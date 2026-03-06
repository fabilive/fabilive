<?php

namespace App\Filament\Resources\ArrivalSectionResource\Pages;

use App\Filament\Resources\ArrivalSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArrivalSection extends EditRecord
{
    protected static string $resource = ArrivalSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
