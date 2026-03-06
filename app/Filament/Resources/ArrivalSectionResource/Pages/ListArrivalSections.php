<?php

namespace App\Filament\Resources\ArrivalSectionResource\Pages;

use App\Filament\Resources\ArrivalSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArrivalSections extends ListRecords
{
    protected static string $resource = ArrivalSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
