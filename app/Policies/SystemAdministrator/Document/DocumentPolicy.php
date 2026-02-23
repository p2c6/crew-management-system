<?php

namespace App\Policies\SystemAdministrator\Document;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
}
