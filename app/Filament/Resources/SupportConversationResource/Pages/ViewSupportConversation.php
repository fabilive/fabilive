<?php

namespace App\Filament\Resources\SupportConversationResource\Pages;

use App\Filament\Resources\SupportConversationResource;
use App\Models\SupportMessage;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
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
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Customer Context')
                    ->schema([
                        Components\TextEntry::make('requester.name')->label('User'),
                        Components\TextEntry::make('status')->badge(),
                        Components\TextEntry::make('context')->label('App Side'),
                    ])->columns(3),
                
                Components\Section::make('Join Conversation')
                    ->description('Type below to reply. Sending a message will automatically assign you to this chat.')
                    ->schema([
                        Components\View::make('filament.resources.support-conversation.chat-box'),
                    ])
                    ->hidden(fn ($record) => $record->status === 'ended' || $record->status === 'rated'),
            ]);
    }
}
