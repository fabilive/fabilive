<?php

namespace App\Filament\Resources\GeneralsettingResource\Pages;

use App\Filament\Resources\GeneralsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralsetting extends EditRecord
{
    protected static string $resource = GeneralsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
