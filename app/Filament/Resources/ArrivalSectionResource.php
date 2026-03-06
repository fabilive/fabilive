<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArrivalSectionResource\Pages;
use App\Filament\Resources\ArrivalSectionResource\RelationManagers;
use App\Models\ArrivalSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArrivalSectionResource extends Resource
{
    protected static ?string $model = ArrivalSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Home Page Settings';

    protected static ?string $navigationLabel = 'Arrival Section';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(500),
                Forms\Components\TextInput::make('header')
                    ->required()
                    ->maxLength(500),
                Forms\Components\TextInput::make('photo')
                    ->required()
                    ->maxLength(300),
                Forms\Components\TextInput::make('up_sale')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('url')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('up_sale')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArrivalSections::route('/'),
            'create' => Pages\CreateArrivalSection::route('/create'),
            'edit' => Pages\EditArrivalSection::route('/{record}/edit'),
        ];
    }
}
