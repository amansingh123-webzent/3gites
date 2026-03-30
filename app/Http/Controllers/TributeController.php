<?php

namespace App\Http\Controllers;

use App\Models\Tribute;
use Illuminate\View\View;

class TributeController extends Controller
{
    /**
     * GET /in-loving-memory
     * Listing of all tribute/memorial pages. Public.
     */
    public function index(): View
    {
        $tributes = Tribute::with('user')
            ->orderBy('member_name')
            ->get();

        return view('tributes.index', compact('tributes'));
    }

    /**
     * GET /in-loving-memory/{tribute}
     * Individual tribute page. Public.
     */
    public function show(Tribute $tribute): View
    {
        return view('tributes.show', compact('tribute'));
    }
}
