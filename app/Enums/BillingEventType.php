<?php

namespace App\Enums;

enum BillingEventType: string
{
    case SubscriptionStarted = 'subscription_started';
    case PlanChanged = 'plan_changed';
    case PluginActivated = 'plugin_activated';
    case PluginDeactivated = 'plugin_deactivated';
    case SubscriptionCancelled = 'subscription_cancelled';

    public function label(): string
    {
        return match ($this) {
            self::SubscriptionStarted => __('enums.billing_event_type.subscription_started'),
            self::PlanChanged => __('enums.billing_event_type.plan_changed'),
            self::PluginActivated => __('enums.billing_event_type.plugin_activated'),
            self::PluginDeactivated => __('enums.billing_event_type.plugin_deactivated'),
            self::SubscriptionCancelled => __('enums.billing_event_type.subscription_cancelled'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SubscriptionStarted => 'heroicon-o-check-circle',
            self::PlanChanged => 'heroicon-o-arrow-path',
            self::PluginActivated => 'heroicon-o-puzzle-piece',
            self::PluginDeactivated => 'heroicon-o-x-circle',
            self::SubscriptionCancelled => 'heroicon-o-no-symbol',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SubscriptionStarted => 'success',
            self::PlanChanged => 'info',
            self::PluginActivated => 'success',
            self::PluginDeactivated => 'warning',
            self::SubscriptionCancelled => 'danger',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }
}
