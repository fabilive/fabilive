<?php

namespace App\Filament\Resources\AdminUserConversationResource\Pages;

use App\Filament\Resources\AdminUserConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUserConversation extends EditRecord
{
    protected static string $resource = AdminUserConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
