<?php

namespace App\Filament\Resources\DeliveryJobResource\Pages;

use App\Filament\Resources\DeliveryJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDeliveryJobs extends ManageRecords
{
    protected static string $resource = DeliveryJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
