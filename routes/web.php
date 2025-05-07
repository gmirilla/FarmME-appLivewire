<?php

use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmunitsController;
use App\Http\Controllers\InternalinspectionController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportsectionController;
use App\Http\Controllers\ReportquestionsController;
use App\Http\Controllers\userController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/** 
Route::view('dashboard', 'dashboard2')
    ->middleware(['auth', 'verified'])
    ->name('dashboard2');
*/
Route::middleware('auth')->group(function () {
    Route::get('user_admin',[userController::class, 'index'])->name('user_admin'); 
    Route::get('new_user',[userController::class, 'newuser'])->name('newuser');
    Route::post('user_update',[userController::class, 'user_update'])->name('user_update'); 

});

Route::middleware('auth')->group(function () {
    Route::get('maptest',[MapsController::class, 'index'])->name('user_admin'); 
});

    Route::middleware(['auth','verified'])->group(function () {
        
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
        Route::view('/unauthorized', 'unauthorized')->name('unauthorized');

    });

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
        Route::post('/farm/updatefarm', [FarmController::class, 'updatefarm']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/fu/edit',[FarmunitsController::class, 'edit'])->name('edit_farmunit'); 
        Route::get('/fu/list',[FarmunitsController::class, 'listfunits'])->name('listfunits'); 
        Route::get('/fu/new',[FarmunitsController::class, 'newfunit'])->name('newfunit'); 
    });
    

Route::middleware('auth')->group(function () {
    Route::get('report',[ReportsController::class, 'new'])->name('viewreports');
    Route::get('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/showsection',[ReportsectionController::class, 'showsection'])->name('showsectionreport');
    Route::post('report/getsection',[ReportsectionController::class, 'getsection'])->name('getsection');
    Route::post('report/newsection',[ReportsectionController::class, 'newsection'])->name('newsection');
    Route::post('report/showquestion',[ReportquestionsController::class, 'showquestion'])->name('showquestion');
    Route::post('report/newquestion',[ReportquestionsController::class, 'newquestion'])->name('newquestion');
});


Route::middleware('auth')->group(function () {
    Route::get('inspection',[InternalinspectionController::class, 'index'])->name('inspection');
    Route::get('inspection/new',[InternalinspectionController::class, 'new'])->name('new');
    Route::post('inspection/start',[InternalinspectionController::class, 'start'])->name('start');
    Route::post('inspection/nextsection',[InternalinspectionController::class, 'nextsection'])->name('nextsection');
    Route::post('inspection/continue',[InternalinspectionController::class, 'continue'])->name('continue');
    Route::get('inspection_approval',[InternalinspectionController::class, 'iapproval'])->name('iapproval');
    Route::post('iapprove',[InternalinspectionController::class, 'iapprove'])->name('iapprove');
    Route::get('iapprove',[InternalinspectionController::class, 'iapprove'])->name('iapprove');
    Route::post('ireject',[InternalinspectionController::class, 'iapprove'])->name('ireject');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
