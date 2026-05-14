@props(['unsubscribeUrl' => null, 'subject' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f3f4f6; color: #111827; line-height: 1.6; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,.07); }
        .header { background: #DC2626; padding: 28px 40px; text-align: center; }
        .header a { color: #ffffff; font-size: 22px; font-weight: 900; text-decoration: none; letter-spacing: -0.5px; }
        .body { padding: 40px; }
        h1 { font-size: 22px; font-weight: 800; color: #111827; margin-bottom: 16px; }
        p { font-size: 15px; color: #374151; margin-bottom: 16px; }
        .btn { display: inline-block; padding: 14px 32px; background: #DC2626; color: #ffffff !important; font-size: 15px; font-weight: 700; border-radius: 10px; text-decoration: none; margin: 8px 0; }
        .btn:hover { background: #b91c1c; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 28px 0; }
        .small { font-size: 13px; color: #9ca3af; }
        .footer { background: #f9fafb; padding: 24px 40px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; margin-bottom: 6px; }
        .footer a { color: #DC2626; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        </div>
        <div class="body">
            {{ $slot }}
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            @if($unsubscribeUrl)
            <p><a href="{{ $unsubscribeUrl }}">Unsubscribe</a></p>
            @endif
        </div>
    </div>
</body>
</html>
