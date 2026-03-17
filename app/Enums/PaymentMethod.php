<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Card = 'card';
    case Transfer = 'transfer';
    case Other = 'other';
    case CashOnDelivery = 'cash_on_delivery';

    public function label(): string
    {
        return match ($this) {
            self::Cash => __('enums.payment_method.cash'),
            self::Card => __('enums.payment_method.card'),
            self::Transfer => __('enums.payment_method.transfer'),
            self::Other => __('enums.payment_method.other'),
            self::CashOnDelivery => __('enums.payment_method.cash_on_delivery'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Cash => 'success',
            self::Card => 'primary',
            self::Transfer => 'info',
            self::Other => 'gray',
            self::CashOnDelivery => 'warning',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Cash => 'bg-green-100 text-green-700',
            self::Card => 'bg-blue-100 text-blue-700',
            self::Transfer => 'bg-cyan-100 text-cyan-700',
            self::Other => 'bg-gray-100 text-gray-700',
            self::CashOnDelivery => 'bg-yellow-100 text-yellow-700',
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
