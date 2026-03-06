<?php

namespace App\Filament\Resources\DeliveryJobResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StopsRelationManager extends RelationManager
{
    protected static string $relationship = 'stops';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location_text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'ready' => 'Ready',
                        'arrived' => 'Arrived',
                        'picked_up' => 'Picked Up',
                        'completed' => 'Completed',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('location_text')
            ->columns([
                Tables\Columns\TextColumn::make('sequence')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pickup' => 'info',
                        'dropoff' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('location_text')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
