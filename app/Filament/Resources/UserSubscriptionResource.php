<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSubscriptionResource\Pages;
use App\Models\UserSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserSubscriptionResource extends Resource
{
    protected static ?string $model = UserSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financials';

    protected static ?string $navigationLabel = 'Subscription Earning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subscription_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('currency_sign')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('currency_code')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('currency_value')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Forms\Components\TextInput::make('days')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('allowed_products')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('details')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(50)
                    ->default('Free'),
                Forms\Components\TextInput::make('txnid')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('charge_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('flutter_id')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('payment_number')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency_sign')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allowed_products')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('txnid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('charge_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListUserSubscriptions::route('/'),
            'create' => Pages\CreateUserSubscription::route('/create'),
            'edit' => Pages\EditUserSubscription::route('/{record}/edit'),
        ];
    }
}
