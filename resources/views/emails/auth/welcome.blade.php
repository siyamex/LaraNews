<x-emails.layout>
    <h1>Welcome to {{ config('app.name') }}, {{ $user->name }}!</h1>

    <p>Your account has been created successfully. You can now log in and start exploring the latest news.</p>

    <p style="text-align:center; margin: 28px 0;">
        <a href="{{ config('app.url') }}" class="btn">Start Reading</a>
    </p>

    <hr class="divider">

    <p class="small">
        Your account details:<br>
        Email: {{ $user->email }}<br>
        Username: {{ $user->username }}
    </p>

    <p>If you did not create this account, please ignore this email or <a href="mailto:{{ config('mail.from.address') }}">contact us</a>.</p>
</x-emails.layout>
