<?php

namespace App\Policies\Staff\Dashboard;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class DashboardPolicy
{
    public function viewAny(User $user)
    {
        return $user->isStaff() ? Response::allow() : 
            Response::deny('You are not allowed to access this resource', 403);
    }
}
