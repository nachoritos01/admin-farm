<?php

namespace App\Enums;

enum HealthRecordType: string
{
    case Vaccination = 'vaccination';
    case Treatment = 'treatment';
    case Observation = 'observation';
    case Mortality = 'mortality';

    public function label(): string
    {
        return match ($this) {
            self::Vaccination => 'Vaccination',
            self::Treatment => 'Treatment',
            self::Observation => 'Observation',
            self::Mortality => 'Mortality',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Vaccination => 'success',
            self::Treatment => 'warning',
            self::Observation => 'info',
            self::Mortality => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Vaccination => 'heroicon-o-shield-check',
            self::Treatment => 'heroicon-o-beaker',
            self::Observation => 'heroicon-o-eye',
            self::Mortality => 'heroicon-o-exclamation-triangle',
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
