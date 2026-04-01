<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserHasPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Get all permissions
        $permissions = Permission::query()->get();

        //Admin
        $admin = User::query()
            ->whereHas('role', function ($q) {
                $q->where('slug', UserRole::SystemAdministrator->value);
            })
            ->first();

        $adminPermissions = [];
        foreach ($permissions as $permission) {
            $adminPermissions[] = [
                'user_id' => $admin->id,
                'permission_id' => $permission->id,
                'created_at' => now()
            ];
        }

        UserHasPermission::query()->insert($adminPermissions);

        //Staff
        $staff = User::query()
            ->whereHas('role', function ($q) {
                $q->where('slug', UserRole::Staff->value);
            })
            ->first();

        $staffPermissions = [];
        foreach ($permissions as $permission) {
            if (Str::contains($permission, UserRole::Staff->value)) {
                $staffPermissions[] = [
                    'user_id' => $staff->id,
                    'permission_id' => $permission->id,
                    'created_at' => now()
                    ];
            }
        }

        UserHasPermission::query()->insert($staffPermissions);
    }
}
