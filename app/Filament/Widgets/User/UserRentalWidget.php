<?php

namespace App\Filament\Widgets\User;

use App\Models\Rental;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserRentalWidget extends Widget
{
    protected static string $view = 'filament.widgets.user.user-rental-widget';
    
    public function getRentals()
    {
        return Rental::where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed'])
            ->latest()
            ->get();
    }
}