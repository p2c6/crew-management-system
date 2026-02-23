<?php

namespace App\Http\Controllers\SystemAdministrator\Document;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentCollection;
use App\Models\Document;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-documents', User::class);

        $documents = Document::query()
        ->when(request('search'), fn ($q) =>
            $q->where('name', 'like', '%' . request('search') . '%')
        )
        ->when(request('sort'), fn ($q) =>
            $q->orderBy(request('sort'), request('direction', 'asc'))
        )
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('SystemAdministrator/Documents/Index', [
            'documents' => new DocumentCollection($documents),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }
}