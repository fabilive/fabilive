<?php

namespace App\Filament\Resources\ProductClickResource\Pages;

use App\Filament\Resources\ProductClickResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductClick extends EditRecord
{
    protected static string $resource = ProductClickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
