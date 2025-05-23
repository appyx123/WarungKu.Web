<?php

namespace App\Filament\Customer\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CustomerStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        try {
            $customerId = auth()->guard('customer')->id();
            if (!$customerId) {
                Log::warning('CustomerStatsOverviewWidget: Customer not authenticated');
                return [];
            }

            // Jumlah transaksi selesai (checked_out)
            $transactionCount = Transaction::where('customer_id', $customerId)
                ->where('status', 'checked_out')
                ->count();

            // Total pengeluaran dari transaksi selesai
            $totalExpenditure = Transaction::where('customer_id', $customerId)
                ->where('status', 'checked_out')
                ->sum('total_price');

            // Jumlah jenis produk unik yang dibeli
            $productCount = TransactionItem::whereIn('transaction_id', function ($query) use ($customerId) {
                $query->select('id')
                    ->from('transactions')
                    ->where('customer_id', $customerId)
                    ->where('status', 'checked_out');
            })
            ->distinct('product_id')
            ->count('product_id');

            // Jumlah produk tersedia untuk dibeli (stock > 0)
            $availableProducts = Product::where('stock', '>', 0)
                ->count();

            // Tanggal transaksi selesai terakhir
            $lastTransaction = Transaction::where('customer_id', $customerId)
                ->where('status', 'checked_out')
                ->latest('created_at')
                ->first();

            $lastTransactionDate = $lastTransaction ? $lastTransaction->created_at->format('d/m/Y H:i') : 'Belum ada transaksi';

            return [
                Stat::make('Jumlah Transaksi Selesai', $transactionCount)
                    ->description('Total transaksi yang selesai')
                    ->color('success')
                    ->icon('heroicon-o-shopping-cart'),

                Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpenditure, 0, ',', '.'))
                    ->description('Total pembelanjaan hingga ' . now()->format('H:i'))
                    ->color('primary')
                    ->icon('heroicon-o-currency-dollar'),

                Stat::make('Jumlah Jenis Produk Dibeli', $productCount)
                    ->description('Total jenis produk yang dibeli')
                    ->color('info')
                    ->icon('heroicon-o-cube'),

                Stat::make('Produk Tersedia', $availableProducts)
                    ->description('Produk yang bisa dibeli saat ini')
                    ->color('warning')
                    ->icon('heroicon-o-archive-box'),

                Stat::make('Transaksi Selesai Terakhir', $lastTransactionDate)
                    ->description('Tanggal transaksi terakhir')
                    ->color('secondary')
                    ->icon('heroicon-o-clock'),
            ];
        } catch (\Exception $e) {
            Log::error('CustomerStatsOverviewWidget Error: ' . $e->getMessage());
            return [];
        }
    }
}
