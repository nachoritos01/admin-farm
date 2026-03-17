<?php

namespace App\Enums;

enum BillingPeriod: string
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => __('enums.billing_period.monthly'),
            self::Yearly => __('enums.billing_period.yearly'),
        };
    }

    public function stripeKey(): string
    {
        return match ($this) {
            self::Monthly => 'stripe_monthly',
            self::Yearly => 'stripe_yearly',
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
