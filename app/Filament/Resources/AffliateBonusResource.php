<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffliateBonusResource\Pages;
use App\Models\AffliateBonus;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AffliateBonusResource extends Resource
{
    protected static ?string $model = AffliateBonus::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';

    protected static ?string $navigationGroup = 'Financials';

    protected static ?string $navigationLabel = 'Commission Earning';

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
            'index' => Pages\ListAffliateBonuses::route('/'),
            'create' => Pages\CreateAffliateBonus::route('/create'),
            'edit' => Pages\EditAffliateBonus::route('/{record}/edit'),
        ];
    }
}
