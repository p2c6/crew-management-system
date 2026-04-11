<?php

namespace App\Http\Controllers\SystemAdministrator\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdatePersonalInformationRequest;
use App\Http\Resources\Profile\PasswordResource;
use App\Http\Resources\Profile\PersonalInformationResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $this->authorize('system-administrator-update-profile', User::class);

        $user = auth()->user();

        return Inertia::render('SystemAdministrator/Profile/Index', [
            'personalInformation' => new PersonalInformationResource($user),
            'password' => new PasswordResource($user)
        ]);
    }
    
    /**
     * Handle update user personal information request
     */
    public function updatePersonalInformation(UpdatePersonalInformationRequest $request)
    {
        $this->authorize('system-administrator-update-personal-information', User::class);

        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        $user->update([
            'full_name' =>  "{$request->first_name} {$request->last_name}",
        ]);
    }

    /**
     * Handle update user password request
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->authorize('system-administrator-update-password', User::class);

        $user = auth()->user();

        if (!$user) {
            abort(401);
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }
}