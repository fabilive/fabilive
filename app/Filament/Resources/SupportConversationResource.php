<?php

namespace App\Filament\Resources;

use App\Models\SupportConversation;
use App\Filament\Resources\SupportConversationResource\RelationManagers;
use App\Filament\Resources\SupportConversationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportConversationResource extends Resource
{
    protected static ?string $model = SupportConversation::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Support System';
    protected static ?string $navigationLabel = 'Chat Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'bot_active' => 'Bot Active',
                        'waiting_agent' => 'Waiting for Agent',
                        'assigned' => 'Assigned',
                        'ended' => 'Ended',
                        'rated' => 'Rated',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('requester.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('context'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'bot_active',
                        'warning' => 'waiting_agent',
                        'success' => 'assigned',
                        'danger' => 'ended',
                        'secondary' => 'rated',
                    ]),
                Tables\Columns\TextColumn::make('assignedAgent.name')->label('Agent'),
                Tables\Columns\TextColumn::make('started_at')->dateTime()->sortable(),
            ])
            ->actions([ 
                Tables\Actions\ViewAction::make(), 
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'view' => Pages\ViewSupportConversation::route('/{record}'),
        ];
    }
}
