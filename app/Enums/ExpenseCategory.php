<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Feed = 'feed';
    case Medicine = 'medicine';
    case Equipment = 'equipment';
    case Packaging = 'packaging';
    case Labor = 'labor';
    case Utilities = 'utilities';
    case Transport = 'transport';
    case Maintenance = 'maintenance';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Feed => __('enums.expense_category.feed'),
            self::Medicine => __('enums.expense_category.medicine'),
            self::Equipment => __('enums.expense_category.equipment'),
            self::Packaging => __('enums.expense_category.packaging'),
            self::Labor => __('enums.expense_category.labor'),
            self::Utilities => __('enums.expense_category.utilities'),
            self::Transport => __('enums.expense_category.transport'),
            self::Maintenance => __('enums.expense_category.maintenance'),
            self::Other => __('enums.expense_category.other'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Feed => 'success',
            self::Medicine => 'danger',
            self::Equipment => 'info',
            self::Packaging => 'warning',
            self::Labor => 'primary',
            self::Utilities => 'gray',
            self::Transport => 'info',
            self::Maintenance => 'warning',
            self::Other => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Feed => 'heroicon-o-fire',
            self::Medicine => 'heroicon-o-beaker',
            self::Equipment => 'heroicon-o-wrench-screwdriver',
            self::Packaging => 'heroicon-o-archive-box',
            self::Labor => 'heroicon-o-user-group',
            self::Utilities => 'heroicon-o-bolt',
            self::Transport => 'heroicon-o-truck',
            self::Maintenance => 'heroicon-o-cog-6-tooth',
            self::Other => 'heroicon-o-ellipsis-horizontal-circle',
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
