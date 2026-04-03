<?php

namespace App\Filament\Resources\SupportFaqCategoryResource\Pages;

use App\Filament\Resources\SupportFaqCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;

class EditRecord extends BaseEditRecord
{
    protected static string $resource = SupportFaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
