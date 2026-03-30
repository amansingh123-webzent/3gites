<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // LoginRequest handles throttling + credential check
        $request->authenticate();

        /** @var User $user */
        $user = Auth::user();

        // Block locked accounts (searching members not yet activated, deceased)
        if ($user->account_locked || $user->member_status === 'deceased') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $user->member_status === 'deceased'
                ? 'This account cannot be accessed.'
                : 'Your account is not yet active. Please contact the site administrator.';

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $message]);
        }

        $request->session()->regenerate();

        // Log the login activity
        activity()
            ->causedBy($user)
            ->log('Member logged in');

        // Check if this is a first login (password is still the seeded default)
        if ($user->must_change_password) {
            return redirect()->route('password.change')
                ->with('warning', 'Please change your temporary password before continuing.');
        }

        // Role-based redirect
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Member logged out');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user to appropriate dashboard based on role.
     */
    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
