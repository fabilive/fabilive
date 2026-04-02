<?php

namespace App\Filament\Resources;

use App\Models\SupportFaq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportFaqResource extends Resource
{
    protected static ?string $model = SupportFaq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Support System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->nullable(),
                Forms\Components\Select::make('context')
                    ->options([
                        'buyer' => 'Buyer',
                        'vendor' => 'Vendor',
                        'both' => 'Both',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('answer_html')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TagsInput::make('keywords'),
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
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('context'),
                Tables\Columns\TextColumn::make('question')->limit(50)->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->actions([ Tables\Actions\EditAction::make(), ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Filament\Resources\Pages\ListRecords::route('/'),
            'create' => \Filament\Resources\Pages\CreateRecord::route('/create'),
            'edit' => \Filament\Resources\Pages\EditRecord::route('/{record}/edit'),
        ];
    }
}
