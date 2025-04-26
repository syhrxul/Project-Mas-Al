<?php

namespace App\Livewire;

use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardAnnouncement extends Widget
{
    protected static string $view = 'livewire.dashboard-announcement';

    protected function getViewData(): array
    {
        return [
            'announcements' => Announcement::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })
                ->latest()
                ->get()
        ];
    }
}