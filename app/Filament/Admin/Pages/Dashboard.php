<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\SalesChartWidget;
use App\Filament\Admin\Widgets\TotalProductsWidget;
use App\Filament\Admin\Widgets\TotalCustomersWidget;
use App\Filament\Admin\Widgets\LowStockProductsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            // TotalProductsWidget::class,
            // TotalCustomersWidget::class,
            // LowStockProductsWidget::class,
            // SalesChartWidget::class,
        ];
    }
    
}
