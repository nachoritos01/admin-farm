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
            self::Small => __('enums.egg_size.small'),
            self::Medium => __('enums.egg_size.medium'),
            self::Large => __('enums.egg_size.large'),
            self::ExtraLarge => __('enums.egg_size.extra_large'),
            self::Jumbo => __('enums.egg_size.jumbo'),
            self::Mixed => __('enums.egg_size.mixed'),
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
