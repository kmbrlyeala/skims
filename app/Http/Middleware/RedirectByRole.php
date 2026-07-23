<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Redirect authenticated users to their role-specific dashboard.
     *
     * Apply this to the generic /dashboard route so users land
     * on their proper home page after login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $target = match ($user->role) {
            User::ROLE_ADMIN    => route('admin.dashboard'),
            User::ROLE_SUPPLIER => route('supplier.dashboard'),
            User::ROLE_CUSTOMER => route('customer.dashboard'),
            default             => null,
        };

        // Only redirect if we're on the generic /dashboard
        // and the user has a role-specific destination
        if ($target && $request->routeIs('dashboard')) {
            return redirect($target);
        }

        return $next($request);
    }
}
