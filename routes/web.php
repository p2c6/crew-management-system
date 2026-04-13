<?php

use App\Http\Controllers\Staff\Crew\CrewController as StaffCrewController;
use App\Http\Controllers\SystemAdministrator\Dashboard\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\Dashboard\DashboardController as StaffDashboardController;
use App\Http\Controllers\SystemAdministrator\Crew\CrewController;
use App\Http\Controllers\SystemAdministrator\Document\DocumentController;
use App\Http\Controllers\SystemAdministrator\DocumentType\DocumentTypeController;
use App\Http\Controllers\SystemAdministrator\Profile\ProfileController as AdminProfileController;
use App\Http\Controllers\Staff\Profile\ProfileController as StaffProfileController;
use App\Http\Controllers\SystemAdministrator\Rank\RankController;
use App\Http\Controllers\SystemAdministrator\Role\RoleController;
use App\Http\Controllers\SystemAdministrator\User\UserController;
use App\Http\Controllers\Upload\UploadController;
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

Route::middleware('auth')->group(function () {
    Route::controller(UploadController::class)
        ->group(function () {
            Route::post('/upload', 'store');
            Route::delete('/upload', 'revert');
        });

    Route::prefix('/system-administrator')
        ->name('system-administrator.')
        ->group(function () {
            Route::controller(AdminProfileController::class)
            ->prefix('/profile')
            ->name('profile.')
            ->group(function() {
                Route::get('/',  'index')->name('edit');
                Route::put('/update-personal-information', 'updatePersonalInformation')->name('update.personal-information');
                Route::put('/update-password', 'updatePassword')->name('update.password');
            });

            Route::get('/', fn() =>  redirect()->route('system-administrator.dashboard'))->name('root');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

            Route::controller(DocumentController::class)
                ->prefix('/documents')
                ->name('documents.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{document}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{document}', 'update')->name('update');
                    Route::delete('/{document}', 'destroy')->name('destroy');
                });

            Route::controller(DocumentTypeController::class)
                ->prefix('/document-types')
                ->name('document-types.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{documentType}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{documentType}', 'update')->name('update');
                    Route::delete('/{documentType}', 'destroy')->name('destroy');
                });

            Route::controller(RoleController::class)
                ->prefix('/roles')
                ->name('roles.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{role}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{role}', 'update')->name('update');
                    Route::delete('/{role}', 'destroy')->name('destroy');
                });

            Route::controller(RankController::class)
                ->prefix('/ranks')
                ->name('ranks.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{rank}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{rank}', 'update')->name('update');
                    Route::delete('/{rank}', 'destroy')->name('destroy');
                });

            Route::controller(UserController::class)
                ->prefix('/users')
                ->name('users.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{user}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{user}', 'update')->name('update');
                    Route::delete('/{user}', 'destroy')->name('destroy');
                });

            Route::controller(CrewController::class)
                ->prefix('/crews')
                ->name('crews.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::get('/{crew}/edit', 'edit')->name('edit');
                    Route::name('documents.')
                        ->group(function () {
                            Route::get('/{crew}/edit/documents', 'documents')->name('index');
                            Route::post('documents/store', 'storeDocument')->name('store');
                            Route::get('/documents/{document}/view', 'view')->name('view');
                            Route::put('/documents/{document}/update', 'updateDocument')->name('update');
                            Route::delete('/documents/{document}/destory', 'destroyDocument')->name('destroy');
                        });
                    Route::get('/{crew}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::post('/bulk-upload', 'bulkUpload')->name('bulk-upload');
                    Route::put('/{crew}', 'update')->name('update');
                    Route::delete('/{crew}', 'destroy')->name('destroy');
                });
        });

    Route::controller(StaffDashboardController::class)
        ->prefix('/staff')
        ->name('staff.')
        ->group(function () {
            Route::get('/', fn() =>  redirect()->route('staff.dashboard'))->name('index');
            Route::get('/dashboard', 'index')->name('dashboard.index');

            Route::controller(StaffProfileController::class)
            ->prefix('/profile')
            ->name('profile.')
            ->group(function() {
                Route::get('/', 'index')->name('edit');
                Route::put('/update-personal-information', 'updatePersonalInformation')->name('update.personal-information');
                Route::put('/update-password', 'updatePassword')->name('update.password');
            });

            Route::controller(StaffCrewController::class)
                ->prefix('/crews')
                ->name('crews.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::get('/{crew}/edit', 'edit')->name('edit');
                    Route::name('documents.')
                        ->group(function () {
                            Route::get('/{crew}/edit/documents', 'documents')->name('index');
                            Route::post('documents/store', 'storeDocument')->name('store');
                            Route::get('/documents/{document}/view', 'view')->name('view');
                            Route::put('/documents/{document}/update', 'updateDocument')->name('update');
                            Route::delete('/documents/{document}/destory', 'destroyDocument')->name('destroy');
                        });
                    Route::get('/{crew}', 'show')->name('show');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{crew}', 'update')->name('update');
                    Route::delete('/{crew}', 'destroy')->name('destroy');
                });
        });
});

require __DIR__ . '/auth.php';
