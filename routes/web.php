<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\FarmController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    /** 
Route::view('farm', 'farm')
    ->middleware(['auth', 'verified'])
    ->name('farm');


Route::view('newfarm', 'newfarm')
    ->middleware(['auth', 'verified'])
    ->name('newfarm');
**/
Route::middleware('auth')->group(function () {
        Route::get('/farm', [FarmController::class, 'index'])->name('index');
        Route::get('/newfarm', [FarmController::class, 'create'])->name('create');
        Route::post('/newfarm/Createfarm', [FarmController::class, 'store']);
        Route::post('/farm/schedule', [FarmController::class, 'newinspectiondate']);
        Route::get('/farm/view', [FarmController::class, 'displayfarm']);
    });

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
