<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Products Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('product_type')
                    ->required(),
                Forms\Components\Textarea::make('affiliate_link')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('subcategory_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('childcategory_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Textarea::make('attributes')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('slug')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('photo')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('thumbnail')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('file')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('size')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('size_qty')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('size_price')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\Textarea::make('color')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('previous_price')
                    ->numeric()
                    ->default(null),
                Forms\Components\Textarea::make('details')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stock')
                    ->numeric()
                    ->default(null),
                Forms\Components\Textarea::make('policy')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('views')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tags')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\Textarea::make('features')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('colors')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('product_condition')
                    ->required(),
                Forms\Components\TextInput::make('ship')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\Toggle::make('is_meta')
                    ->required(),
                Forms\Components\Textarea::make('meta_tag')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('youtube')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\Textarea::make('license')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('license_qty')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('link')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('platform')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('region')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('licence_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('measure')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('featured')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('best')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('top')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('hot')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('latest')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('big')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('trending')
                    ->required(),
                Forms\Components\Toggle::make('sale')
                    ->required(),
                Forms\Components\Toggle::make('is_discount')
                    ->required(),
                Forms\Components\DatePicker::make('discount_date'),
                Forms\Components\Textarea::make('whole_sell_qty')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('whole_sell_discount')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_catalog')
                    ->required(),
                Forms\Components\TextInput::make('catalog_id')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('preordered')
                    ->required(),
                Forms\Components\TextInput::make('minimum_qty')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\Textarea::make('color_all')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('size_all')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stock_check')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('cross_products')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_type'),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subcategory_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('childcategory_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('thumbnail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size_qty')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size_price')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('previous_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags')
                    ->searchable(),
                Tables\Columns\IconColumn::make('product_condition')
                    ->boolean(),
                Tables\Columns\TextColumn::make('ship')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_meta')
                    ->boolean(),
                Tables\Columns\TextColumn::make('youtube')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('platform')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                    ->searchable(),
                Tables\Columns\TextColumn::make('licence_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('measure')
                    ->searchable(),
                Tables\Columns\TextColumn::make('featured')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('best')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('top')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latest')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('big')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('trending')
                    ->boolean(),
                Tables\Columns\IconColumn::make('sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_discount')
                    ->boolean(),
                Tables\Columns\TextColumn::make('discount_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_catalog')
                    ->boolean(),
                Tables\Columns\TextColumn::make('catalog_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('preordered')
                    ->boolean(),
                Tables\Columns\TextColumn::make('minimum_qty')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_check')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cross_products')
                    ->searchable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
