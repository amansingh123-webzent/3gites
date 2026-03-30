<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body{font-family:Georgia,serif;color:#1e293b;background:#f8fafc;margin:0;padding:0}
.wrap{max-width:540px;margin:36px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.08)}
.hdr{background:#0f172a;padding:28px 36px;text-align:center}
.org{color:#fff;font-size:18px;font-weight:bold;margin:8px 0 2px}
.sub{color:#f59e0b;font-size:11px;letter-spacing:2px;text-transform:uppercase}
.body{padding:32px 36px}
p{font-size:15px;line-height:1.75;color:#475569;margin:0 0 16px}
.foot{background:#f8fafc;border-top:1px solid #e2e8f0;padding:18px 36px;text-align:center;font-size:12px;color:#94a3b8}
</style></head>
<body>
<div class="wrap">
    <div class="hdr">
        <div style="width:46px;height:46px;background:#f59e0b;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px">
            <span style="color:#0f172a;font-weight:bold;font-size:15px">3G</span>
        </div>
        <p class="org">3Gites-1975</p>
        <p class="sub">Message from the Administrator</p>
    </div>
    <div class="body">
        <p>Dear {{ $recipient->name }},</p>
        <div style="white-space:pre-line">{{ $body }}</div>
        <p style="margin-top:24px;font-size:13px;color:#94a3b8">
            — The 3Gites-1975 Administration Team
        </p>
    </div>
    <div class="foot">
        <p>3Gites-1975 · Class of 1975 · <a href="{{ config('app.url') }}" style="color:#d97706">{{ config('app.url') }}</a></p>
        <p>You are receiving this as a registered member.</p>
    </div>
</div>
</body>
</html>
