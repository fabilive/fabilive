<?php

namespace App\Filament\Resources\CustomReferralResource\Pages;

use App\Filament\Resources\CustomReferralResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomReferrals extends ListRecords
{
    protected static string $resource = CustomReferralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
