<?php

namespace App\Enums;

enum EggSize: string
{
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';
    case ExtraLarge = 'extra_large';
    case Jumbo = 'jumbo';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Small => 'Small',
            self::Medium => 'Medium',
            self::Large => 'Large',
            self::ExtraLarge => 'Extra Large',
            self::Jumbo => 'Jumbo',
            self::Mixed => 'Mixed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Small => 'gray',
            self::Medium => 'info',
            self::Large => 'success',
            self::ExtraLarge => 'warning',
            self::Jumbo => 'primary',
            self::Mixed => 'gray',
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
