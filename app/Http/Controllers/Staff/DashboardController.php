<?php

namespace App\Http\Controllers\Staff;

use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        return Inertia::render('Staff/Dashboard/Index');
    }
}