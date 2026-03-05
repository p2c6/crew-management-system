<?php

namespace App\Policies\SystemAdministrator\Crew;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class CrewPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function view(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function create(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function update(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function delete(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
}
