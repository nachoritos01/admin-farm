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
            self::Natural => __('enums.death_cause.natural'),
            self::Disease => __('enums.death_cause.disease'),
            self::Accident => __('enums.death_cause.accident'),
            self::Unknown => __('enums.death_cause.unknown'),
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
