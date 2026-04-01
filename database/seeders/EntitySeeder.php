<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use App\Models\Entity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'title' => 'Dashboard',
                'icon' => 'dashboard',
                'is_main' => true,
                'created_at' => now(),
            ],
            [
                'title' => 'Crews',
                'icon' => 'crews',
                'is_main' => true,
                'created_at' => now(),
            ],
            [
                'title' => 'Ranks',
                'icon' => 'ranks',
                'is_main' => false,
                'created_at' => now(),
            ],
            [
                'title' => 'Document Types',
                'icon' => 'documentTypes',
                'is_main' => false,
                'created_at' => now(),
            ],
            [
                'title' => 'Roles',
                'icon' => 'roles',
                'is_main' => false,
                'created_at' => now(),
            ],
            [
                'title' => 'Users',
                'icon' => 'users',
                'is_main' => false,
                'created_at' => now(),
            ],
        ];

        Entity::query()->insert($entities);
    }
}
