<?php

namespace App\Filament\Widgets\User;

use App\Models\Rental;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserRentalWidget extends Widget
{
    protected static string $view = 'filament.widgets.user.user-rental-widget';
    
    public function getRentals()
    {
        return Rental::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->where('end_datetime', '>', Carbon::now())
            ->latest()
            ->get();
    }

    public function checkExpiredRentals()
    {
        // Update status rental yang sudah lewat tanggal selesai
        Rental::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->where('end_datetime', '<=', Carbon::now())
            ->update(['status' => 'completed']);
    }

    public function mount()
    {
        // Cek rental yang sudah expired setiap kali widget dimuat
        $this->checkExpiredRentals();
    }
}