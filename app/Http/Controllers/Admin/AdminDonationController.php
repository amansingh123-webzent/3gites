<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\View\View;

class AdminDonationController extends Controller
{
    public function index(): View
    {
        $donations = Donation::with('user')
            ->latest()
            ->paginate(25);

        $totals = [
            'all_time'  => Donation::where('status', 'completed')->sum('amount'),
            'this_year' => Donation::where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'count'     => Donation::where('status', 'completed')->count(),
        ];

        return view('admin.donations.index', compact('donations', 'totals'));
    }
}
