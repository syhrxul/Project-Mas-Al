<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RentalStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $activeRentals = Rental::where('status', 'confirmed')->count();
        $pendingRentals = Rental::where('status', 'waiting')->count();
        $completedRentals = Rental::where('status', 'completed')->count();

        return [
            Stat::make('Status Penyewaan', "Aktif: $activeRentals | Menunggu: $pendingRentals | Selesai: $completedRentals")
                ->description('Ringkasan status semua penyewaan')
                ->color('gray')
                ->icon('heroicon-o-chart-bar')
        ];
    }
}