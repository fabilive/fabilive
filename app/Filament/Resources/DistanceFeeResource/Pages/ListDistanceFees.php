<?php

namespace App\Filament\Resources\DistanceFeeResource\Pages;

use App\Filament\Resources\DistanceFeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistanceFees extends ListRecords
{
    protected static string $resource = DistanceFeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
