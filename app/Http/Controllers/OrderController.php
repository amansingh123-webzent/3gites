<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class OrderController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * POST /checkout
     * Build a pending Order, then redirect to Stripe Checkout.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart  = CartController::getCart();
        $items = CartController::hydrateCart($cart);

        if (empty($items)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Validate stock one more time before creating the Stripe session
        foreach ($items as $item) {
            if ($item['product']->stock < $item['quantity']) {
                return redirect()->route('cart.index')
                    ->with('error', "\"{$item['product']->name}\" no longer has enough stock.");
            }
        }

        // Create a pending Order in DB
        $order = DB::transaction(function () use ($items) {
            $total = collect($items)->sum('total');

            $order = Order::create([
                'user_id' => auth()->id(),
                'total'   => $total,
                'status'  => 'pending',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['product']->price,
                ]);
            }

            return $order;
        });

        // Build Stripe line items
        $lineItems = collect($items)->map(fn ($item) => [
            'price_data' => [
                'currency'     => config('cashier.currency', 'usd'),
                'unit_amount'  => (int) round($item['product']->price * 100),
                'product_data' => [
                    'name'        => $item['product']->name,
                    'description' => $item['product']->description ?? null,
                ],
            ],
            'quantity' => $item['quantity'],
        ])->values()->toArray();

        // Create Stripe Checkout Session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'customer_email'       => auth()->user()->email,
            'success_url'          => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('checkout.cancelled'),
            'metadata'             => [
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
            ],
        ]);

        // Store session ID on order for webhook matching
        $order->update(['stripe_payment_id' => $session->id]);

        return redirect($session->url);
    }

    /**
     * GET /checkout/success
     * Stripe redirects here after payment. We show a holding page;
     * the webhook does the actual fulfilment.
     */
    public function success(Request $request): View
    {
        $sessionId = $request->query('session_id');

        $order = Order::where('stripe_payment_id', $sessionId)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->first();

        // Clear the cart now
        session()->forget('cart');

        return view('store.checkout-success', compact('order'));
    }

    /**
     * GET /checkout/cancelled
     * Member cancelled at Stripe — return to cart.
     */
    public function cancelled(): RedirectResponse
    {
        return redirect()->route('cart.index')
            ->with('error', 'Checkout was cancelled. Your cart is still saved.');
    }

    // ── Stripe Webhook ────────────────────────────────────────────────────────

    /**
     * POST /stripe/webhook
     * Handles: checkout.session.completed, payment_intent.succeeded,
     *          checkout.session.expired
     */
    public function webhook(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('cashier.webhook.secret');

        // Skip signature verification for development if no secret is configured
        if (!$secret) {
            $event = json_decode($payload);
        } else {
            try {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (\Exception $e) {
                Log::warning('Stripe webhook signature failure: ' . $e->getMessage());
                return response('Invalid signature', 400);
            }
        }

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'checkout.session.expired'   => $this->handleCheckoutExpired($event->data->object),
            'payment_intent.succeeded'   => $this->handleDonationSucceeded($event->data->object),
            default                      => null,
        };

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $order = Order::where('stripe_payment_id', $session->id)->first();

        if (! $order || $order->status === 'paid') {
            return; // already processed or not found
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);

            // Decrement stock for each item
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        });

        // Send confirmation email immediately
        try {
            Mail::to($order->user->email)
                ->send(new OrderConfirmationMail($order->load('items.product', 'user')));
        } catch (\Exception $e) {
            Log::error('Order confirmation email failed: ' . $e->getMessage());
        }

        activity()
            ->performedOn($order)
            ->log("Order #{$order->id} paid via Stripe Checkout");
    }

    private function handleCheckoutExpired(object $session): void
    {
        Order::where('stripe_payment_id', $session->id)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    private function handleDonationSucceeded(object $intent): void
    {
        // Backup: if the confirm endpoint didn't fire (e.g. browser closed)
        // we still mark the donation completed via webhook
        if (isset($intent->metadata->donation_id)) {
            \App\Models\Donation::where('id', $intent->metadata->donation_id)
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        }
    }
}
