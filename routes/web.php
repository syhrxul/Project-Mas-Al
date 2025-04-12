<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
// Remove the Volt import for now

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Set landing page as the default route
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // Replace Volt routes with standard Laravel routes
    Route::view('settings/profile', 'settings.profile')->name('settings.profile');
    Route::view('settings/password', 'settings.password')->name('settings.password');
    Route::view('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
