#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────
# plan-conversion.sh
# Reads conversion/context.md + conversion/screenshots/ and launches
# Claude Code in plan mode to generate feature docs in docs/features/
# ─────────────────────────────────────────────────────────────────────
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
CONTEXT_FILE="$ROOT_DIR/conversion/context.md"
SCREENSHOTS_DIR="$ROOT_DIR/conversion/screenshots"
GUIDE_FILE="$ROOT_DIR/docs/guides/convert-poc-to-multitenant.md"

# ── Preflight checks ────────────────────────────────────────────────

if ! command -v claude &>/dev/null; then
  echo "Error: Claude Code CLI not found. Install it first:"
  echo "  npm install -g @anthropic-ai/claude-code"
  exit 1
fi

if [[ ! -f "$CONTEXT_FILE" ]]; then
  echo "Error: conversion/context.md not found."
  echo "Create it following the format in docs/guides/convert-poc-to-multitenant.md"
  exit 1
fi

# Check context.md has been filled out (not still the template)
if grep -q '^\[Name of the proof-of-concept app\]' "$CONTEXT_FILE"; then
  echo "Error: conversion/context.md still has placeholder values."
  echo "Fill it out with your PoC audit before running this script."
  exit 1
fi

# Count screenshots (png, jpg, jpeg, webp)
SCREENSHOT_COUNT=$(find "$SCREENSHOTS_DIR" -type f \( -iname '*.png' -o -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.webp' \) 2>/dev/null | wc -l | tr -d ' ')

if [[ "$SCREENSHOT_COUNT" -eq 0 ]]; then
  echo "Warning: No screenshots found in conversion/screenshots/"
  echo "Screenshots help Claude understand UI patterns. Continue anyway? [y/N]"
  read -r REPLY
  if [[ ! "$REPLY" =~ ^[Yy]$ ]]; then
    echo "Add screenshots and try again."
    exit 0
  fi
fi

# ── Build the prompt ─────────────────────────────────────────────────

SCREENSHOT_LIST=$(find "$SCREENSHOTS_DIR" -type f \( -iname '*.png' -o -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.webp' \) -exec echo "- {}" \; 2>/dev/null | sort)

PROMPT="$(cat <<PROMPT_EOF
You are in PLANNING mode. Your goal is to generate a conversion plan — DO NOT write any implementation code.

Read these files to understand the conversion workflow and rules:
- ${GUIDE_FILE} (full guide with rules)
- CLAUDE.md (project rules)
- .claude/context/architecture.md (existing models and relationships)

Read the PoC context:
- conversion/context.md

Read ALL screenshots in conversion/screenshots/ to visually analyze the PoC UI:
${SCREENSHOT_LIST:-"(no screenshots provided)"}

Then execute Phase 0 from the guide:

1. Analyze conversion/context.md and all screenshots
2. Cross-reference PoC modules against the template's existing capabilities
3. Create docs/conversion-mapping.md with:
   - Module mapping table (PoC Module → Action: SKIP/EXTEND/NEW → Template Equivalent → New Model? → Feature Doc #)
   - Existing models to extend (new columns, new relationships)
   - New roles/permissions mapping
   - Plan limit adjustments for config/saas.php
4. Generate numbered docs/features/XX-*.md files — one per feature to implement:
   - Number sequentially after the last existing feature doc in docs/features/
   - Each doc must follow the feature doc format from the guide (Status: Pending, Overview, Models, Migration, Filament Resource, Seeder, Tests, Screenshots Reference)
   - Respect dependency order
   - Reference which screenshots map to each feature
5. Present the full plan summary for approval

IMPORTANT:
- Follow ALL Conversion Rules from the guide (What to KEEP, CONVERT, SKIP)
- Follow ALL Important Reminders from the guide
- DO NOT write implementation code — only generate planning documents
- Wait for user approval before any implementation begins
PROMPT_EOF
)"

# ── Launch Claude Code ───────────────────────────────────────────────

echo "╔══════════════════════════════════════════════════════════╗"
echo "║         PoC → Multi-Tenant Conversion Planner           ║"
echo "╠══════════════════════════════════════════════════════════╣"
echo "║  Context:      conversion/context.md                    ║"
echo "║  Screenshots:  $SCREENSHOT_COUNT file(s)                           ║"
echo "║  Guide:        docs/guides/convert-poc-to-multitenant.md║"
echo "║  Output:       docs/conversion-mapping.md               ║"
echo "║                docs/features/XX-*.md                    ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""
echo "Launching Claude Code in planning mode..."
echo ""

cd "$ROOT_DIR"
claude --print "$PROMPT"
