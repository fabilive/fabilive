<?php

namespace App\Filament\Resources\SupportBotRuleResource\Pages;

use App\Filament\Resources\SupportBotRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord as BaseEditRecord;

class EditRecord extends BaseEditRecord
{
    protected static string $resource = SupportBotRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
