<?php

namespace App\Filament\Resources\DistanceFeeResource\Pages;

use App\Filament\Resources\DistanceFeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistanceFee extends EditRecord
{
    protected static string $resource = DistanceFeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
