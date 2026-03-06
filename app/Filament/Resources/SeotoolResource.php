<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeotoolResource\Pages;
use App\Filament\Resources\SeotoolResource\RelationManagers;
use App\Models\Seotool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeotoolResource extends Resource
{
    protected static ?string $model = Seotool::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('google_analytics')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('facebook_pixel')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_keys')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListSeotools::route('/'),
            'create' => Pages\CreateSeotool::route('/create'),
            'edit' => Pages\EditSeotool::route('/{record}/edit'),
        ];
    }
}
