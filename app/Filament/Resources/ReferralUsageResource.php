<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralUsageResource\Pages;
use App\Models\ReferralUsage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReferralUsageResource extends Resource
{
    protected static ?string $model = ReferralUsage::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Growth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Referral Details')
                    ->schema([
                        Forms\Components\Placeholder::make('referral_code')
                            ->content(fn (ReferralUsage $record) => $record->referralCode->code ?? 'N/A'),
                        Forms\Components\Placeholder::make('referred_user')
                            ->label('Referred Entity')
                            ->content(fn (ReferralUsage $record) => $record->referredUser->name ?? $record->referredRider->name ?? 'Unknown'),
                        Forms\Components\Placeholder::make('role')
                            ->content(fn (ReferralUsage $record) => strtoupper($record->referred_role)),
                        Forms\Components\Placeholder::make('status')
                            ->content(fn (ReferralUsage $record) => strtoupper($record->status)),
                    ])->columns(2),

                Forms\Components\Section::make('Bonuses')
                    ->schema([
                        Forms\Components\TextInput::make('referrer_bonus')
                            ->numeric()
                            ->prefix('XAF')
                            ->readOnly(),
                        Forms\Components\TextInput::make('referred_bonus')
                            ->numeric()
                            ->prefix('XAF')
                            ->readOnly(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referralCode.code')
                    ->label('Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('referred_role')
                    ->badge()
                    ->label('Role'),
                Tables\Columns\TextColumn::make('referrer_bonus')
                    ->label('Referrer Payout')
                    ->money('XAF'),
                Tables\Columns\TextColumn::make('referred_bonus')
                    ->label('Referred Payout')
                    ->money('XAF'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('referred_role')
                    ->options([
                        'user' => 'User',
                        'rider' => 'Rider',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReferralUsages::route('/'),
        ];
    }
}
