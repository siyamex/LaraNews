<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, string $locale, string $slug)
    {
        $page = Page::where('slug', $slug)->where('status', 'published')
            ->with('translations')
            ->firstOrFail();

        $translation = $page->translation($locale);

        $seo = [
            'title'       => $translation?->meta_title ?? $translation?->title . ' — ' . config('app.name'),
            'description' => $translation?->meta_description,
            'og_type'     => 'website',
        ];

        return view('front.page.show', compact('page', 'translation', 'seo', 'locale'));
    }
}
