<?php

namespace App\Filament\Resources\AffliateBonusResource\Pages;

use App\Filament\Resources\AffliateBonusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffliateBonus extends EditRecord
{
    protected static string $resource = AffliateBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
