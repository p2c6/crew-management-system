<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $route =  route('system-administrator.dashboard.index', absolute: false) . '?verified=1';

        if ( $request->user()->role_id === UserRole::Staff->id() ) {
            $route = route('staff.dashboard.index', absolute: false) . '?verified=1';
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($route);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($route);
    }
}
