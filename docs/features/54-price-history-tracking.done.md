# Feature #54 — Add Price History Tracking

**Priority:** MEDIUM
**Status:** Done
**Depends on:** None
**Phase:** 1

## Summary
Automatic tracking of item price changes over time, recording old and new prices whenever an Item's price is updated, with a percentage change accessor for trend analysis.

## Scope
- PriceHistory model that auto-records on Item price change
- PriceHistoriesRelationManager on ItemResource
- change_percentage computed accessor

## Implementation Details
- **Model:** `PriceHistory` (item_id, old_price, new_price, changed_at, changed_by)
- **Accessor:** `change_percentage` computed as `((new_price - old_price) / old_price) * 100`
- **Migration:** `create_price_histories_table` with item_id FK
- **Observer/Event:** Item model `updating` event triggers PriceHistory creation when `price` field changes
- **Relation Manager:** `PriceHistoriesRelationManager` on ItemResource showing history in reverse chronological order with change percentage badge (green for decrease, red for increase)

## Files Changed
- `app/Models/PriceHistory.php`
- `database/migrations/xxxx_create_price_histories_table.php`
- `app/Models/Item.php` (added priceHistories relationship, updating observer)
- `app/Filament/Resources/ItemResource/RelationManagers/PriceHistoriesRelationManager.php`
- `app/Filament/Resources/ItemResource.php` (registered relation manager)

## Verification
- Edit an item's price and verify a PriceHistory record is created automatically
- Check the relation manager on ItemResource shows the history
- Verify change_percentage displays correctly (positive/negative)
- Confirm no history is created when price remains unchanged
- Run `composer test` and `composer analyse` with no errors
