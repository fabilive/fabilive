<?php

namespace App\Filament\Resources\SupportConversationResource\Pages;

use App\Filament\Resources\SupportConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewSupportConversation extends ViewRecord
{
    protected static string $resource = SupportConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('end_conversation')
                ->label('End Conversation')
                ->color('danger')
                ->requiresConfirmation()
                ->hidden(fn ($record) => $record->status === 'ended' || $record->status === 'rated')
                ->action(function ($record) {
                    $record->status = 'ended';
                    $record->ended_at = now();
                    $record->ended_by = 'agent';
                    $record->save();

                    \App\Models\SupportConversationEvent::create([
                        'conversation_id' => $record->id,
                        'actor_type' => 'agent',
                        'actor_id' => Auth::id(),
                        'event' => 'ended'
                    ]);

                    \App\Models\SupportMessage::create([
                        'conversation_id' => $record->id,
                        'sender_type' => 'system',
                        'body_text' => 'This conversation has been ended by the agent.'
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Conversation Ended')
                        ->success()
                        ->send();
                }),
        ];
    }
}
