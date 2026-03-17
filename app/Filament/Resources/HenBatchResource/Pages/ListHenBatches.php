<?php

namespace App\Filament\Resources\HenBatchResource\Pages;

use App\Filament\Resources\HenBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHenBatches extends ListRecords
{
    protected static string $resource = HenBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            HenBatchResource\Widgets\HenBatchStats::class,
        ];
    }
}
