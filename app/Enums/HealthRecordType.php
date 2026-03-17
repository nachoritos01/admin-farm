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
            self::Vaccination => __('enums.health_record_type.vaccination'),
            self::Treatment => __('enums.health_record_type.treatment'),
            self::Observation => __('enums.health_record_type.observation'),
            self::Mortality => __('enums.health_record_type.mortality'),
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
