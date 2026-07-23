<?php

namespace App\Http\Responses;

use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $home = match ($user->role) {
            User::ROLE_ADMIN    => route('admin.dashboard'),
            User::ROLE_SUPPLIER => route('supplier.dashboard'),
            User::ROLE_CUSTOMER => route('customer.dashboard'),
            default             => route('dashboard'),
        };

        // Inertia responses must be redirects (not Inertia::render)
        // from POST handlers like login
        return redirect()->intended($home);
    }
}
