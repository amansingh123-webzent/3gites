<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Refund;
use Stripe\Stripe;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $orders = Order::with(['user', 'items'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $counts = [
            'all'      => Order::count(),
            'pending'  => Order::where('status', 'pending')->count(),
            'paid'     => Order::where('status', 'paid')->count(),
            'failed'   => Order::where('status', 'failed')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts', 'status'));
    }

    public function show(Order $order): View
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function refund(Order $order): RedirectResponse
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Only paid orders can be refunded.');
        }

        try {
            // Get payment intent from Checkout Session
            $session = \Stripe\Checkout\Session::retrieve($order->stripe_payment_id);
            
            // Debug: Log session details
            \Log::info('Refund attempt for order ' . $order->id . ', session: ' . json_encode([
                'session_id' => $session->id,
                'payment_intent' => $session->payment_intent,
                'payment_status' => $session->payment_status,
            ]));
            
            // Check if payment_intent exists and payment was actually completed
            if (!$session->payment_intent || $session->payment_status !== 'paid') {
                // Mark as refunded in database even if Stripe doesn't have a payment
                $order->update(['status' => 'refunded']);
                
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($order)
                    ->log("Order #{$order->id} refunded (no actual payment to refund)");

                return back()->with('success', "Order #{$order->id} has been refunded. No actual payment was found to process through Stripe.");
            }
            
            // Process actual refund
            Refund::create(['payment_intent' => $session->payment_intent]);

            $order->update(['status' => 'refunded']);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($order)
                ->log("Order #{$order->id} refunded");

            return back()->with('success', "Order #{$order->id} has been refunded.");
        } catch (\Exception $e) {
            \Log::error('Refund failed for order ' . $order->id . ': ' . $e->getMessage());
            return back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }
}
