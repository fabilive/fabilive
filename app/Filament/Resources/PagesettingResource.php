<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagesettingResource\Pages;
use App\Models\Pagesetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PagesettingResource extends Resource
{
    protected static ?string $model = Pagesetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Home Page Settings';

    protected static ?string $navigationLabel = 'Home Page Customization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('contact_email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('street')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('phone')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('fax')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('email')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('site')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('best_seller_banner')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('best_seller_banner_link')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('big_save_banner')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('big_save_banner_link')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('best_seller_banner1')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('best_seller_banner_link1')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('big_save_banner1')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('big_save_banner_link1')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('big_save_banner_subtitle')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('big_save_banner_title')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('big_save_banner_text')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rightbanner1')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rightbanner2')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rightbannerlink1')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rightbannerlink2')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('home')
                    ->required(),
                Forms\Components\Toggle::make('blog')
                    ->required(),
                Forms\Components\Toggle::make('faq')
                    ->required(),
                Forms\Components\Toggle::make('contact')
                    ->required(),
                Forms\Components\Toggle::make('category')
                    ->required(),
                Forms\Components\Toggle::make('arrival_section')
                    ->required(),
                Forms\Components\Toggle::make('our_services')
                    ->required(),
                Forms\Components\Toggle::make('slider')
                    ->required(),
                Forms\Components\Toggle::make('partner')
                    ->required(),
                Forms\Components\Toggle::make('top_big_trending')
                    ->required(),
                Forms\Components\TextInput::make('top_banner')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('large_banner')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('bottom_banner')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('best_selling')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('newsletter')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('deal_of_the_day')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('best_sellers')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('third_left_banner')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('popular_products')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('flash_deal')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('top_brand')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('big_save_banner_subtitle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('big_save_banner_title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('home')
                    ->boolean(),
                Tables\Columns\IconColumn::make('blog')
                    ->boolean(),
                Tables\Columns\IconColumn::make('faq')
                    ->boolean(),
                Tables\Columns\IconColumn::make('contact')
                    ->boolean(),
                Tables\Columns\IconColumn::make('category')
                    ->boolean(),
                Tables\Columns\IconColumn::make('arrival_section')
                    ->boolean(),
                Tables\Columns\IconColumn::make('our_services')
                    ->boolean(),
                Tables\Columns\IconColumn::make('slider')
                    ->boolean(),
                Tables\Columns\IconColumn::make('partner')
                    ->boolean(),
                Tables\Columns\IconColumn::make('top_big_trending')
                    ->boolean(),
                Tables\Columns\TextColumn::make('top_banner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('large_banner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bottom_banner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('best_selling')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('newsletter')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deal_of_the_day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('best_sellers')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('third_left_banner')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('popular_products')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flash_deal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('top_brand')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListPagesettings::route('/'),
            'create' => Pages\CreatePagesetting::route('/create'),
            'edit' => Pages\EditPagesetting::route('/{record}/edit'),
        ];
    }
}
