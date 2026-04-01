<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // ADMIN DASHBOARD
            [
                'permission_name' => 'system-administrator-view-any-dashboard',
                'created_at' => now()
            ],

            // USER
            [
                'permission_name' => 'system-administrator-view-any-users',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-users',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-users',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-users',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-users',
                'created_at' => now()
            ],

            // DOCUMENT TYPES
            [
                'permission_name' => 'system-administrator-view-any-document-types',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-document-types',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-document-types',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-document-types',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-document-types',
                'created_at' => now()
            ],

            // ROLE
            [
                'permission_name' => 'system-administrator-view-any-roles',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-roles',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-roles',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-roles',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-roles',
                'created_at' => now()
            ],

            // RANK
            [
                'permission_name' => 'system-administrator-view-any-ranks',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-ranks',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-ranks',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-ranks',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-ranks',
                'created_at' => now()
            ],

            // CREW
            [
                'permission_name' => 'system-administrator-view-any-crews',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-crews',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-crews',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-crews',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-crews',
                'created_at' => now()
            ],

            // CREW DOCUMENTS
            [
                'permission_name' => 'system-administrator-view-any-crew-documents',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-view-crew-documents',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-create-crew-documents',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-update-crew-documents',
                'created_at' => now()
            ],
            [
                'permission_name' => 'system-administrator-delete-crew-documents',
                'created_at' => now()
            ],

            // STAFF DASHBOARD
            [
                'permission_name' => 'staff-view-any-dashboard',
                'created_at' => now()
            ],
        ];

        Permission::query()->insert($permissions);
    }
}
