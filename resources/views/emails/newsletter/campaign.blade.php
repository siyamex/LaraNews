<x-emails.layout :unsubscribeUrl="$subscriber->unsubscribe_url">
    <h1>{{ $campaign->subject }}</h1>

    @if($campaign->preheader)
    <p style="color:#6b7280; font-style:italic;">{{ $campaign->preheader }}</p>
    <hr class="divider">
    @endif

    <div style="line-height:1.8;">
        {!! $campaign->content !!}
    </div>

    <hr class="divider">

    <p class="small">
        You are receiving this email because you subscribed to {{ config('app.name') }} newsletter.<br>
        <a href="{{ $subscriber->unsubscribe_url }}">Unsubscribe</a> &nbsp;|&nbsp;
        <a href="{{ config('app.url') }}">Visit our website</a>
    </p>
</x-emails.layout>
