<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryJobResource\Pages;
use App\Filament\Resources\DeliveryJobResource\RelationManagers;
use App\Models\DeliveryJob;
use App\Services\DeliveryJobService;
use App\Traits\AuditsAdminActions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class DeliveryJobResource extends Resource
{
    use AuditsAdminActions;

    protected static ?string $model = DeliveryJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Logistics';

    protected static ?string $slug = 'delivery-jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Core Information')
                    ->schema([
                        Forms\Components\Placeholder::make('order_number')
                            ->content(fn (DeliveryJob $record): string => $record->order->order_number ?? 'N/A'),
                        Forms\Components\Placeholder::make('buyer_name')
                            ->content(fn (DeliveryJob $record): string => $record->buyer->name ?? 'N/A'),
                        Forms\Components\Placeholder::make('rider_name')
                            ->content(fn (DeliveryJob $record): string => $record->rider->name ?? 'Unassigned'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending_readiness' => 'Pending Readiness',
                                'assigned' => 'Assigned',
                                'picked_up' => 'Picked Up',
                                'delivered' => 'Delivered (Legacy)',
                                'delivered_pending_verification' => 'Pending Verification',
                                'delivered_verified' => 'Verified & Settled',
                                'cancelled' => 'Cancelled',
                                'returned' => 'Returned',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Financials')
                    ->schema([
                        Forms\Components\TextInput::make('delivery_fee_total')
                            ->numeric()
                            ->prefix('XAF')
                            ->disabled(),
                        Forms\Components\TextInput::make('rider_earnings')
                            ->numeric()
                            ->prefix('XAF')
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_delivery_commission')
                            ->numeric()
                            ->prefix('XAF')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Proof of Delivery')
                    ->schema([
                        Forms\Components\FileUpload::make('proof_photo')
                            ->image()
                            ->directory('delivery_proofs')
                            ->visibility('public'),
                        Forms\Components\DateTimePicker::make('proof_uploaded_at')
                            ->disabled(),
                        Forms\Components\Placeholder::make('delivered_at')
                            ->content(fn (DeliveryJob $record): string => $record->delivered_at?->toDateTimeString() ?? 'N/A'),
                        Forms\Components\Placeholder::make('verified_at')
                            ->content(fn (DeliveryJob $record): string => $record->verified_at?->toDateTimeString() ?? 'N/A'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Buyer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rider.name')
                    ->label('Rider')
                    ->placeholder('Unassigned')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_readiness' => 'gray',
                        'assigned' => 'info',
                        'picked_up' => 'warning',
                        'delivered_pending_verification' => 'danger',
                        'delivered_verified' => 'success',
                        'cancelled', 'returned' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('delivery_fee_total')
                    ->label('Fee')
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'delivered_pending_verification' => 'Pending Verification',
                        'picked_up' => 'Picked Up',
                        'assigned' => 'Assigned',
                    ]),
            ])
            ->actions([
                Action::make('verify')
                    ->label('Verify Delivery')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verify Delivery and Settle Payouts')
                    ->modalDescription('This will mark the delivery as verified, release the rider\'s earnings to their balance, and release the seller\'s escrow. This action is irreversible.')
                    ->visible(fn (DeliveryJob $record): bool => $record->status === 'delivered_pending_verification')
                    ->action(function (DeliveryJob $record) {
                        try {
                            app(DeliveryJobService::class)->verifyAndSettle($record);

                            // Audit - Using static call to avoid trait instantiation issues in anon function if any
                            (new class
                            {
                                use AuditsAdminActions;
                            })->auditAdminAction('Verify Delivery', $record, [
                                'order_number' => $record->order->order_number,
                                'rider_id' => $record->assigned_rider_id,
                                'amount' => $record->delivery_fee_total,
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Delivery Verified Successfully')
                                ->body('Payouts have been settled for the rider and seller.')
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Verification Failed')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\StopsRelationManager::class,
            RelationManagers\EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDeliveryJobs::route('/'),
        ];
    }
}
