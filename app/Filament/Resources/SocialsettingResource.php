<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialsettingResource\Pages;
use App\Filament\Resources\SocialsettingResource\RelationManagers;
use App\Models\Socialsetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialsettingResource extends Resource
{
    protected static ?string $model = Socialsetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Social Settings';

    protected static ?string $navigationLabel = 'Login Providers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('facebook')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('gplus')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('twitter')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('linkedin')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('dribble')
                    ->maxLength(191)
                    ->default(null),
                Forms\Components\TextInput::make('f_status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('g_status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('t_status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('l_status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('d_status')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('f_check')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('g_check')
                    ->numeric()
                    ->default(null),
                Forms\Components\Textarea::make('fclient_id')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('fclient_secret')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('fredirect')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('gclient_id')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('gclient_secret')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('gredirect')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('facebook')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gplus')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('linkedin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dribble')
                    ->searchable(),
                Tables\Columns\TextColumn::make('f_status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('g_status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('t_status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('l_status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('d_status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('f_check')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('g_check')
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
            'index' => Pages\ListSocialsettings::route('/'),
            'create' => Pages\CreateSocialsetting::route('/create'),
            'edit' => Pages\EditSocialsetting::route('/{record}/edit'),
        ];
    }
}
