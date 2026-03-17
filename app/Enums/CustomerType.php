<?php

namespace App\Enums;

enum CustomerType: string
{
    case Retail = 'retail';
    case Wholesale = 'wholesale';
    case Store = 'store';
    case Restaurant = 'restaurant';
    case NaturalStore = 'natural_store';

    public function label(): string
    {
        return match ($this) {
            self::Retail => 'Retail',
            self::Wholesale => 'Wholesale',
            self::Store => 'Store',
            self::Restaurant => 'Restaurant',
            self::NaturalStore => 'Natural Store',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Retail => 'info',
            self::Wholesale => 'success',
            self::Store => 'warning',
            self::Restaurant => 'primary',
            self::NaturalStore => 'purple',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Retail => 'heroicon-o-user',
            self::Wholesale => 'heroicon-o-building-storefront',
            self::Store => 'heroicon-o-building-office',
            self::Restaurant => 'heroicon-o-building-storefront',
            self::NaturalStore => 'heroicon-o-heart',
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
