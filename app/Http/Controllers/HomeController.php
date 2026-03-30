<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $nextEvent = Event::upcoming()->first();

        $stats = [
            'active'    => User::where('member_status', 'active')->count(),
            'searching' => User::where('member_status', 'searching')->count(),
            'deceased'  => User::where('member_status', 'deceased')->count(),
        ];

        return view('home', compact('nextEvent', 'stats'));
    }
}
