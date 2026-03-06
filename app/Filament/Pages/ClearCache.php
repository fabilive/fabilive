<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class ClearCache extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static string $view = 'filament.pages.clear-cache';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Clear Cache';

    protected static ?int $navigationSort = 100;

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        Notification::make()
            ->title('Cache cleared successfully!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('Clear Cache Now')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => $this->clearCache()),
        ];
    }
}
