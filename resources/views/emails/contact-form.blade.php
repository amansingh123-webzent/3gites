<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body{font-family:Arial,sans-serif;color:#1e293b;background:#f8fafc;margin:0;padding:0}
.wrap{max-width:520px;margin:30px auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08)}
.hdr{background:#0f172a;padding:24px 32px;color:#f59e0b;font-weight:bold;font-size:16px}
.body{padding:28px 32px}
.field{margin-bottom:16px}
.label{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#94a3b8;margin-bottom:4px}
.value{font-size:15px;color:#334155}
.msg{background:#f1f5f9;border-radius:8px;padding:16px;font-size:14px;line-height:1.7;color:#475569;white-space:pre-wrap}
</style></head>
<body>
<div class="wrap">
    <div class="hdr">New Contact Form Message — 3Gites.org</div>
    <div class="body">
        <div class="field">
            <div class="label">From</div>
            <div class="value">{{ $data['name'] }} &lt;{{ $data['email'] }}&gt;</div>
        </div>
        <div class="field">
            <div class="label">Subject</div>
            <div class="value">{{ $data['subject'] }}</div>
        </div>
        <div class="field">
            <div class="label">Message</div>
            <div class="msg">{{ $data['message'] }}</div>
        </div>
    </div>
</div>
</body>
</html>
