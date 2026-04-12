<?php

namespace App\Http\Controllers\SystemAdministrator\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Crew;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Rank;
use App\Models\Role;
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
        $this->authorize('system-administrator-view-any-dashboard', User::class);
        
        return Inertia::render('SystemAdministrator/Dashboard/Index', [
            'crews' => $this->getCrewCount(),
            'documents' => $this->getDocumentCount(),
            'ranks' => $this->getRankCount(),
            'document_types' => $this->getDocumentTypeCount(),
            'roles' => $this->getRoleCount(),
            'users' => $this->getUserCount(),
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

    public function getRankCount()
    {
        return Rank::count();
    }

    public function getDocumentTypeCount()
    {
        return DocumentType::count();
    }

    public function getRoleCount()
    {
        return Role::count();
    }

    public function getUserCount()
    {
        return User::count();
    }
}