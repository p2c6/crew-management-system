<?php

namespace App\Http\Controllers\SystemAdministrator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
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
        $this->authorize('system-administrator-view-any-users', User::class);

        $users = User::query()
        ->with('role:id,name')
        ->when(request('search'), fn ($q) =>
            $q->where('email', 'like', '%' . request('search') . '%')
            ->orWhereHas('role', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            })
        )
        ->when(request('sort'), fn ($q) =>
            $q->orderBy(request('sort'), request('direction', 'asc'))
        )
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('SystemAdministrator/Users/Index', [
            'users' => new UserCollection($users),
            'roles' => RoleResource::collection($this->roleService->getAllRoles()),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }
    
    /** 
     * Handle show user
     * 
     * @return App\Http\Resources\User\UserResource
     */
    public function show(User $user): UserResource
    {
        $this->authorize('system-administrator-view-users', User::class);

        return new UserResource($user);
    }

    /**
     * Handle store user request
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('system-administrator-create-users', User::class);

        DB::transaction(function() use ($request) {
            User::query()->create($request->validated());
        });
    }
    
    /**
     * Handle update user request
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('system-administrator-update-users', User::class);

        DB::transaction(function() use ($user, $request) {
            $user->update($request->validated());
        });
    }

    /**
     * Handle delete user request
     */
    public function destroy(User $user)
    {
        $this->authorize('system-administrator-delete-users', User::class);

        DB::transaction(function() use ($user) {
            $user->delete();
        });
    }
}