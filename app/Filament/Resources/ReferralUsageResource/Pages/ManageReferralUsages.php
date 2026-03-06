<?php

namespace App\Filament\Resources\ReferralUsageResource\Pages;

use App\Filament\Resources\ReferralUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReferralUsages extends ManageRecords
{
    protected static string $resource = ReferralUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
