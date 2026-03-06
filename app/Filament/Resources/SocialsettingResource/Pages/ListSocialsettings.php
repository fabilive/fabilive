<?php

namespace App\Filament\Resources\SocialsettingResource\Pages;

use App\Filament\Resources\SocialsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialsettings extends ListRecords
{
    protected static string $resource = SocialsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
