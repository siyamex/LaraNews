<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($posts as $post)
    @foreach(['dv', 'en'] as $locale)
    @php $t = $post->translation($locale); @endphp
    @if($t && $t->slug)
    <url>
        <loc>{{ route('news.show', ['locale' => $locale, 'slug' => $t->slug]) }}</loc>
        <lastmod>{{ $post->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($post->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/'.$post->featured_image) }}</image:loc>
            <image:title><![CDATA[{{ $t->title }}]]></image:title>
        </image:image>
        @endif
    </url>
    @endif
    @endforeach
    @endforeach
</urlset>
