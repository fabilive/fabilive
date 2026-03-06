<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Notification Context')
                    ->schema([
                        Forms\Components\Placeholder::make('user')
                            ->content(fn (Notification $record) => $record->user->name ?? 'N/A'),
                        Forms\Components\Placeholder::make('vendor')
                            ->content(fn (Notification $record) => $record->vendor->name ?? 'N/A'),
                        Forms\Components\Placeholder::make('order_number')
                            ->content(fn (Notification $record) => $record->order->order_number ?? 'N/A'),
                        Forms\Components\Placeholder::make('product')
                            ->content(fn (Notification $record) => $record->product->name ?? 'N/A'),
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn (Notification $record) => $record->created_at?->toDateTimeString()),
                        Forms\Components\Toggle::make('is_read')->disabled(),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->placeholder('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->placeholder('System')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read'),
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
            'index' => Pages\ManageNotifications::route('/'),
        ];
    }
}
