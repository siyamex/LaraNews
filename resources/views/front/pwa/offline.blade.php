<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline — {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
        .card { background: white; border-radius: 1.5rem; padding: 3rem 2rem; text-align: center; max-width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,.1); }
        .icon { font-size: 4rem; margin-bottom: 1.5rem; }
        h1 { font-size: 1.5rem; font-weight: 900; color: #111; margin-bottom: .75rem; }
        p { color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem; }
        a { display: inline-block; padding: .75rem 2rem; background: #DC2626; color: white; font-weight: 600; border-radius: .75rem; text-decoration: none; }
        a:hover { background: #b91c1c; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📡</div>
        <h1>You're Offline</h1>
        <p>No internet connection. Please check your network and try again.</p>
        <a href="javascript:window.location.reload()">Try Again</a>
    </div>
</body>
</html>
