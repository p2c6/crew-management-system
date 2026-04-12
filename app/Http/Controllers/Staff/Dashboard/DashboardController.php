<?php

namespace App\Http\Controllers\Staff\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Crew;
use App\Models\Document;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('staff-view-any-dashboard', User::class);

        return Inertia::render('Staff/Dashboard/Index', [
            'crews' => $this->getCrewCount(),
            'documents' => $this->getDocumentCount(),
        ]);
    }

    public function getCrewCount()
    {
        return Crew::count();
    }

    public function getDocumentCount()
    {
        return Document::count();
    }
}