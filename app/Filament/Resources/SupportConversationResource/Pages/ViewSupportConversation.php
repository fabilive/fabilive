<?php

namespace App\Filament\Resources\SupportConversationResource\Pages;

use App\Filament\Resources\SupportConversationResource;
use App\Models\SupportMessage;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewSupportConversation extends ViewRecord implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
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
    public function mount($record): void
    {
        parent::mount($record);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quick Reply')
                    ->description('Type your message below and click "Send". This will also assign you to the conversation.')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('')
                            ->placeholder('Type your reply to the user here...')
                            ->rows(3)
                            ->required(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label('Optional Attachment')
                            ->directory('support_attachments')
                            ->visibility('public'),
                    ])
                    ->footerActions([
                        Forms\Components\Actions\Action::make('sendReply')
                            ->label('Send Reply')
                            ->color('primary')
                            ->icon('heroicon-o-paper-airplane')
                            ->action('sendReply')
                    ])
                    ->hidden(fn ($record) => $record->status === 'ended' || $record->status === 'rated'),
            ])
            ->statePath('data');
    }

    public function sendReply(): void
    {
        $data = $this->form->getState();
        $record = $this->getRecord();

        $msgData = [
            'conversation_id' => $record->id,
            'sender_type' => 'agent',
            'sender_id' => Auth::id(),
            'type' => 'text',
            'body_text' => $data['message'],
        ];

        if ($data['attachment']) {
            $msgData['type'] = 'image';
            $msgData['attachment_url'] = '/storage/' . $data['attachment'];
        }

        SupportMessage::create($msgData);

        // Update status and assignment
        if ($record->status === 'bot_active' || $record->status === 'waiting_agent') {
            $record->status = 'assigned';
            $record->assigned_agent_admin_id = Auth::id();
            $record->assigned_at = now();
            $record->save();

            \App\Models\SupportConversationEvent::create([
                'conversation_id' => $record->id,
                'actor_type' => 'agent',
                'actor_id' => Auth::id(),
                'event' => 'assigned'
            ]);
        }

        $this->form->fill();

        \Filament\Notifications\Notification::make()
            ->title('Reply Sent')
            ->success()
            ->send();
            
        // Refresh the page to show latest messages in the relation manager
        $this->redirect(SupportConversationResource::getUrl('view', ['record' => $record]));
    }
}
