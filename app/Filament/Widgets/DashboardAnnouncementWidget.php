<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardAnnouncementWidget extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-announcement';
    
    protected static bool $isLazy = true;

    public static function canView(): bool
    {
        return true;
    }

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