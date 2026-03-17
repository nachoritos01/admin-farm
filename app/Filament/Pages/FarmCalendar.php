<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\ProductionRecord;
use App\Models\Shipment;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class FarmCalendar extends Page
{
    protected static ?string $title = 'Farm Calendar';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.farm-calendar';

    public bool $showProduction = true;

    public bool $showOrders = true;

    public bool $showShipments = true;

    public static function canAccess(): bool
    {
        return hasModule('production');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('production');
    }

    /** @return array<int, array<string, mixed>> */
    public function getProductionEvents(): array
    {
        $tenantId = currentTenant()?->id;

        if (! $tenantId) {
            return [];
        }

        return ProductionRecord::where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subMonths(3))
            ->get()
            ->map(fn (ProductionRecord $r) => [
                'title' => "{$r->net_production} eggs",
                'start' => $r->date->format('Y-m-d'),
                'color' => '#22c55e',
                'url' => route('filament.admin.resources.production-records.edit', $r),
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function getOrderEvents(): array
    {
        $tenantId = currentTenant()?->id;

        if (! $tenantId) {
            return [];
        }

        return Order::where('tenant_id', $tenantId)
            ->whereNotNull('delivery_date')
            ->where('delivery_date', '>=', now()->subMonths(3))
            ->get()
            ->map(fn (Order $o) => [
                'title' => "Order #{$o->id} - {$o->customer_name}",
                'start' => Carbon::parse($o->delivery_date)->format('Y-m-d'),
                'color' => '#3b82f6',
                'url' => route('filament.admin.resources.orders.edit', $o),
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function getShipmentEvents(): array
    {
        $tenantId = currentTenant()?->id;

        if (! $tenantId) {
            return [];
        }

        return Shipment::where('tenant_id', $tenantId)
            ->where('scheduled_date', '>=', now()->subMonths(3))
            ->get()
            ->map(fn (Shipment $s) => [
                'title' => "Shipment #{$s->id}" . ($s->zone ? " - {$s->zone}" : ''),
                'start' => $s->scheduled_date->format('Y-m-d'),
                'color' => '#f97316',
                'url' => route('filament.admin.resources.shipments.edit', $s),
            ])
            ->all();
    }

    /** @return string */
    public function getEvents(): string
    {
        $events = [];

        if ($this->showProduction) {
            $events = array_merge($events, $this->getProductionEvents());
        }

        if ($this->showOrders) {
            $events = array_merge($events, $this->getOrderEvents());
        }

        if ($this->showShipments) {
            $events = array_merge($events, $this->getShipmentEvents());
        }

        return json_encode($events);
    }

    public static function getNavigationLabel(): string
    {
        return __('Calendar');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Farm');
    }
}
