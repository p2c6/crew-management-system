<?php

namespace App\Providers;

use App\Policies\SystemAdministrator\Dashboard\DashboardPolicy as SystemAdministratorPolicy;
use App\Policies\Staff\Dashboard\DashboardPolicy as StaffPolicy;
use App\Policies\SystemAdministrator\Document\DocumentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        $this->bootGates();
    }

    private function bootGates(): void
    {
        Gate::define('system-administrator-view-any-dashboard', [SystemAdministratorPolicy::class, 'viewAny']);
        
        Gate::define('system-administrator-view-any-documents', [DocumentPolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-documents', [DocumentPolicy::class, 'view']);
        Gate::define('system-administrator-create-documents', [DocumentPolicy::class, 'create']);
        Gate::define('system-administrator-update-documents', [DocumentPolicy::class, 'update']);
        Gate::define('system-administrator-delete-documents', [DocumentPolicy::class, 'delete']);
        
        Gate::define('staff-view-any-dashboard', [StaffPolicy::class, 'viewAny']);
    }
}
