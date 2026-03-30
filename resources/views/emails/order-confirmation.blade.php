<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: Georgia, serif; color: #1e293b; background: #f8fafc; margin: 0; padding: 0; }
    .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
    .header  { background: #0f172a; padding: 32px 40px; text-align: center; }
    .org-name { color: #fff; font-size: 22px; font-weight: bold; margin: 8px 0 0; }
    .subtitle { color: #f59e0b; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; }
    .body    { padding: 36px 40px; }
    h2       { color: #0f172a; font-size: 20px; margin: 0 0 16px; }
    p        { font-size: 15px; line-height: 1.7; color: #475569; margin: 0 0 16px; }
    table    { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th       { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; padding: 6px 0; border-bottom: 2px solid #e2e8f0; }
    td       { padding: 10px 0; font-size: 14px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .total   { font-weight: bold; color: #0f172a; font-size: 16px; }
    .footer  { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div style="width:52px;height:52px;background:#f59e0b;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px;">
            <span style="color:#0f172a;font-weight:bold;font-size:16px;">3G</span>
        </div>
        <p class="org-name">3Gites-1975</p>
        <p class="subtitle">Order Confirmation</p>
    </div>

    <div class="body">
        <h2>Order #{{ $order->id }} Confirmed!</h2>
        <p>Hi {{ $order->user->name }}, thank you for your order. We'll get it shipped to you soon.</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align:center">Qty</th>
                    <th style="text-align:right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product?->name ?? 'Product' }}</td>
                        <td style="text-align:center">{{ $item->quantity }}</td>
                        <td style="text-align:right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align:right;font-size:13px;color:#64748b;padding-top:14px;">Order Total</td>
                    <td class="total" style="text-align:right;padding-top:14px;">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size:13px;color:#94a3b8;">
            Questions about your order? Reply to this email or contact us at
            <a href="mailto:{{ config('mail.from.address') }}" style="color:#d97706;">{{ config('mail.from.address') }}</a>.
        </p>
    </div>

    <div class="footer">
        <p>3Gites-1975 · <a href="{{ config('app.url') }}" style="color:#d97706;">{{ config('app.url') }}</a></p>
        <p>Reference: #{{ $order->id }}</p>
    </div>
</div>
</body>
</html>
