<?php

namespace App\Filament\Resources\PagesettingResource\Pages;

use App\Filament\Resources\PagesettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPagesetting extends EditRecord
{
    protected static string $resource = PagesettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
