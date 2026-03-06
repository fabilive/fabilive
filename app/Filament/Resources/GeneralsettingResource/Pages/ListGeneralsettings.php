<?php

namespace App\Filament\Resources\GeneralsettingResource\Pages;

use App\Filament\Resources\GeneralsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralsettings extends ListRecords
{
    protected static string $resource = GeneralsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
