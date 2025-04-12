<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
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

// Add login routes
Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::post('/login', function() {
    if (auth()->attempt(request()->only('email', 'password'))) {
        request()->session()->regenerate();
        return redirect('/user');
    }
    
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login.post');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // Replace Volt routes with standard Laravel routes
    Route::view('settings/profile', 'settings.profile')->name('settings.profile');
    Route::view('settings/password', 'settings.password')->name('settings.password');
    Route::view('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    // Add logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Add these routes for registration
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store']);

// User dashboard route - only keep one definition
Route::get('/user/dashboard', function () {
    return redirect('/user');
})->middleware(['auth'])->name('user.dashboard');
