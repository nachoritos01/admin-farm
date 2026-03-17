<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Active = 'active';
    case Trialing = 'trialing';
    case Canceled = 'canceled';
    case PastDue = 'past_due';
    case Incomplete = 'incomplete';

    public function label(): string
    {
        return match ($this) {
            self::Active => __('enums.subscription_status.active'),
            self::Trialing => __('enums.subscription_status.trialing'),
            self::Canceled => __('enums.subscription_status.canceled'),
            self::PastDue => __('enums.subscription_status.past_due'),
            self::Incomplete => __('enums.subscription_status.incomplete'),
        };
    }

    public function isValid(): bool
    {
        return match ($this) {
            self::Active, self::Trialing => true,
            default => false,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
