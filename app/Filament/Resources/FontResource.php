<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FontResource\Pages;
use App\Filament\Resources\FontResource\RelationManagers;
use App\Models\Font;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FontResource extends Resource
{
    protected static ?string $model = Font::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('is_default')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('font_family')
                    ->maxLength(100)
                    ->default(null),
                Forms\Components\TextInput::make('font_value')
                    ->maxLength(100)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('is_default')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('font_family')
                    ->searchable(),
                Tables\Columns\TextColumn::make('font_value')
                    ->searchable(),
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
            'index' => Pages\ListFonts::route('/'),
            'create' => Pages\CreateFont::route('/create'),
            'edit' => Pages\EditFont::route('/{record}/edit'),
        ];
    }
}
