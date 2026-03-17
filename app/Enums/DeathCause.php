<?php

namespace App\Enums;

enum DeathCause: string
{
    case Natural = 'natural';
    case Disease = 'disease';
    case Accident = 'accident';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Natural => 'Natural',
            self::Disease => 'Enfermedad',
            self::Accident => 'Accidente',
            self::Unknown => 'Desconocida',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Natural => 'gray',
            self::Disease => 'danger',
            self::Accident => 'warning',
            self::Unknown => 'info',
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
