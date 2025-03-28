<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\InternalinspectionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportsectionController;
use App\Http\Controllers\ReportquestionsController;

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
        Route::post('/farm/assignstaff', [FarmController::class, 'assignstaff']);
        Route::get('/farm/view', [FarmController::class, 'displayfarm']);
    });

Route::middleware('auth')->group(function () {
    Route::get('report',[ReportsController::class, 'new'])->name('new');
    Route::get('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/showsection',[ReportsectionController::class, 'showsection'])->name('showsection');
    Route::post('report/getsection',[ReportsectionController::class, 'getsection'])->name('getsection');
    Route::post('report/newsection',[ReportsectionController::class, 'newsection'])->name('newsection');
    Route::post('report/showquestion',[ReportquestionsController::class, 'showquestion'])->name('showquestion');
    Route::post('report/newquestion',[ReportquestionsController::class, 'newquestion'])->name('newquestion');
});


Route::middleware('auth')->group(function () {
    Route::get('inspection',[InternalinspectionController::class, 'index'])->name('index');
    Route::get('inspection/new',[InternalinspectionController::class, 'new'])->name('new');
    Route::post('inspection/start',[InternalinspectionController::class, 'start'])->name('start');
    Route::post('inspection/nextsection',[InternalinspectionController::class, 'nextsection'])->name('nextsection');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
