<x-emails.layout>
    <h1>Confirm your subscription</h1>

    <p>Hi{{ $subscriber->name ? ', '.$subscriber->name : '' }}!</p>

    <p>Thank you for subscribing to <strong>{{ config('app.name') }}</strong>. Please confirm your email address to start receiving our newsletter.</p>

    <p style="text-align:center; margin: 28px 0;">
        <a href="{{ route('newsletter.confirm', $subscriber->token) }}" class="btn">Confirm Subscription</a>
    </p>

    <hr class="divider">

    <p class="small">
        If you did not subscribe to this newsletter, you can safely ignore this email — you will not receive any further emails from us.<br><br>
        Or copy this link into your browser:<br>
        {{ route('newsletter.confirm', $subscriber->token) }}
    </p>
</x-emails.layout>
