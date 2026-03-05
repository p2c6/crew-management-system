<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemAdministrator\Dashboard\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\Dashboard\DashboardController as StaffDashboardController;
use App\Http\Controllers\SystemAdministrator\Crew\CrewController;
use App\Http\Controllers\SystemAdministrator\Document\DocumentController;
use App\Http\Controllers\SystemAdministrator\Rank\RankController;
use App\Http\Controllers\SystemAdministrator\Role\RoleController;
use App\Http\Controllers\SystemAdministrator\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/system-administrator')
    ->name('system-administrator.')
    ->group(function() {
        Route::get('/', fn() =>  redirect()->route('system-administrator.dashboard'))->name('system-administrator.root');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::controller(DocumentController::class)
        ->prefix('/documents')
        ->name('documents.')
        ->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{document}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::put('/{document}', 'update')->name('update');
            Route::delete('/{document}', 'destroy')->name('destroy');
        });
        
        Route::controller(RoleController::class)
        ->prefix('/roles')
        ->name('roles.')
        ->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{role}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::put('/{role}', 'update')->name('update');
            Route::delete('/{role}', 'destroy')->name('destroy');
        });

        Route::controller(RankController::class)
        ->prefix('/ranks')
        ->name('ranks.')
        ->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{rank}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::put('/{rank}', 'update')->name('update');
            Route::delete('/{rank}', 'destroy')->name('destroy');
        });

        Route::controller(UserController::class)
        ->prefix('/users')
        ->name('users.')
        ->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/{user}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

        Route::controller(CrewController::class)
        ->prefix('/crews')
        ->name('crews.')
        ->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{crew}/edit', 'edit')->name('edit');
            Route::get('/{crew}', 'show')->name('show');
            Route::post('/store', 'store')->name('store');
            Route::put('/{crew}', 'update')->name('update');
            Route::delete('/{crew}', 'destroy')->name('destroy');
        });
    });
    
    Route::controller(StaffDashboardController::class)
    ->prefix('/staff')
    ->name('staff.')
    ->group(function() {
        Route::get('/', fn() =>  redirect()->route('staff.dashboard'))->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
    });
});

require __DIR__.'/auth.php';
