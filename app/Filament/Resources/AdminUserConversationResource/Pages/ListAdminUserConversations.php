<?php

namespace App\Filament\Resources\AdminUserConversationResource\Pages;

use App\Filament\Resources\AdminUserConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminUserConversations extends ListRecords
{
    protected static string $resource = AdminUserConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
