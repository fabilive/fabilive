<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawResource\Pages;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\WalletLedger;
use App\Traits\AuditsAdminActions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class WithdrawResource extends Resource
{
    use AuditsAdminActions;

    protected static ?string $model = Withdraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Financials';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Withdrawal Request Information')
                    ->schema([
                        Forms\Components\Placeholder::make('user_name')
                            ->label('User')
                            ->content(fn (Withdraw $record) => $record->user->name ?? $record->rider->name ?? 'Unknown'),
                        Forms\Components\Placeholder::make('method')
                            ->content(fn (Withdraw $record) => strtoupper($record->method)),
                        Forms\Components\Placeholder::make('amount')
                            ->content(fn (Withdraw $record) => number_format($record->amount, 2)),
                        Forms\Components\Placeholder::make('fee')
                            ->content(fn (Withdraw $record) => number_format($record->fee, 2)),
                        Forms\Components\Placeholder::make('status')
                            ->content(fn (Withdraw $record) => strtoupper($record->status)),
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn (Withdraw $record) => $record->created_at?->toDateTimeString()),
                    ])->columns(3),

                Forms\Components\Section::make('Payment Destination')
                    ->schema([
                        Forms\Components\TextInput::make('acc_name')->label('Account Name')->readOnly(),
                        Forms\Components\TextInput::make('acc_email')->label('Account Email/ID')->readOnly(),
                        Forms\Components\TextInput::make('iban')->label('IBAN/Phone')->readOnly(),
                        Forms\Components\TextInput::make('swift')->label('SWIFT/Network')->readOnly(),
                        Forms\Components\TextInput::make('country')->readOnly(),
                        Forms\Components\Textarea::make('address')->readOnly(),
                        Forms\Components\Textarea::make('reference')->readOnly(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('Unknown')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Withdraw $record) => $record->status === 'pending')
                    ->action(function (Withdraw $record) {
                        DB::transaction(function () use ($record) {
                            $withdraw = Withdraw::lockForUpdate()->find($record->id);
                            if ($withdraw->status !== 'pending') return;

                            $withdraw->status = 'completed';
                            $withdraw->save();

                            (new class { use AuditsAdminActions; })->auditAdminAction('Approve Withdrawal', $withdraw, [
                                'amount' => $withdraw->amount,
                                'method' => $withdraw->method
                            ]);
                        });

                        Notification::make()->success()->title('Withdrawal Approved')->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Withdrawal Request')
                    ->modalDescription('This will return the funds (amount + fee) back to the user\'s wallet. This action is irreversible.')
                    ->visible(fn (Withdraw $record) => $record->status === 'pending')
                    ->action(function (Withdraw $record) {
                        try {
                            DB::transaction(function () use ($record) {
                                $withdraw = Withdraw::lockForUpdate()->find($record->id);
                                if ($withdraw->status !== 'pending') return;

                                $user = User::lockForUpdate()->find($withdraw->user_id);
                                if (!$user) throw new \Exception("User not found for ID: {$withdraw->user_id}");

                                // Logic from legacy controller reversal
                                $refundAmount = $withdraw->amount + $withdraw->fee;
                                $user->balance += $refundAmount;
                                $user->save();

                                $withdraw->status = 'rejected';
                                $withdraw->save();

                                // Log reversal in ledger
                                WalletLedger::create([
                                    'user_id' => $user->id,
                                    'amount' => $refundAmount,
                                    'type' => 'withdrawal_reversal',
                                    'reference' => 'WDR-' . $withdraw->id,
                                    'status' => 'completed',
                                    'details' => 'Withdrawal request rejected by admin. Funds returned to wallet.'
                                ]);

                                (new class { use AuditsAdminActions; })->auditAdminAction('Reject Withdrawal', $withdraw, [
                                    'refunded_amount' => $refundAmount,
                                    'reason' => 'Admin manual rejection'
                                ]);
                            });

                            Notification::make()->warning()->title('Withdrawal Rejected')->body('Funds returned to user wallet.')->send();
                        } catch (\Exception $e) {
                            Notification::make()->danger()->title('Rejection Failed')->body($e->getMessage())->send();
                        }
                    }),

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
            'index' => Pages\ManageWithdraws::route('/'),
        ];
    }
}
