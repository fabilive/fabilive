<?php

namespace App\Filament\Resources;

use App\Models\SupportBotRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportBotRuleResource extends Resource
{
    protected static ?string $model = SupportBotRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

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
                Forms\Components\Select::make('pattern_type')
                    ->options([
                        'keyword' => 'Keyword',
                        'contains' => 'Contains (Substring)',
                        'regex' => 'Regex',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('pattern_value')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g. refund, money back'),
                Forms\Components\Textarea::make('response_text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('suggested_faq_id')
                    ->relationship('suggestedFaq', 'question')
                    ->nullable(),
                Forms\Components\TextInput::make('priority')
                    ->numeric()
                    ->default(0)
                    ->helperText('Higher priority triggers first'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('context'),
                Tables\Columns\TextColumn::make('pattern_type'),
                Tables\Columns\TextColumn::make('pattern_value')->searchable(),
                Tables\Columns\TextColumn::make('priority')->sortable(),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => SupportBotRuleResource\Pages\ListRecords::route('/'),
            'create' => SupportBotRuleResource\Pages\CreateRecord::route('/create'),
            'edit' => SupportBotRuleResource\Pages\EditRecord::route('/{record}/edit'),
        ];
    }
}
