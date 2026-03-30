<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contact');
    }

    /**
     * POST /contact
     * Rate limited: 5 per hour per IP (defined in AppServiceProvider).
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        Mail::to(config('mail.from.address'))
            ->send(new ContactFormMail($validated));

        return back()->with('success',
            'Your message has been sent. We will get back to you as soon as possible.'
        );
    }
}
