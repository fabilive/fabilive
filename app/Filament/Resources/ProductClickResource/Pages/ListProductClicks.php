<?php

namespace App\Filament\Resources\ProductClickResource\Pages;

use App\Filament\Resources\ProductClickResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductClicks extends ListRecords
{
    protected static string $resource = ProductClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
