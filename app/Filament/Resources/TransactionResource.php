<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Financials';

    protected static ?string $navigationLabel = 'Tax Calculate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('reward_point')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('reward_dolar')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('txn_number')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('currency_sign'),
                Forms\Components\TextInput::make('currency_code')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('currency_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('method')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('txnid')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('details')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward_point')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward_dolar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency_sign'),
                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('txnid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
