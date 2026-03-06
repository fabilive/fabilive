<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoogleAnalyticsResource\Pages;
use App\Models\Seotool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoogleAnalyticsResource extends Resource
{
    protected static ?string $model = Seotool::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'SEO Tools';

    protected static ?string $navigationLabel = 'Google Analytics';

    protected static ?string $slug = 'google-analytics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('google_analytics')
                    ->label('Google Analytics Code')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('facebook_pixel')
                    ->label('Facebook Pixel Code')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('google_analytics')
                    ->limit(50),
                Tables\Columns\TextColumn::make('facebook_pixel')
                    ->limit(50),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoogleAnalytics::route('/'),
            'create' => Pages\CreateGoogleAnalytics::route('/create'),
            'edit' => Pages\EditGoogleAnalytics::route('/{record}/edit'),
        ];
    }
}
