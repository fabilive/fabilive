<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryFeeResource\Pages;
use App\Filament\Resources\DeliveryFeeResource\RelationManagers;
use App\Models\DeliveryFee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryFeeResource extends Resource
{
    protected static ?string $model = DeliveryFee::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'General Settings';

    protected static ?string $navigationLabel = 'Delivery Fee Module';

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
            'index' => Pages\ListDeliveryFees::route('/'),
            'create' => Pages\CreateDeliveryFee::route('/create'),
            'edit' => Pages\EditDeliveryFee::route('/{record}/edit'),
        ];
    }
}
