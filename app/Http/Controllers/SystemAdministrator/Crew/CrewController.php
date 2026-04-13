<?php

namespace App\Http\Controllers\SystemAdministrator\Crew;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crew\StoreCrewRequest;
use App\Http\Requests\Crew\UpdateCrewRequest;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Http\Resources\Crew\CrewCollection;
use App\Http\Resources\Crew\CrewResource;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\DocumentType\DocumentTypeResource;
use App\Http\Resources\Rank\RankResource;
use App\Jobs\ImportCrewJob;
use App\Models\Crew;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use App\Services\Rank\RankService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CrewController extends Controller
{
    /**
     * The instance of RankService class
     * 
     * @param App\Services\Rank\RankService $rankService
     */
    public function __construct(protected RankService $rankService) {}

    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-crews', User::class);

        $crews = Crew::query()
            ->with('rank:id,short_name')
            ->when(
                request('search'),
                fn($q) =>
                $q->where('first_name', 'like', '%' . request('search') . '%')
                    ->orWhere('middle_name', 'like', '%' . request('search') . '%')
                    ->orWhere('last_name', 'like', '%' . request('search') . '%')
                    ->orWhere('address', 'like', '%' . request('search') . '%')
                    ->orWhereDate('birth_date', request('search') . '%')
                    ->orWhereHas('rank', function ($q) {
                        $q->where('short_name', 'like', '%' . request('search') . '%');
                    })
            )
            ->when(
                request('sort'),
                fn($q) =>
                $q->orderBy(request('sort'), request('direction', 'asc'))
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('SystemAdministrator/Crews/Index', [
            'crews' => new CrewCollection($crews),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create()
    {
        $this->authorize('system-administrator-create-crews', User::class);

        $ranks = $this->rankService->getAllRanks();

        return Inertia::render('SystemAdministrator/Crews/Create', [
            'ranks' => RankResource::collection($ranks),
        ]);
    }

    /** 
     * Handle show crew
     * 
     * @return App\Http\Resources\Crew\CrewResource
     */
    public function show(Crew $user): CrewResource
    {
        $this->authorize('system-administrator-view-crews', User::class);

        return new CrewResource($user);
    }

    /**
     * Handle store crew request
     */
    public function store(StoreCrewRequest $request)
    {
        $this->authorize('system-administrator-create-crews', User::class);

        $crew = DB::transaction(function () use ($request) {
            return Crew::query()->create($request->validated());
        });

        return redirect()->route('system-administrator.crews.edit', $crew->id);
    }

    public function edit(Crew $crew)
    {
        $this->authorize('system-administrator-update-crews', User::class);

        $ranks = $this->rankService->getAllRanks();

        $crew->load('rank');

        return Inertia::render('SystemAdministrator/Crews/Edit', [
            'crew' => new CrewResource($crew),
            'ranks' => RankResource::collection($ranks),
        ]);
    }

    public function documents(Crew $crew)
    {
        $this->authorize('system-administrator-view-any-crew-documents', User::class);

        $crew->loadMissing('rank');

        $documents = $crew->documents()
            ->with('documentType', 'user')
            ->paginate(10)
            ->withQueryString();

        $ranks = $this->rankService->getAllRanks();

        return Inertia::render('SystemAdministrator/Crews/Edit', [
            'crew' => new CrewResource($crew),
            'documents' => DocumentResource::collection($documents),
            'documentTypes' => DocumentTypeResource::collection($this->getAllDocumentTypes()),
            'ranks' => RankResource::collection($ranks),
        ]);
    }

    public function view(Document $document)
    {
        $this->authorize('system-administrator-view-crew-documents', User::class);

        return Storage::disk('private')->response($document->file_path);
    }

    /**
     * Handle update crew document request
     */
    public function updateDocument(UpdateDocumentRequest $request, Document $document)
    {
        $this->authorize('system-administrator-update-crew-documents', User::class);

        if (
            blank($request->existing_document_id) &&
            Storage::disk('private')->exists($document->file_path)
        ) {
            Storage::disk('private')->delete($document->file_path);
        }

        $finalPath = $document->file_path;
        $fileName = $document->file_name;

        foreach ($request->documents as $tempId) {
            $tempId = trim($tempId, " \"'");

            if (!Str::isUuid($tempId)) continue;

            $tmpFiles = Storage::disk('local')->files('tmp');
            $tempFile = collect($tmpFiles)->first(fn($f) => str_contains($f, $tempId));

            if (!$tempFile) continue;

            $extension = pathinfo($tempFile, PATHINFO_EXTENSION);
            $fileName = "{$tempId}.{$extension}";
            $finalPath = "documents/{$fileName}";

            $stream = Storage::disk('local')->readStream($tempFile);
            Storage::disk('private')->writeStream($finalPath, $stream);
            Storage::disk('local')->delete($tempFile);
        }

        $document->update([
            'crew_id'          => $document->crew_id,
            'document_type_id' => $request->document_type_id,
            'code'             => $request->code,
            'issued_date'      => $request->issued_date,
            'expiry_date'      => $request->expiry_date,
            'file_path'        => $finalPath,
            'file_name'        => $fileName,
            'user_id'          => auth()->id(),
        ]);

        return redirect()->back();
    }

    /**
     * Handle store crew document request
     */
    public function storeDocument(StoreDocumentRequest $request)
    {
        $this->authorize('system-administrator-create-crew-documents', User::class);

        foreach ($request->documents as $tempId) {
            $tempId = trim($tempId, " \"'");

            if (!Str::isUuid($tempId)) continue;

            $tmpFiles = Storage::disk('local')->files('tmp');
            $tempFile = collect($tmpFiles)->first(fn($f) => str_contains($f, $tempId));

            if (!$tempFile) continue;

            $extension = pathinfo($tempFile, PATHINFO_EXTENSION);
            $fileName = "{$tempId}.{$extension}";
            $finalPath = "documents/{$fileName}";

            $stream = Storage::disk('local')->readStream($tempFile);
            Storage::disk('private')->writeStream($finalPath, $stream);
            Storage::disk('local')->delete($tempFile);

            Document::create([
                'crew_id'          => $request->crew_id,
                'document_type_id' => $request->document_type_id,
                'code'             => $request->code,
                'issued_date'      => $request->issued_date,
                'expiry_date'      => $request->expiry_date,
                'file_path'        => $finalPath,
                'file_name'        => $fileName,
                'user_id'          => auth()->id(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Handle delete crew document request
     */
    public function destroyDocument(Document $document)
    {
        $this->authorize('system-administrator-delete-crew-documents', User::class);

        DB::transaction(function () use ($document) {
            $document->delete();
        });

        return redirect()->back();
    }

    /**
     * Handle update crew request
     */
    public function update(UpdateCrewRequest $request, Crew $crew)
    {
        $this->authorize('system-administrator-update-crews', User::class);

        DB::transaction(function () use ($crew, $request) {
            $crew->update($request->validated());
        });

        return redirect()->route('system-administrator.crews.index');
    }

    /**
     * Handle delete crew request
     */
    public function destroy(Crew $crew)
    {
        $this->authorize('system-administrator-delete-crews', User::class);

        DB::transaction(function () use ($crew) {
            $crew->delete();
        });
    }

    public function getAllDocumentTypes()
    {
        return DocumentType::query()->get();
    }

    public function bulkUpload(Request $request)
    {
        $this->authorize('system-administrator-bulk-upload-crews', User::class);

        $request->validate([
            'file' => 'required|file|mimes:csv'
        ]);

        $file = $request->file('file');

        $data = array_map('str_getcsv', file($file->getRealPath()));

        unset($data[0]);

        $chunks = array_chunk($data, 1000);

        foreach ($chunks as $chunk) {
            dispatch(new ImportCrewJob($chunk));
        }

        return redirect()->back();
    }
}
