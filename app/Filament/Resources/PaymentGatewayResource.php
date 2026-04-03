<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentGatewayResource\Pages;
use App\Models\PaymentGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentGatewayResource extends Resource
{
    protected static ?string $model = PaymentGateway::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Payment Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('title')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\Textarea::make('details')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(100)
                    ->default(null),
                Forms\Components\TextInput::make('type'),
                Forms\Components\Textarea::make('information')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('keyword')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('currency_id')
                    ->required()
                    ->maxLength(191)
                    ->default('*'),
                Forms\Components\TextInput::make('checkout')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('deposit')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('subscription')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('keyword')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('checkout')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deposit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListPaymentGateways::route('/'),
            'create' => Pages\CreatePaymentGateway::route('/create'),
            'edit' => Pages\EditPaymentGateway::route('/{record}/edit'),
        ];
    }
}
