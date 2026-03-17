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
            self::Addition => 'Addition',
            self::Removal => 'Removal',
            self::Death => 'Death',
            self::Transfer => 'Transfer',
            self::Sale => 'Sale',
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
