<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><![CDATA[{{ config('app.name') }}]]></title>
        <link>{{ url('/') }}</link>
        <atom:link href="{{ request()->url() }}" rel="self" type="application/rss+xml"/>
        <description><![CDATA[{{ config('app.name') }} — Maldives News]]></description>
        <language>{{ $locale }}</language>
        <lastBuildDate>{{ now()->toRfc1123String() }}</lastBuildDate>
        <generator>LaraNews</generator>

        @foreach($posts as $post)
        @php $t = $post->translation($locale); @endphp
        @if($t && $t->slug)
        <item>
            <title><![CDATA[{{ $t->title }}]]></title>
            <link>{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}</link>
            <guid isPermaLink="true">{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}</guid>
            <pubDate>{{ $post->published_at?->toRfc1123String() }}</pubDate>
            @if($post->user)<author><![CDATA[{{ $post->user->email }} ({{ $post->user->name }})]]></author>@endif
            @if($post->category)<category><![CDATA[{{ $post->category->getName($locale) }}]]></category>@endif
            @if($t->excerpt)<description><![CDATA[{{ $t->excerpt }}]]></description>@endif
            @if($post->featured_image)<enclosure url="{{ asset('storage/'.$post->featured_image) }}" type="image/jpeg"/>@endif
            <content:encoded><![CDATA[{!! strip_tags($t->content) !!}]]></content:encoded>
        </item>
        @endif
        @endforeach
    </channel>
</rss>
