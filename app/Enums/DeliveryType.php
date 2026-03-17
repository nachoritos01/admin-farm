<?php

namespace App\Enums;

enum DeliveryType: string
{
    case Pickup = 'pickup';
    case Delivery = 'delivery';

    public function label(): string
    {
        return match ($this) {
            self::Pickup => __('enums.delivery_type.pickup'),
            self::Delivery => __('enums.delivery_type.delivery'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pickup => 'info',
            self::Delivery => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pickup => 'heroicon-o-building-storefront',
            self::Delivery => 'heroicon-o-truck',
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
