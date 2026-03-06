<?php

namespace App\Filament\Resources\WalletLedgerResource\Pages;

use App\Filament\Resources\WalletLedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWalletLedgers extends ManageRecords
{
    protected static string $resource = WalletLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
