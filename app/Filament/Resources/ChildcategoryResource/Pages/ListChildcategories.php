<?php

namespace App\Filament\Resources\ChildcategoryResource\Pages;

use App\Filament\Resources\ChildcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChildcategories extends ListRecords
{
    protected static string $resource = ChildcategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
