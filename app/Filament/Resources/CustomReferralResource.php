<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomReferralResource\Pages;
use App\Models\CustomReferral;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomReferralResource extends Resource
{
    protected static ?string $model = CustomReferral::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Referrals';

    protected static ?string $navigationLabel = 'Custom Referrals';

    protected static ?string $modelLabel = 'Referral';

    protected static ?string $pluralModelLabel = 'Referrals';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Referral Details')
                    ->schema([
                        Forms\Components\Select::make('referrer_id')
                            ->label('Referrer')
                            ->relationship('referrer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('referred_id')
                            ->label('Referred User')
                            ->relationship('referred', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->default(500)
                            ->required()
                            ->prefix('CFA'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'locked' => 'Locked',
                                'unlocked' => 'Unlocked',
                                'expired' => 'Expired',
                            ])
                            ->default('locked')
                            ->required(),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('referrer.name')
                    ->label('Referrer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referrer.email')
                    ->label('Referrer Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('referred.name')
                    ->label('Referred User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referred.email')
                    ->label('Referred Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Bonus Amount')
                    ->numeric(2)
                    ->prefix('CFA ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'locked' => 'warning',
                        'unlocked' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'locked' => 'Locked',
                        'unlocked' => 'Unlocked',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('unlock')
                    ->label('Unlock')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (CustomReferral $record): bool => $record->status === 'locked')
                    ->action(function (CustomReferral $record) {
                        $record->update(['status' => 'unlocked']);

                        // Credit the referrer's wallet
                        $referrer = $record->referrer;
                        if ($referrer) {
                            $referrer->current_balance = ($referrer->current_balance ?? 0) + $record->amount;
                            $referrer->save();

                            \App\Models\WalletLedger::create([
                                'user_id' => $referrer->id,
                                'amount' => $record->amount,
                                'type' => 'referral_bonus',
                                'status' => 'completed',
                                'reference' => 'CREF-'.$record->id,
                                'details' => "Custom referral bonus unlocked for referring user #{$record->referred_id}",
                            ]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('bulk_unlock')
                        ->label('Unlock Selected')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                if ($record->status === 'locked') {
                                    $record->update(['status' => 'unlocked']);

                                    $referrer = $record->referrer;
                                    if ($referrer) {
                                        $referrer->current_balance = ($referrer->current_balance ?? 0) + $record->amount;
                                        $referrer->save();

                                        \App\Models\WalletLedger::create([
                                            'user_id' => $referrer->id,
                                            'amount' => $record->amount,
                                            'type' => 'referral_bonus',
                                            'status' => 'completed',
                                            'reference' => 'CREF-'.$record->id,
                                            'details' => "Custom referral bonus unlocked for referring user #{$record->referred_id}",
                                        ]);
                                    }
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCustomReferrals::route('/'),
            'create' => Pages\CreateCustomReferral::route('/create'),
            'edit' => Pages\EditCustomReferral::route('/{record}/edit'),
        ];
    }
}
