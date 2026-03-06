<?php

namespace App\Filament\Resources\ManageAgreementResource\Pages;

use App\Filament\Resources\ManageAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageAgreement extends EditRecord
{
    protected static string $resource = ManageAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
