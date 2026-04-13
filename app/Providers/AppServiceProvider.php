<?php

namespace App\Providers;

use App\Policies\Staff\Crew\CrewPolicy as StaffCrewPolicy;
use App\Policies\SystemAdministrator\Dashboard\DashboardPolicy as SystemAdministratorPolicy;
use App\Policies\Staff\Dashboard\DashboardPolicy as StaffPolicy;
use App\Policies\SystemAdministrator\Crew\CrewPolicy;
use App\Policies\SystemAdministrator\Document\DocumentPolicy;
use App\Policies\SystemAdministrator\DocumentType\DocumentTypePolicy;
use App\Policies\SystemAdministrator\Profile\ProfilePolicy as SystemAdministratorProfilePolicy;
use App\Policies\Staff\Profile\ProfilePolicy as StaffProfilePolicy;
use App\Policies\SystemAdministrator\Rank\RankPolicy;
use App\Policies\SystemAdministrator\Role\RolePolicy;
use App\Policies\SystemAdministrator\User\UserPolicy;
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
        //ADMIN DASHBOARD
        Gate::define('system-administrator-view-any-dashboard', [SystemAdministratorPolicy::class, 'viewAny']);
         //USER
        Gate::define('system-administrator-view-any-users', [UserPolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-users', [UserPolicy::class, 'view']);
        Gate::define('system-administrator-create-users', [UserPolicy::class, 'create']);
        Gate::define('system-administrator-update-users', [UserPolicy::class, 'update']);
        Gate::define('system-administrator-delete-users', [UserPolicy::class, 'delete']);
        //DOCUMENT
        Gate::define('system-administrator-view-any-documents', [DocumentPolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-documents', [DocumentPolicy::class, 'view']);
        Gate::define('system-administrator-create-documents', [DocumentPolicy::class, 'create']);
        Gate::define('system-administrator-update-documents', [DocumentPolicy::class, 'update']);
        Gate::define('system-administrator-delete-documents', [DocumentPolicy::class, 'delete']);
        //DOCUMENT
        Gate::define('system-administrator-view-any-document-types', [DocumentTypePolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-document-types', [DocumentTypePolicy::class, 'view']);
        Gate::define('system-administrator-create-document-types', [DocumentTypePolicy::class, 'create']);
        Gate::define('system-administrator-update-document-types', [DocumentTypePolicy::class, 'update']);
        Gate::define('system-administrator-delete-document-types', [DocumentTypePolicy::class, 'delete']);
        //ROLE
        Gate::define('system-administrator-view-any-roles', [RolePolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-roles', [RolePolicy::class, 'view']);
        Gate::define('system-administrator-create-roles', [RolePolicy::class, 'create']);
        Gate::define('system-administrator-update-roles', [RolePolicy::class, 'update']);
        Gate::define('system-administrator-delete-roles', [RolePolicy::class, 'delete']);
        //RANK
        Gate::define('system-administrator-view-any-ranks', [RankPolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-ranks', [RankPolicy::class, 'view']);
        Gate::define('system-administrator-create-ranks', [RankPolicy::class, 'create']);
        Gate::define('system-administrator-update-ranks', [RankPolicy::class, 'update']);
        Gate::define('system-administrator-delete-ranks', [RankPolicy::class, 'delete']);
        //CREW
        Gate::define('system-administrator-view-any-crews', [CrewPolicy::class, 'viewAny']);
        Gate::define('system-administrator-view-crews', [CrewPolicy::class, 'view']);
        Gate::define('system-administrator-create-crews', [CrewPolicy::class, 'create']);
        Gate::define('system-administrator-update-crews', [CrewPolicy::class, 'update']);
        Gate::define('system-administrator-delete-crews', [CrewPolicy::class, 'delete']);
        Gate::define('system-administrator-view-any-crew-documents', [CrewPolicy::class, 'viewAnyDocument']);
        Gate::define('system-administrator-view-crew-documents', [CrewPolicy::class, 'viewDocument']);
        Gate::define('system-administrator-create-crew-documents', [CrewPolicy::class, 'createDocument']);
        Gate::define('system-administrator-update-crew-documents', [CrewPolicy::class, 'updateDocument']);
        Gate::define('system-administrator-delete-crew-documents', [CrewPolicy::class, 'deleteDocument']);
        Gate::define('system-administrator-bulk-upload-crews', [CrewPolicy::class, 'bulkUpload']);
        //PROFILE
        Gate::define('system-administrator-update-profile', [SystemAdministratorProfilePolicy::class, 'update']);
        Gate::define('system-administrator-update-personal-information', [SystemAdministratorProfilePolicy::class, 'updatePersonalInformation']);
        Gate::define('system-administrator-update-password', [SystemAdministratorProfilePolicy::class, 'updatePassword']);
        //STAFF DASHBOARD
        Gate::define('staff-view-any-dashboard', [StaffPolicy::class, 'viewAny']);
        //CREW
        Gate::define('staff-view-any-crews', [StaffCrewPolicy::class, 'viewAny']);
        Gate::define('staff-view-crews', [StaffCrewPolicy::class, 'view']);
        Gate::define('staff-create-crews', [StaffCrewPolicy::class, 'create']);
        Gate::define('staff-update-crews', [StaffCrewPolicy::class, 'update']);
        Gate::define('staff-delete-crews', [StaffCrewPolicy::class, 'delete']);
        Gate::define('staff-view-any-crew-documents', [StaffCrewPolicy::class, 'viewAnyDocument']);
        Gate::define('staff-view-crew-documents', [StaffCrewPolicy::class, 'viewDocument']);
        Gate::define('staff-create-crew-documents', [StaffCrewPolicy::class, 'createDocument']);
        Gate::define('staff-update-crew-documents', [StaffCrewPolicy::class, 'updateDocument']);
        Gate::define('staff-delete-crew-documents', [StaffCrewPolicy::class, 'deleteDocument']);
         //PROFILE
        Gate::define('staff-update-profile', [StaffProfilePolicy::class, 'update']);
        Gate::define('staff-update-personal-information', [StaffProfilePolicy::class, 'updatePersonalInformation']);
        Gate::define('staff-update-password', [StaffProfilePolicy::class, 'updatePassword']);
    }
}
