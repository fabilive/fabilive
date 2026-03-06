<?php

namespace App\Filament\Resources\PagesettingResource\Pages;

use App\Filament\Resources\PagesettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagesettings extends ListRecords
{
    protected static string $resource = PagesettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
