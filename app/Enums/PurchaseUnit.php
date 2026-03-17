<?php

namespace App\Enums;

enum PurchaseUnit: string
{
    case Kilogram = 'kilogram';
    case Liter = 'liter';
    case Piece = 'piece';
    case Sack = 'sack';
    case Ton = 'ton';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Kilogram => 'Kilogramo',
            self::Liter => 'Litro',
            self::Piece => 'Pieza',
            self::Sack => 'Costal',
            self::Ton => 'Tonelada',
            self::Other => 'Otro',
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
