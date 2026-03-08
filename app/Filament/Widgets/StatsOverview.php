<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Rider;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Total Orders', Order::count())
                ->description('Total orders processed')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            Stat::make('Active Riders', Rider::where('status', 1)->count())
                ->description('Verified delivery partners')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),
            Stat::make('Revenue (Est.)', 'XAF ' . number_format(Order::where('payment_status', 'Completed')->sum('pay_amount'), 0))
                ->description('Total completed payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
