<?php

namespace App\Enums;

enum QualityGrade: string
{
    case A = 'a';
    case B = 'b';
    case C = 'c';

    public function label(): string
    {
        return match ($this) {
            self::A => 'Grade A',
            self::B => 'Grade B',
            self::C => 'Grade C',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::A => 'success',
            self::B => 'warning',
            self::C => 'danger',
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
