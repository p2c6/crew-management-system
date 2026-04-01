<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AccessModule;
use App\Models\Entity;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = Entity::query()->get();

        //Admin
        $admin = User::query()
            ->whereHas('role', function ($q) {
                $q->where('slug', UserRole::SystemAdministrator->value);
            })
            ->firstOrFail();

        $adminDataEntites = $this->getEntities($entities, $admin);
        AccessModule::query()->insert($adminDataEntites);

        //Staff
        $staffEntities = Entity::query()->whereIn('title', ['Dashboard', 'Crews'])->get();

        $staff = User::query()
            ->whereHas('role', function ($q) {
                $q->where('slug', UserRole::Staff->value);
            })
            ->firstOrFail();

        $staffDataEntites = $this->getEntities($staffEntities, $staff);
        AccessModule::query()->insert($staffDataEntites);
        
    }

    public function getEntities($entities, $user): array
    {
        $data = [];

        foreach ($entities as $entity) {
            $data[] = [
                'user_id' => $user->id,
                'entity_id' => $entity->id,
                'created_at' => now()
            ];
        }

        return $data;
    }
}
