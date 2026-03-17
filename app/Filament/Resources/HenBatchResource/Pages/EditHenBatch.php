<?php

namespace App\Filament\Resources\HenBatchResource\Pages;

use App\Filament\Resources\HenBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHenBatch extends EditRecord
{
    protected static string $resource = HenBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
