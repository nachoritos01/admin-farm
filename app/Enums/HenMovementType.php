<?php

namespace App\Enums;

enum HenMovementType: string
{
    case Addition = 'addition';
    case Removal = 'removal';
    case Death = 'death';
    case Transfer = 'transfer';
    case Sale = 'sale';

    public function label(): string
    {
        return match ($this) {
            self::Addition => __('enums.hen_movement_type.addition'),
            self::Removal => __('enums.hen_movement_type.removal'),
            self::Death => __('enums.hen_movement_type.death'),
            self::Transfer => __('enums.hen_movement_type.transfer'),
            self::Sale => __('enums.hen_movement_type.sale'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Addition => 'success',
            self::Removal => 'warning',
            self::Death => 'danger',
            self::Transfer => 'info',
            self::Sale => 'primary',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Addition => 'heroicon-o-plus-circle',
            self::Removal => 'heroicon-o-minus-circle',
            self::Death => 'heroicon-o-x-circle',
            self::Transfer => 'heroicon-o-arrows-right-left',
            self::Sale => 'heroicon-o-banknotes',
        };
    }

    public function isAddition(): bool
    {
        return $this === self::Addition;
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
