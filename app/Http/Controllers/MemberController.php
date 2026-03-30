<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * GET /members
     * Public directory of all 50 classmates.
     * Guests can view; no auth required.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status'); // filter: active | searching | deceased | null (all)

        $members = User::withoutTrashed()
            ->with('profile')
            ->when($status, fn ($q) => $q->where('member_status', $status))
            ->whereNotNull('name')
            // Exclude admin-only accounts (admin role but not a real classmate)
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin')
                ->whereDoesntHave('users', fn ($q2) => $q2->where('member_status', 'active'))
            )
            ->orderBy('name')
            ->get();

        // Counts for filter tabs
        $counts = [
            'all'       => User::withoutTrashed()->whereHas('roles', fn($q) => $q->where('name', 'active_member'))->count(),
            'active'    => User::withoutTrashed()->where('member_status', 'active')->count(),
            'searching' => User::withoutTrashed()->where('member_status', 'searching')->count(),
            'deceased'  => User::withoutTrashed()->where('member_status', 'deceased')->count(),
        ];

        return view('members.index', compact('members', 'counts', 'status'));
    }

    /**
     * GET /members/{user}
     * Public profile page. Works for active AND searching members.
     * Deceased members redirect to their tribute page.
     */
    public function show(User $user): mixed
    {
        // Deceased → redirect to tribute page
        if ($user->member_status === 'deceased') {
            $tribute = $user->tribute;
            if ($tribute) {
                return redirect()->route('tributes.show', $tribute);
            }
            abort(404);
        }

        $user->load('profile', 'birthday');

        return view('members.show', compact('user'));
    }
}
