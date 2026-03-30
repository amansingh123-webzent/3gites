<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body  { font-family: Georgia, serif; color: #1e293b; background: #f8fafc; margin: 0; padding: 0; }
    .wrap { max-width: 540px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
    .top  { background: #0f172a; padding: 32px 40px; text-align: center; }
    .cake { font-size: 52px; margin-bottom: 10px; }
    .org  { color: #f59e0b; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; }
    .body { padding: 36px 40px; }
    h2    { color: #0f172a; font-size: 22px; margin: 0 0 14px; }
    p     { font-size: 15px; line-height: 1.7; color: #475569; margin: 0 0 16px; }
    .btn  { display: inline-block; background: #f59e0b; color: #0f172a; text-decoration: none;
             padding: 13px 28px; border-radius: 8px; font-weight: bold; font-size: 14px; }
    .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 40px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <div class="cake">🎂</div>
        <p class="org">3Gites-1975 · Class of 1975</p>
    </div>
    <div class="body">
        @if ($isCelebrant)
            {{-- Email TO the birthday person --}}
            <h2>Happy Birthday, {{ $celebrant->name }}! 🎉</h2>
            <p>
                Your classmates from the Class of 1975 are thinking of you today.
                Wishing you a wonderful birthday filled with joy, laughter, and
                great memories of our years together.
            </p>
            <p>
                Log in to the member portal — your classmates may have left you messages!
            </p>
            <a href="{{ config('app.url') }}/board" class="btn">Visit the Message Board →</a>
        @else
            {{-- Email to OTHER members about the celebrant --}}
            <h2>Today is {{ $celebrant->name }}'s Birthday!</h2>
            <p>
                A little reminder: today is your classmate <strong>{{ $celebrant->name }}</strong>'s birthday.
                Why not drop them a message on the class board to wish them well?
            </p>
            <a href="{{ route('members.show', $celebrant) }}" class="btn">
                View {{ $celebrant->name }}'s Profile →
            </a>
        @endif
    </div>
    <div class="foot">
        <p>3Gites-1975 · <a href="{{ config('app.url') }}" style="color:#d97706;">{{ config('app.url') }}</a></p>
        <p>You're receiving this because you are a registered member of the Class of 1975.</p>
    </div>
</div>
</body>
</html>
