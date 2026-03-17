<?php

namespace App\Enums;

enum UnitType: string
{
    case Tray = 'tray';
    case Kilogram = 'kilogram';
    case Piece = 'piece';

    public function label(): string
    {
        return match ($this) {
            self::Tray => 'Tray (30)',
            self::Kilogram => 'Kilogram',
            self::Piece => 'Piece',
        };
    }

    public function toEggs(): int
    {
        return match ($this) {
            self::Tray => 30,
            self::Kilogram => 16,
            self::Piece => 1,
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
