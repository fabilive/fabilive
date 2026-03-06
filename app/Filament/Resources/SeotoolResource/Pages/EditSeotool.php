<?php

namespace App\Filament\Resources\SeotoolResource\Pages;

use App\Filament\Resources\SeotoolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeotool extends EditRecord
{
    protected static string $resource = SeotoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
