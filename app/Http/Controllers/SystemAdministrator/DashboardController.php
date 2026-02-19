<?php

namespace App\Http\Controllers\SystemAdministrator;

use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        return Inertia::render('SystemAdministrator/Dashboard/Index');
    }
}