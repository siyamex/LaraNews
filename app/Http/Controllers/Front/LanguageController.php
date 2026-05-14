<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $supportedLocales = config('app.supported_locales', ['dv', 'en']);

        if (! in_array($locale, $supportedLocales)) {
            abort(404);
        }

        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        $referer = $request->header('Referer', '/');
        // Replace locale prefix in the referer URL
        $currentLocale = app()->getLocale();
        $newUrl = str_replace("/{$currentLocale}/", "/{$locale}/", $referer);

        return redirect($newUrl !== $referer ? $newUrl : "/{$locale}");
    }
}
