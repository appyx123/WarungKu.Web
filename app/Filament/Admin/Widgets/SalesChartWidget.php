<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Harian';
    protected static string $color = 'primary';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Perluas rentang waktu untuk memastikan ada data
        $data = Trend::model(Transaction::class)
            ->between(
                start: now()->subDays(7), // Ambil data 7 hari terakhir
                end: now()->endOfDay(),
            )
            ->perDay()
            ->sum('total_price');

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#552834',
                    'borderColor' => '#552834',
                    'fill' => false, // Tidak mengisi area di bawah garis
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => (new \DateTime($value->date))->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Menentukan opsi Chart.js untuk meningkatkan tampilan
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Penjualan (Rp)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Tanggal',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }
}
