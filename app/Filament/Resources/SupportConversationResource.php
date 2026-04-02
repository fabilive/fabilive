<?php

namespace App\Filament\Resources;

use App\Models\SupportConversation;
use App\Filament\Resources\SupportConversationResource\RelationManagers;
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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('context'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('started_at')->dateTime(),
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
            'index' => SupportConversationResource\Pages\ListRecords::route('/'),
        ];
    }
}
