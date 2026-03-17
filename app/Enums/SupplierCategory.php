<?php

namespace App\Enums;

enum SupplierCategory: string
{
    case Feed = 'feed';
    case Medicine = 'medicine';
    case Equipment = 'equipment';
    case Packaging = 'packaging';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Feed => __('enums.supplier_category.feed'),
            self::Medicine => __('enums.supplier_category.medicine'),
            self::Equipment => __('enums.supplier_category.equipment'),
            self::Packaging => __('enums.supplier_category.packaging'),
            self::Other => __('enums.supplier_category.other'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Feed => 'success',
            self::Medicine => 'danger',
            self::Equipment => 'info',
            self::Packaging => 'warning',
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
