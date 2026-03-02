<?php
namespace App\Services\Role;
use App\Models\Role;

class RoleService
{
    public function getAllRoles()
    {
        return Role::query()->get();
    }
}