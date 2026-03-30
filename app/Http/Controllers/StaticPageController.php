<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        $stats = [
            'active'   => User::where('member_status', 'active')->count(),
            'total'    => User::withoutTrashed()->count() - 1, // exclude admin
            'deceased' => User::where('member_status', 'deceased')->count(),
        ];

        return view('pages.about', compact('stats'));
    }

    public function leadership(): View
    {
        // Leadership stored as a simple JSON config or in a DB table.
        // For simplicity, we manage it as a config file editable by admin.
        $roles = config('leadership.roles', []);
        return view('pages.leadership', compact('roles'));
    }
}
