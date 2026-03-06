<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Home Page Settings';

    protected static ?string $navigationLabel = 'Sliders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('subtitle_text')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subtitle_size')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('subtitle_color')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('subtitle_anime')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Textarea::make('title_text')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title_size')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('title_color')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('title_anime')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Textarea::make('details_text')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('details_size')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('details_color')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('details_anime')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('photo')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('position')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Textarea::make('link')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subtitle_size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle_anime')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_anime')
                    ->searchable(),
                Tables\Columns\TextColumn::make('details_size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('details_color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('details_anime')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
