<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletLedgerResource\Pages;
use App\Models\WalletLedger;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WalletLedgerResource extends Resource
{
    protected static ?string $model = WalletLedger::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Financials';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\Placeholder::make('user_id')
                            ->label('User ID')
                            ->content(fn (WalletLedger $record) => $record->user_id),
                        Forms\Components\Placeholder::make('order_id')
                            ->label('Order ID')
                            ->content(fn (WalletLedger $record) => $record->order_id ?? 'N/A'),
                        Forms\Components\Placeholder::make('amount')
                            ->content(fn (WalletLedger $record) => number_format($record->amount, 2)),
                        Forms\Components\Placeholder::make('type')
                            ->content(fn (WalletLedger $record) => strtoupper($record->type)),
                        Forms\Components\Placeholder::make('reference')
                            ->content(fn (WalletLedger $record) => $record->reference ?? 'N/A'),
                        Forms\Components\Placeholder::make('status')
                            ->content(fn (WalletLedger $record) => strtoupper($record->status)),
                        Forms\Components\Placeholder::make('details')
                            ->content(fn (WalletLedger $record) => $record->details ?? 'N/A')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric(2)
                    ->sortable()
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'escrow_release' => 'success',
                        'deposit' => 'success',
                        'withdraw' => 'danger',
                        'refund' => 'warning',
                        'order_payment' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->placeholder('N/A')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'gray',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWalletLedgers::route('/'),
        ];
    }
}
