<?php

namespace App\Filament\Resources\GoogleAnalyticsResource\Pages;

use App\Filament\Resources\GoogleAnalyticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoogleAnalytics extends ListRecords
{
    protected static string $resource = GoogleAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
