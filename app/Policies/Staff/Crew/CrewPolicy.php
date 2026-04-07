<?php

namespace App\Policies\Staff\Crew;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class CrewPolicy
{
    public function viewAny(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function view(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function create(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function update(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function delete(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
    public function viewAnyDocument(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function viewDocument(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function createDocument(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function updateDocument(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }

    public function deleteDocument(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
}
