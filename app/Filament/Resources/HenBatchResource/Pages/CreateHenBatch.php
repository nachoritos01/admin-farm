<?php

namespace App\Filament\Resources\HenBatchResource\Pages;

use App\Filament\Concerns\NotifiesNearLimit;
use App\Filament\Resources\HenBatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHenBatch extends CreateRecord
{
    use NotifiesNearLimit;

    protected static string $resource = HenBatchResource::class;

    /** @param  array<string, mixed>  $data */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['current_count'] = $data['initial_count'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->checkNearLimit('hen_batches');
    }
}
