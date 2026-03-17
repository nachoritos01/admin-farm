<?php

namespace App\Filament\Resources\ProductionRecordResource\Pages;

use App\Filament\Resources\ProductionRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductionRecord extends EditRecord
{
    protected static string $resource = ProductionRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
