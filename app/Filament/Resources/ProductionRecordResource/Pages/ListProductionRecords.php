<?php

namespace App\Filament\Resources\ProductionRecordResource\Pages;

use App\Filament\Resources\ProductionRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionRecords extends ListRecords
{
    protected static string $resource = ProductionRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
