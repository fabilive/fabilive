<?php

namespace App\Filament\Resources\SupportConversationResource\RelationManagers;

use App\Models\SupportConversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('body_text')
                    ->label('Reply')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('attachment_url')
                    ->label('Attachment')
                    ->directory('support_attachments')
                    ->visibility('public'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body_text')
            ->columns([
                Tables\Columns\TextColumn::make('sender_type')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'user' => 'gray',
                        'bot' => 'info',
                        'agent' => 'success',
                        'admin' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('body_text')->wrap()->label('Message'),
                Tables\Columns\ImageColumn::make('attachment_url')->label('File'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Time'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Send Reply')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sender_type'] = 'agent';
                        $data['sender_id'] = Auth::id();
                        $data['type'] = isset($data['attachment_url']) ? 'image' : 'text';
                        return $data;
                    })
                    ->after(function ($record) {
                        // Mark conversation as assigned if waiting
                        $conversation = $this->getOwnerRecord();
                        if ($conversation->status === 'waiting_agent' || $conversation->status === 'bot_active') {
                            $conversation->status = 'assigned';
                            $conversation->assigned_agent_admin_id = Auth::id();
                            $conversation->assigned_at = now();
                            $conversation->save();

                            \App\Models\SupportConversationEvent::create([
                                'conversation_id' => $conversation->id,
                                'actor_type' => 'agent',
                                'actor_id' => Auth::id(),
                                'event' => 'assigned'
                            ]);
                        }
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
