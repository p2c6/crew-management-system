<?php

namespace App\Http\Controllers\SystemAdministrator\Crew;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crew\StoreCrewRequest;
use App\Http\Requests\Crew\UpdateCrewRequest;
use App\Http\Resources\Crew\CrewCollection;
use App\Http\Resources\Crew\CrewResource;
use App\Http\Resources\Rank\RankCollection;
use App\Http\Resources\Rank\RankResource;
use App\Models\Crew;
use App\Models\User;
use App\Services\Rank\RankService;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CrewController extends Controller
{
    /**
     * The instance of RankService class
     * 
     * @param App\Services\Rank\RankService $rankService
     */
    public function __construct(protected RankService $rankService) { }

    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-crews', User::class);

        $crews = Crew::query()
        ->with('rank:id,short_name')
        ->when(request('search'), fn ($q) =>
            $q->where('first_name', 'like', '%' . request('search') . '%')
            ->orWhere('middle_name', 'like', '%' . request('search') . '%')
            ->orWhere('last_name', 'like', '%' . request('search') . '%')
            ->orWhere('address', 'like', '%' . request('search') . '%')
            ->orWhereDate('birth_date', request('search') . '%')
            ->orWhereHas('rank', function($q) {
                $q->where('short_name', 'like', '%' . request('search') . '%');
            })
        )
        ->when(request('sort'), fn ($q) =>
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

        $crew = DB::transaction(function() use ($request) {
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
    
    /**
     * Handle update crew request
     */
    public function update(UpdateCrewRequest $request, Crew $crew)
    {
        $this->authorize('system-administrator-update-crews', User::class);

        DB::transaction(function() use ($crew, $request) {
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

        DB::transaction(function() use ($crew) {
            $crew->delete();
        });
    }
}