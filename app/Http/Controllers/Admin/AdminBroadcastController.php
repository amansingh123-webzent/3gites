<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BroadcastMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminBroadcastController extends Controller
{
    public function index(): View
    {
        $activeCount = User::where('member_status', 'active')
            ->where('account_locked', false)
            ->whereNotNull('email')
            ->count();

        return view('admin.broadcast', compact('activeCount'));
    }

    /**
     * POST /admin/broadcast
     * Queue a broadcast email to all active members.
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'body'    => ['required', 'string', 'min:20', 'max:5000'],
            'confirm' => ['required', 'accepted'],
        ]);

        $members = User::where('member_status', 'active')
            ->where('account_locked', false)
            ->whereNotNull('email')
            ->get();

        foreach ($members as $member) {
            Mail::to($member->email)
                ->queue(new BroadcastMail($member, $validated['subject'], $validated['body']));
        }

        activity()
            ->causedBy(auth()->user())
            ->log("Broadcast email sent to {$members->count()} members: {$validated['subject']}");

        return back()->with('success',
            "Broadcast queued for {$members->count()} active members. It will be delivered shortly."
        );
    }
}
