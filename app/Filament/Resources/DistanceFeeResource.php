<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistanceFeeResource\Pages;
use App\Models\DistanceFee;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DistanceFeeResource extends Resource
{
    protected static ?string $model = DistanceFee::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'General Settings';

    protected static ?string $navigationLabel = 'Distance Fee Module';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListDistanceFees::route('/'),
            'create' => Pages\CreateDistanceFee::route('/create'),
            'edit' => Pages\EditDistanceFee::route('/{record}/edit'),
        ];
    }
}
