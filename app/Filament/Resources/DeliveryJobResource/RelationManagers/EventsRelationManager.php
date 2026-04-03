<?php

namespace App\Filament\Resources\DeliveryJobResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'job_created' => 'info',
                        'status_changed' => 'warning',
                        'delivered_pending_verification' => 'danger',
                        'job_verified_and_settled' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('actor_type')
                    ->label('Actor'),
                Tables\Columns\TextColumn::make('meta_json')
                    ->label('Metadata')
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->wrap()
                    ->limit(100),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
