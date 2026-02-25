<?php

namespace App\Http\Controllers\SystemAdministrator\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Models\Document;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-roles', User::class);

        $roles = Role::query()
        ->when(request('search'), fn ($q) =>
            $q->where('name', 'like', '%' . request('search') . '%')
            ->orWhere('slug', 'like', '%' . request('search') . '%')
        )
        ->when(request('sort'), fn ($q) =>
            $q->orderBy(request('sort'), request('direction', 'asc'))
        )
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('SystemAdministrator/Roles/Index', [
            'roles' => new RoleCollection($roles),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }
    
    /** 
     * Handle show document
     * 
     * @return App\Http\Resources\Role\RoleResource
     */
    public function show(Role $role): RoleResource
    {
        $this->authorize('system-administrator-view-roles', User::class);

        return new RoleResource($role);
    }

    /**
     * Handle store document request
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('system-administrator-create-roles', User::class);

        DB::transaction(function() use ($request) {
            Role::query()->create([
                'name' => $request->name,
                'slug' => $request->slug,
            ]);
        });
    }
    
    /**
     * Handle update document request
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('system-administrator-update-roles', User::class);

        DB::transaction(function() use ($role, $request) {
            $role->update([
                'name' => $request->name,
                'slug' => Str::snake($request->slug),
            ]);
        });
    }

    /**
     * Handle delete document request
     */
    public function destroy(Role $role)
    {
        $this->authorize('system-administrator-delete-roles', User::class);

        DB::transaction(function() use ($role) {
            $role->delete();
        });
    }
}