<?php

namespace App\Http\Controllers\SystemAdministrator\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\Document\DocumentCollection;
use App\Http\Resources\Document\DocumentResource;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
    
    /** 
     * Handle show document
     * 
     * @return App\Http\Resources\DocumentResource
     */
    public function show(Document $document): DocumentResource
    {
        $this->authorize('system-administrator-view-documents', User::class);

        return new DocumentResource($document);
    }

    /**
     * Handle store document request
     */
    public function store(StoreDocumentRequest $request)
    {
        $this->authorize('system-administrator-create-documents', User::class);

        DB::transaction(function() use ($request) {
            Document::query()->create([
                'name' => $request->name
            ]);
        });
    }
    
    /**
     * Handle update document request
     */
    public function update(StoreDocumentRequest $request, Document $document)
    {
        $this->authorize('system-administrator-update-documents', User::class);

        DB::transaction(function() use ($document, $request) {
            $document->update([
                'name' => $request->name
            ]);
        });
    }

    /**
     * Handle delete document request
     */
    public function destroy(Document $document)
    {
        $this->authorize('system-administrator-delete-documents', User::class);

        DB::transaction(function() use ($document) {
            $document->delete();
        });
    }
}