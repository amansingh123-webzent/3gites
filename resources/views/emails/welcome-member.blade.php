<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Georgia, serif; color: #1e293b; background: #f8fafc; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #0f172a; padding: 32px 40px; text-align: center; }
        .logo-circle { width: 56px; height: 56px; background: #f59e0b; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .logo-text { color: #0f172a; font-weight: bold; font-size: 18px; }
        .org-name { color: #fff; font-size: 22px; font-weight: bold; margin: 0; }
        .subtitle { color: #f59e0b; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .body { padding: 40px; }
        .greeting { font-size: 20px; font-weight: bold; color: #0f172a; margin-bottom: 16px; }
        p { font-size: 15px; line-height: 1.7; color: #475569; margin: 0 0 16px; }
        .credentials { background: #f1f5f9; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
        .cred-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 4px; }
        .cred-value { font-size: 16px; font-weight: bold; color: #0f172a; word-break: break-all; }
        .btn { display: inline-block; background: #0f172a; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: bold; margin: 8px 0 24px; }
        .warning { font-size: 13px; color: #dc2626; background: #fef2f2; border-radius: 6px; padding: 12px 16px; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo-circle">
                <span class="logo-text">3G</span>
            </div>
            <p class="org-name">3Gites-1975</p>
            <p class="subtitle">Class of 1975</p>
        </div>

        <div class="body">
            <p class="greeting">Welcome, {{ $user->name }}!</p>

            <p>Your account on the <strong>3Gites-1975</strong> class reunion portal has been created. We're so glad to have you back in the fold.</p>

            <p>Here are your login credentials:</p>

            <div class="credentials">
                <div class="cred-label">Email Address</div>
                <div class="cred-value">{{ $user->email }}</div>
                <div style="margin-top: 16px;">
                    <div class="cred-label">Temporary Password</div>
                    <div class="cred-value">{{ $temporaryPassword }}</div>
                </div>
            </div>

            <div class="warning">
                ⚠️ <strong>Please change your password</strong> the first time you log in.
                You will be prompted to do so automatically.
            </div>

            <br>

            <a href="{{ config('app.url') }}/login" class="btn">Sign In to the Portal →</a>

            <p style="font-size: 13px; color: #94a3b8;">
                If you have any trouble logging in, reply to this email or contact the site administrator at
                <a href="mailto:{{ config('mail.from.address') }}" style="color: #d97706;">{{ config('mail.from.address') }}</a>.
            </p>
        </div>

        <div class="footer">
            <p>3Gites-1975 Class Reunion Portal · <a href="{{ config('app.url') }}" style="color: #d97706;">{{ config('app.url') }}</a></p>
            <p>This is a private community. Please do not share your login credentials.</p>
        </div>
    </div>
</body>
</html>
