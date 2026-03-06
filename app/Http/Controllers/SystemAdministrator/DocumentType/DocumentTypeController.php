<?php

namespace App\Http\Controllers\SystemAdministrator\DocumentType;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentType\StoreDocumentTypeRequest;
use App\Http\Requests\DocumentType\UpdateDocumentTypeRequest;
use App\Http\Resources\DocumentType\DocumentTypeCollection;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DocumentTypeController extends Controller
{
    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-document-types', User::class);

        $documentTypes = DocumentType::query()
        ->when(request('search'), fn ($q) =>
            $q->where('name', 'like', '%' . request('search') . '%')
        )
        ->when(request('sort'), fn ($q) =>
            $q->orderBy(request('sort'), request('direction', 'asc'))
        )
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('SystemAdministrator/DocumentTypes/Index', [
            'document_types' => new DocumentTypeCollection($documentTypes),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }
    
    /** 
     * Handle show document
     * 
     * @return App\Http\Resources\DocumentType\DocumentTypeResource
     */
    public function show(DocumentType $documentType): DocumentTypeResource
    {
        $this->authorize('system-administrator-view-document-types', User::class);

        return new DocumentTypeResource($documentType);
    }

    /**
     * Handle store document request
     */
    public function store(StoreDocumentTypeRequest $request)
    {
        $this->authorize('system-administrator-create-document-types', User::class);

        DB::transaction(function() use ($request) {
            DocumentType::query()->create([
                'name' => $request->name
            ]);
        });
    }
    
    /**
     * Handle update document request
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        $this->authorize('system-administrator-update-document-types', User::class);

        DB::transaction(function() use ($documentType, $request) {
            $documentType->update([
                'name' => $request->name
            ]);
        });
    }

    /**
     * Handle delete document request
     */
    public function destroy(DocumentType $documentType)
    {
        $this->authorize('system-administrator-delete-document-types', User::class);

        DB::transaction(function() use ($documentType) {
            $documentType->delete();
        });
    }
}