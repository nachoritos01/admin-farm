<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Concerns\NotifiesNearLimit;
use App\Filament\Resources\SupplierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    use NotifiesNearLimit;

    protected static string $resource = SupplierResource::class;

    protected function afterCreate(): void
    {
        $this->checkNearLimit('suppliers');
    }
}
