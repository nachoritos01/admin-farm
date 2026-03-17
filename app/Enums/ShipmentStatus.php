<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case Scheduled = 'scheduled';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => __('enums.shipment_status.scheduled'),
            self::InTransit => __('enums.shipment_status.in_transit'),
            self::Delivered => __('enums.shipment_status.delivered'),
            self::Cancelled => __('enums.shipment_status.cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Scheduled => 'warning',
            self::InTransit => 'info',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Scheduled => 'heroicon-o-clock',
            self::InTransit => 'heroicon-o-truck',
            self::Delivered => 'heroicon-o-check-circle',
            self::Cancelled => 'heroicon-o-x-circle',
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
