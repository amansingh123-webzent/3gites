<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        
        $stats = [
            'total_members'    => User::withoutTrashed()->whereHas('roles', fn($q) => $q->where('name', 'active_member'))->count(),
            'active_members'   => User::where('member_status', 'active')->count(),
            'searching'        => User::where('member_status', 'searching')->count(),
            'deceased'         => User::where('member_status', 'deceased')->count(),
            'locked_accounts'  => User::where('account_locked', true)->where('member_status', 'searching')->count(),
            'total_donations'  => Donation::where('status', 'completed')->sum('amount'),
            'total_orders'     => Order::where('status', 'paid')->count(),
        ];

        $recentMembers = User::withoutTrashed()
            ->with('roles')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentMembers'));
    }
}
