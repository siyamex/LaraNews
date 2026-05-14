@php
    $zone = \App\Models\AdZone::where('placement', $placement)->where('is_active', true)->first();
    if (! $zone) return;
    $ad = $zone->ads()->where('is_active', true)->inRandomOrder()->first();
    if (! $ad) return;
@endphp

<div class="ad-zone" data-zone="{{ $placement }}" data-ad="{{ $ad->id }}">
    @if($ad->type === 'html')
        {!! $ad->content !!}
    @elseif($ad->type === 'adsense')
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ $ad->adsense_client }}"
             data-ad-slot="{{ $ad->adsense_slot }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    @elseif($ad->type === 'image')
        <a href="{{ $ad->click_url }}" target="_blank" rel="noopener sponsored">
            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->name }}"
                 class="rounded-lg w-full" loading="lazy">
        </a>
    @endif
</div>
