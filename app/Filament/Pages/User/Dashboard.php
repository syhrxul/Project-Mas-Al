<?php

namespace App\Filament\Pages\User;

use App\Livewire\DashboardAnnouncement;
use App\Livewire\LatestProductsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardAnnouncement::class,
            LatestProductsWidget::class,
        ];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getHeading(): string
    {
        return '';
    }
}