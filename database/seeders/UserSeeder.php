<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@test.com',
                'full_name' => 'Administrator',
                'password' => bcrypt('password123'),
                'role_id' => UserRole::SystemAdministrator->id(),
                'created_at' => now(),
            ],
            [
                'email' => 'staff@test.com',
                'full_name' => 'Staff',
                'password' => bcrypt('password123'),
                'role_id' => UserRole::Staff->id(),
                'created_at' => now(),
            ],
        ];

        User::query()->insert($users);
    }
}
