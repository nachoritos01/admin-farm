<?php

namespace App\Filament\Pages;

use App\Services\InventoryService;
use Filament\Pages\Page;

class Inventory extends Page
{
    protected static ?string $title = 'Egg Inventory';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 30;

    protected static string $view = 'filament.pages.inventory';

    public array $stock = [];

    public bool $isLowStock = false;

    public static function canAccess(): bool
    {
        return hasModule('inventory')
            && (auth()->user()?->can('inventory.view') ?? false);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return hasModule('inventory');
    }

    public function mount(): void
    {
        $service = app(InventoryService::class);
        $this->stock = $service->getTotalStock();
        $this->isLowStock = $service->isLowStock();
    }

    public static function getNavigationLabel(): string
    {
        return __('Inventory');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Farm');
    }
}
