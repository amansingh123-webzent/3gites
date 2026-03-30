<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Don't redirect if already on the change-password page or logging out
            if (! $request->routeIs('password.change', 'password.change.update', 'logout')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Please set a personal password before continuing.');
            }
        }

        return $next($request);
    }
}
