<?php

namespace App\Livewire\Widgets;

use App\Models\Announcement;
use Livewire\Component;
use Illuminate\Support\Carbon;

class AnnouncementsList extends Component
{
    public function render()
    {
        return view('livewire.widgets.announcements-list', [
            'announcements' => Announcement::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })
                ->latest()
                ->get()
        ]);
    }
}
