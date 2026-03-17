<?php

namespace App\Enums;

enum Breed: string
{
    case RhodeIslandRed = 'rhode_island_red';
    case Leghorn = 'leghorn';
    case PlymouthRock = 'plymouth_rock';
    case Araucana = 'araucana';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::RhodeIslandRed => 'Rhode Island Red',
            self::Leghorn => 'Leghorn',
            self::PlymouthRock => 'Plymouth Rock',
            self::Araucana => 'Araucana',
            self::Other => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RhodeIslandRed => 'danger',
            self::Leghorn => 'warning',
            self::PlymouthRock => 'info',
            self::Araucana => 'success',
            self::Other => 'gray',
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
