<?php

namespace App\Filament\Resources\SupportConversationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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
                // Handled by the persistent chat box on the View page
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
