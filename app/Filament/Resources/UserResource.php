<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Traits\AuditsAdminActions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    use AuditsAdminActions;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Stakeholders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                        Forms\Components\TextInput::make('phone')->tel(),
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->directory('users'),
                    ])->columns(2),

                Forms\Components\Section::make('Account Status & Financials')
                    ->schema([
                        Forms\Components\Toggle::make('ban')
                            ->label('Banned')
                            ->disabled() // Managed via action
                            ->helperText('Use the table actions to ban/unban'),
                        Forms\Components\Toggle::make('is_verified')
                            ->label('Email Verified'),
                        Forms\Components\TextInput::make('balance')
                            ->numeric()
                            ->prefix('XAF'),
                        Forms\Components\TextInput::make('affilate_income')
                            ->numeric()
                            ->prefix('XAF'),
                    ])->columns(2),

                Forms\Components\Section::make('Vendor Information')
                    ->schema([
                        Forms\Components\Toggle::make('is_vendor')->label('Is Vendor'),
                        Forms\Components\TextInput::make('shop_name'),
                        Forms\Components\TextInput::make('owner_name'),
                        Forms\Components\TextInput::make('shop_number'),
                        Forms\Components\Textarea::make('shop_address')->columnSpanFull(),
                        Forms\Components\FileUpload::make('shop_image')->image(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_vendor')
                    ->boolean()
                    ->label('Vendor'),
                Tables\Columns\TextColumn::make('ban')
                    ->badge()
                    ->label('Account Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Banned' : 'Active')
                    ->color(fn ($state) => $state ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_vendor'),
                Tables\Filters\TernaryFilter::make('ban')->label('Banned'),
            ])
            ->actions([
                Action::make('toggle_ban')
                    ->label(fn (User $record) => $record->ban ? 'Unban' : 'Ban')
                    ->icon(fn (User $record) => $record->ban ? 'heroicon-o-check-circle' : 'heroicon-o-no-symbol')
                    ->color(fn (User $record) => $record->ban ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->ban = !$record->ban;
                        $record->save();

                        (new class { use AuditsAdminActions; })->auditAdminAction($record->ban ? 'Ban User' : 'Unban User', $record, [
                            'email' => $record->email
                        ]);

                        Notification::make()
                            ->success()
                            ->title($record->ban ? 'User Banned' : 'User Unbanned')
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
