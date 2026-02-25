<?php

namespace App\Http\Controllers\SystemAdministrator\Rank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rank\StoreRankRequest;
use App\Http\Requests\Rank\UpdateRankRequest;
use App\Http\Resources\Rank\RankCollection;
use App\Http\Resources\Rank\RankResource;
use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RankController extends Controller
{
    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {
        $this->authorize('system-administrator-view-any-ranks', User::class);

        $ranks = Rank::query()
        ->when(request('search'), fn ($q) =>
            $q->where('code', 'like', '%' . request('search') . '%')
            ->orWhere('short_name', 'like', '%' . request('search') . '%')
            ->orWhere('alias', 'like', '%' . request('search') . '%')
        )
        ->when(request('sort'), fn ($q) =>
            $q->orderBy(request('sort'), request('direction', 'asc'))
        )
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('SystemAdministrator/Ranks/Index', [
            'ranks' => new RankCollection($ranks),
            'filters' => request()->only(['search', 'sort', 'direction']),
        ]);
    }
    
    /** 
     * Handle show document
     * 
     * @return App\Http\Resources\Rank\RankResource
     */
    public function show(Rank $rank): RankResource
    {
        $this->authorize('system-administrator-view-ranks', User::class);

        return new RankResource($rank);
    }

    /**
     * Handle store document request
     */
    public function store(StoreRankRequest $request)
    {
        $this->authorize('system-administrator-create-ranks', User::class);

        DB::transaction(function() use ($request) {
            Rank::query()->create([
                'code' => $request->code,
                'short_name' => $request->short_name,
                'alias' => $request->alias,
            ]);
        });
    }
    
    /**
     * Handle update document request
     */
    public function update(UpdateRankRequest $request, Rank $rank)
    {
        $this->authorize('system-administrator-update-ranks', User::class);

        DB::transaction(function() use ($rank, $request) {
            $rank->update([
                'code' => $request->code,
                'short_name' => $request->short_name,
                'alias' => $request->alias,
            ]);
        });
    }

    /**
     * Handle delete document request
     */
    public function destroy(Rank $rank)
    {
        $this->authorize('system-administrator-delete-ranks', User::class);

        DB::transaction(function() use ($rank) {
            $rank->delete();
        });
    }
}