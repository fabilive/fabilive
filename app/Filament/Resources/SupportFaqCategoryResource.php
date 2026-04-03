<?php

namespace App\Filament\Resources;

use App\Models\SupportFaqCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportFaqCategoryResource extends Resource
{
    protected static ?string $model = SupportFaqCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Support System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('context')
                    ->options([
                        'buyer' => 'Buyer',
                        'vendor' => 'Vendor',
                        'both' => 'Both',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('context'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => SupportFaqCategoryResource\Pages\ListRecords::route('/'),
            'create' => SupportFaqCategoryResource\Pages\CreateRecord::route('/create'),
            'edit' => SupportFaqCategoryResource\Pages\EditRecord::route('/{record}/edit'),
        ];
    }
}
