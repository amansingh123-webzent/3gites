<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMemberMail;
use App\Models\Birthday;
use App\Models\Profile;
use App\Models\Tribute;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserCreationController extends Controller
{
    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'member_status' => ['required', Rule::in(['active', 'searching', 'deceased'])],
            'birth_month'   => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day'     => ['nullable', 'integer', 'min:1', 'max:31'],
            'birth_year'    => ['nullable', 'integer', 'min:1920', 'max:1980'],
        ]);

        // Generate a temporary password
        $tempPassword = 'Welcome1975!' . Str::random(4);

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'password'       => Hash::make($tempPassword),
            'member_status'  => $validated['member_status'],
            // Searching and deceased are locked until admin activates
            'account_locked' => in_array($validated['member_status'], ['searching', 'deceased']),
            'must_change_password' => $validated['member_status'] === 'active',
        ]);

        // Assign role
        $user->assignRole('active_member');

        // Create blank profile
        Profile::create(['user_id' => $user->id]);

        // Create tribute if deceased
        if ($validated['member_status'] === 'deceased') {
            Tribute::create([
                'user_id'      => $user->id,
                'member_name'  => $validated['name'],
                'tribute_text' => 'Tribute text to be added by the administrator.',
            ]);
        }

        // Create birthday record if provided
        if (! empty($validated['birth_month']) && ! empty($validated['birth_day'])) {
            Birthday::create([
                'user_id'     => $user->id,
                'birth_month' => $validated['birth_month'],
                'birth_day'   => $validated['birth_day'],
                'birth_year'  => $validated['birth_year'] ?? null,
            ]);
        }

        // Send welcome email to active members only
        if ($validated['member_status'] === 'active') {
            Mail::to($user->email)->send(new WelcomeMemberMail($user, $tempPassword));
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Admin created new member account: {$user->name}");

        return redirect()->route('admin.members.create')
            ->with('success', "Account created for {$user->name}." .
                ($validated['member_status'] === 'active'
                    ? ' A welcome email with login details has been sent.'
                    : ' Account is locked pending activation.'));
    }

    /**
     * Activate a "searching" member account when they've been found.
     */
    public function toggleLock(Request $request, User $user): RedirectResponse
    {
        $user->update([
            'account_locked' => ! $user->account_locked,
        ]);

        $action = $user->account_locked ? 'locked' : 'activated';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Admin {$action} account for: {$user->name}");

        return back()->with('success', "Account {$action} for {$user->name}.");
    }
}
