<?php

namespace App\Policies\SystemAdministrator\Profile;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
    public function update(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function updatePersonalInformation(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function updatePassword(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
}
