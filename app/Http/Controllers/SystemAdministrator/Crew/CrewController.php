<?php

namespace App\Http\Controllers\SystemAdministrator\Crew;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crew\StoreCrewRequest;
use App\Http\Requests\Crew\UpdateCrewRequest;
use App\Http\Resources\Crew\CrewCollection;
use App\Http\Resources\Crew\CrewResource;
use App\Models\Crew;
use App\Models\User;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CrewController extends Controller
{
    /**
     * The instance of RoleService class
     * 
     * @param App\Services\Role\RoleService $roleServiice
     */
    public function __construct(protected RoleService $roleService) { }

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
            ->orWhereDate('birthdate', request('search') . '%')
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
        return Inertia::render('SystemAdministrator/Crews/Create');
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

        DB::transaction(function() use ($request) {
            Crew::query()->create($request->validated());
        });
    }

    public function edit(Crew $crew)
    {
        return Inertia::render('SystemAdministrator/Crews/Create');
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