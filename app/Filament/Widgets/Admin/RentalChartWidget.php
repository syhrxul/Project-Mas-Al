<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RentalChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Penyewaan 7 Hari Terakhir';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            
            $rentals = Rental::where('status', 'confirmed')
                ->whereDate('created_at', $date)
                ->get();

            return [
                'date' => $date->format('d M'),
                'count' => $rentals->count(),
                'income' => $rentals->sum('total_price'),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penyewaan',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#36A2EB',
                    'tension' => 0.4,
                    'fill' => false,
                ],
                [
                    'label' => 'Pendapatan (dalam ribuan)',
                    'data' => $data->pluck('income')->map(fn ($amount) => round($amount/1000))->toArray(),
                    'borderColor' => '#4CAF50',
                    'tension' => 0.4,
                    'fill' => false,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}