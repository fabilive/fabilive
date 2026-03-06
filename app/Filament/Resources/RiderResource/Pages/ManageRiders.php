<?php

namespace App\Filament\Resources\RiderResource\Pages;

use App\Filament\Resources\RiderResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRiders extends ManageRecords
{
    protected static string $resource = RiderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
