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
            self::Kilogram => __('enums.purchase_unit.kilogram'),
            self::Liter => __('enums.purchase_unit.liter'),
            self::Piece => __('enums.purchase_unit.piece'),
            self::Sack => __('enums.purchase_unit.sack'),
            self::Ton => __('enums.purchase_unit.ton'),
            self::Other => __('enums.purchase_unit.other'),
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
