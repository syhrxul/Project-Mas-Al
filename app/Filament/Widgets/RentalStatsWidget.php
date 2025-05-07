<?php

namespace App\Filament\Widgets;

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
            Stat::make('Penyewaan Aktif', $activeRentals)
                ->description('Sedang dalam masa sewa')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            
            Stat::make('Menunggu Persetujuan', $pendingRentals)
                ->description('Perlu ditinjau')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            
            Stat::make('Penyewaan Selesai', $completedRentals)
                ->description('Telah selesai')
                ->color('info')
                ->icon('heroicon-o-document-check'),
        ];
    }
}