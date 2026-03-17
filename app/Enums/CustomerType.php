<?php

namespace App\Enums;

enum CustomerType: string
{
    case Retail = 'retail';
    case Wholesale = 'wholesale';
    case Store = 'store';

    public function label(): string
    {
        return match ($this) {
            self::Retail => 'Retail',
            self::Wholesale => 'Wholesale',
            self::Store => 'Store',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Retail => 'info',
            self::Wholesale => 'success',
            self::Store => 'warning',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Retail => 'heroicon-o-user',
            self::Wholesale => 'heroicon-o-building-storefront',
            self::Store => 'heroicon-o-building-office',
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
