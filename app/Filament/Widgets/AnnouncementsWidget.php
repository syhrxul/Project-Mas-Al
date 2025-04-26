<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;

#[Lazy]
class AnnouncementsWidget extends Widget
{
    protected static string $view = 'filament.widgets.announcements-widget';
    
    protected int | string | array $columnSpan = 'full';

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