<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class DonationController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * GET /donate
     * Public page — guests can view, but must log in to donate.
     */
    public function index(): View
    {
        $totalRaised = Donation::where('status', 'completed')->sum('amount');
        $recentDonations = Donation::where('status', 'completed')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('donate.index', compact('totalRaised', 'recentDonations'));
    }

    /**
     * POST /donate/intent  (AJAX)
     * Create a Stripe PaymentIntent and return the client_secret to the JS.
     */
    public function createIntent(Request $request): JsonResponse
    {
        $minAmount = config('donation.min_amount', 5);
        
        $validated = $request->validate([
            'amount'  => ['required', 'numeric', "min:{$minAmount}", 'max:10000'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $amountCents = (int) round($validated['amount'] * 100);

        // Create a pending donation record first
        $donation = Donation::create([
            'user_id' => auth()->id(),
            'amount'  => $validated['amount'],
            'message' => $validated['message'] ?? null,
            'status'  => 'pending',
        ]);

        // Create the Stripe PaymentIntent
        $intent = PaymentIntent::create([
            'amount'   => $amountCents,
            'currency' => config('cashier.currency', 'usd'),
            'metadata' => [
                'donation_id' => $donation->id,
                'user_id'     => auth()->id(),
                'user_name'   => auth()->user()->name,
            ],
            'description' => '3Gites-1975 Class Donation',
            'receipt_email' => auth()->user()->email,
        ]);

        // Store the payment intent ID
        $donation->update(['stripe_payment_id' => $intent->id]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'donation_id'   => $donation->id,
        ]);
    }

    /**
     * POST /donate/confirm  (called after Stripe.js confirms payment)
     * Mark donation as completed in our DB.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_intent_id' => ['required', 'string'],
            'donation_id'       => ['required', 'integer'],
        ]);

        // Verify with Stripe that payment actually succeeded
        $intent = PaymentIntent::retrieve($validated['payment_intent_id']);

        if ($intent->status !== 'succeeded') {
            return redirect()->route('donate.index')
                ->with('error', 'Payment was not completed. Please try again.');
        }

        $donation = Donation::findOrFail($validated['donation_id']);

        // Security: ensure this donation belongs to the current user
        if ($donation->user_id !== auth()->id()) {
            abort(403);
        }

        $donation->update(['status' => 'completed']);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($donation)
            ->log("Donation completed: \${$donation->amount}");

        return redirect()->route('donate.thankyou')
            ->with('donation', $donation);
    }

    /**
     * GET /donate/thank-you
     */
    public function thankYou(Request $request): View
    {
        // Donation passed via session flash
        $donation = session('donation');
        return view('donate.thank-you', compact('donation'));
    }
}
