<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Access Control';

    public static function form(Form $form): Form
    {
        $permissions = [
            'Orders' => 'Orders',
            'Categories' => 'Categories',
            'Products' => 'Products',
            'Affiliate Products' => 'Affiliate Products',
            'Bulk Product Upload' => 'Bulk Product Upload',
            'Product Discussion' => 'Product Discussion',
            'Set Coupons' => 'Set Coupons',
            'Customers' => 'Customers',
            'Customer Deposits' => 'Customer Deposits',
            'Vendors' => 'Vendors',
            'Vendor Verifications' => 'Vendor Verifications',
            'Vendor Subscriptions' => 'Vendor Subscriptions',
            'Vendor Subscription Plans' => 'Vendor Subscription Plans',
            'Messages' => 'Messages',
            'Earning' => 'Earning',
            'Payment Settings' => 'Payment Settings',
            'Language Settings' => 'Language Settings',
            'Seo Tools' => 'Seo Tools',
            'Manage Staffs' => 'Manage Staffs',
            'Subscribers' => 'Subscribers',
        ];

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\CheckboxList::make('section')
                    ->options($permissions)
                    ->columns(2)
                    ->gridDirection('vertical')
                    ->afterStateHydrated(fn ($component, $state) => $component->state(explode(' , ', $state ?? '')))
                    ->dehydrateStateUsing(fn ($state) => implode(' , ', $state ?? []))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('section')
                    ->label('Permissions')
                    ->badge()
                    ->separator(' , ')
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
