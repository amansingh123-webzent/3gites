<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountIsNotLocked
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->account_locked) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not yet active. Please contact the administrator.']);
        }

        return $next($request);
    }
}
