<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiderResource\Pages;
use App\Models\Rider;
use App\Traits\AuditsAdminActions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class RiderResource extends Resource
{
    use AuditsAdminActions;

    protected static ?string $model = Rider::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Stakeholders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rider Profile')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                        Forms\Components\TextInput::make('phone')->tel()->required(),
                        Forms\Components\TextInput::make('balance')->numeric()->prefix('XAF')->readOnly(),
                        Forms\Components\Select::make('rider_type')
                            ->options([
                                'individual' => 'Individual',
                                'company' => 'Company',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Onboarding Documents')
                    ->schema([
                        Forms\Components\FileUpload::make('national_id_front_image')->label('National ID Front')->image(),
                        Forms\Components\FileUpload::make('national_id_back_image')->label('National ID Back')->image(),
                        Forms\Components\FileUpload::make('license_image')->label('Driver License')->image(),
                        Forms\Components\FileUpload::make('live_selfie_individual')->label('Live Selfie')->image(),
                        Forms\Components\FileUpload::make('vehicle_registration_certificate')->label('Vehicle Reg')->image(),
                    ])->columns(2),

                Forms\Components\Section::make('Verification Status')
                    ->schema([
                        Forms\Components\Placeholder::make('onboarding_status')
                            ->content(fn (Rider $record) => strtoupper($record->onboarding_status ?? 'PENDING')),
                        Forms\Components\Placeholder::make('is_verified')
                            ->label('Verified')
                            ->content(fn (Rider $record) => $record->is_verified ? 'YES' : 'NO'),
                        Forms\Components\Placeholder::make('approved_at')
                            ->content(fn (Rider $record) => $record->approved_at?->toDateTimeString() ?? 'N/A'),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->readOnly()
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('onboarding_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('onboarding_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('verify_onboarding')
                    ->label('Verify Rider')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Rider $record) => in_array($record->onboarding_status, ['pending', 'pending_docs', 'pending_approval']))
                    ->action(function (Rider $record) {
                        $record->onboarding_status = 'approved';
                        $record->is_verified = 1;
                        $record->status = 1;
                        $record->approved_at = Carbon::now();
                        $record->save();

                        (new class
                        {
                            use AuditsAdminActions;
                        })->auditAdminAction('Verify Rider Onboarding', $record, [
                            'name' => $record->name,
                            'phone' => $record->phone,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Rider Verified')
                            ->body("{$record->name} has been successfully verified and activated.")
                            ->send();
                    }),

                Action::make('reject_onboarding')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->visible(fn (Rider $record) => $record->onboarding_status === 'pending')
                    ->action(function (Rider $record, array $data) {
                        $record->onboarding_status = 'rejected';
                        $record->rejection_reason = $data['reason'];
                        $record->is_verified = 0;
                        $record->save();

                        (new class
                        {
                            use AuditsAdminActions;
                        })->auditAdminAction('Reject Rider Onboarding', $record, [
                            'reason' => $data['reason'],
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('Rider Onboarding Rejected')
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRiders::route('/'),
        ];
    }
}
