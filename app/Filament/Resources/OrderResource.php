<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Highlights')
                    ->schema([
                        Forms\Components\Placeholder::make('order_number')
                            ->content(fn (Order $record) => $record->order_number),
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn (Order $record) => $record->created_at?->toDateTimeString()),
                        Forms\Components\Placeholder::make('pay_amount')
                            ->content(fn (Order $record) => $record->currency_sign . number_format($record->pay_amount, 2)),
                        Forms\Components\Placeholder::make('status')
                            ->content(fn (Order $record) => strtoupper($record->status)),
                    ])->columns(4),

                Forms\Components\Section::make('Customer Details')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')->readOnly(),
                        Forms\Components\TextInput::make('customer_email')->readOnly(),
                        Forms\Components\TextInput::make('customer_phone')->readOnly(),
                        Forms\Components\Textarea::make('customer_address')->readOnly(),
                    ])->columns(2),

                Forms\Components\Section::make('Financial Status')
                    ->schema([
                        Forms\Components\TextInput::make('payment_status')->readOnly(),
                        Forms\Components\TextInput::make('escrow_status')->readOnly(),
                        Forms\Components\DateTimePicker::make('payout_released_at')->readOnly(),
                        Forms\Components\Toggle::make('admin_verified')->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Cart Items')
                    ->schema([
                        Forms\Components\Placeholder::make('cart_items')
                            ->content(function (Order $record) {
                                $cart = json_decode($record->cart, true);
                                if (!$cart || !isset($cart['items'])) return 'Empty Cart';
                                
                                $output = "";
                                foreach ($cart['items'] as $item) {
                                    $name = $item['item']['name'] ?? 'Unknown Item';
                                    $qty = $item['qty'] ?? 1;
                                    $price = $item['price'] ?? 0;
                                    $output .= "- {$name} (x{$qty}) - " . number_format($price, 2) . "\n";
                                }
                                return new \Illuminate\Support\HtmlString("<pre>" . e($output) . "</pre>");
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pay_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, Order $record) => $record->currency_sign . number_format($state, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'info',
                        'on delivery' => 'warning',
                        'completed' => 'success',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'pending' => 'gray',
                        'completed' => 'success',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                    ]),
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
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
