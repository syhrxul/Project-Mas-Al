<?php

namespace App\Filament\Resources\Admin\Widgets;

use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $activeRentals = Rental::where('status', 'confirmed')->count();
        $pendingRentals = Rental::where('status', 'waiting')->count();
        $totalRentals = Rental::count();

        return [
            Stat::make('Active Rentals', $activeRentals)
                ->description('Currently active rentals')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            
            Stat::make('Pending Rentals', $pendingRentals)
                ->description('Waiting for approval')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            
            Stat::make('Total Rentals', $totalRentals)
                ->description('All time rentals')
                ->color('info')
                ->icon('heroicon-o-document-text'),
        ];
    }
}