<?php

namespace App\Enums;

enum HenBatchStatus: string
{
    case Active = 'active';
    case Resting = 'resting';
    case Molting = 'molting';
    case Retired = 'retired';
    case Sold = 'sold';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Resting => 'Resting',
            self::Molting => 'Molting',
            self::Retired => 'Retired',
            self::Sold => 'Sold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Resting => 'warning',
            self::Molting => 'info',
            self::Retired => 'gray',
            self::Sold => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',
            self::Resting => 'heroicon-o-pause-circle',
            self::Molting => 'heroicon-o-arrow-path',
            self::Retired => 'heroicon-o-archive-box',
            self::Sold => 'heroicon-o-banknotes',
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
