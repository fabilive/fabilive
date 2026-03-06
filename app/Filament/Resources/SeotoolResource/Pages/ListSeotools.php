<?php

namespace App\Filament\Resources\SeotoolResource\Pages;

use App\Filament\Resources\SeotoolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeotools extends ListRecords
{
    protected static string $resource = SeotoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
