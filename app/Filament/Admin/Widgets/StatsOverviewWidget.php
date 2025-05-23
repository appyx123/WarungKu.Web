<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        try {
            $todaySales = Transaction::whereDate('created_at', now()->toDateString())->sum('total_price');
            $lowStockCount = Product::where('stock', '<', 5)->count();
            $totalProducts = Product::count();
            $totalCustomers = Customer::count();
            $currentTime = now()->format('H:i');

            // Total transaksi selesai (checked_out)
            $completedTransactions = Transaction::where('status', 'checked_out')->count();

            // Total pendapatan dari transaksi selesai
            $totalRevenue = Transaction::where('status', 'checked_out')->sum('total_price');

            // Customer aktif (memiliki setidaknya satu transaksi selesai)
            $activeCustomers = Customer::whereHas('transactions', function ($query) {
                $query->where('status', 'checked_out');
            })->count();

            return [
                Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($todaySales, 0, ',', '.'))
                    ->description('Total penjualan hingga ' . $currentTime)
                    ->color('success')
                    ->icon('heroicon-o-currency-dollar'),

                Stat::make('Produk Low Stock', $lowStockCount)
                    ->description($lowStockCount > 0 ? 'Periksa Low Stock Page' : 'Semua stok aman')
                    ->color($lowStockCount > 0 ? 'danger' : 'success')
                    ->icon($lowStockCount > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-badge')
                    ->url($lowStockCount > 0 ? '/admin/low-stock' : ''),

                Stat::make('Total Produk', number_format($totalProducts, 0, ',', '.'))
                    ->description('Total produk yang tersedia')
                    ->color('primary')
                    ->icon('heroicon-o-cube'),

                Stat::make('Total Customer', number_format($totalCustomers, 0, ',', '.'))
                    ->description('Total customer terdaftar')
                    ->color('primary')
                    ->icon('heroicon-o-users'),

                Stat::make('Total Transaksi Selesai', $completedTransactions)
                    ->description('Total transaksi yang selesai')
                    ->color('info')
                    ->icon('heroicon-o-shopping-cart'),

                Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                    ->description('Total pendapatan dari transaksi selesai')
                    ->color('warning')
                    ->icon('heroicon-o-banknotes'),

                Stat::make('Customer Aktif', $activeCustomers)
                    ->description('Customer dengan transaksi selesai')
                    ->color('secondary')
                    ->icon('heroicon-o-user-group'),
            ];
        } catch (\Exception $e) {
            Log::error('StatsOverviewWidget Error: ' . $e->getMessage());
            return [];
        }
    }
}
