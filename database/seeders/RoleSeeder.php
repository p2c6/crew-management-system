<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = UserRole::SystemAdministrator;
        $staff = UserRole::Staff;

        $roles = [
            [
                'id' => $admin->id(),
                'name' => $admin->label(),
                'slug' => $admin->value,
                'created_at' => now()
            ],
            [
                'id' => $staff->id(),
                'name' => $staff->label(),
                'slug' => $staff->value,
                'created_at' => now()
            ],
        ];

        Role::query()->insert($roles);
    }
}
