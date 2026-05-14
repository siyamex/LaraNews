<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED_LOCALES = ['dv', 'en'];
    private const DEFAULT_LOCALE = 'dv';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->detectLocale($request);

        App::setLocale($locale);
        Session::put('locale', $locale);

        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('Content-Language', $locale);
        }

        return $response;
    }

    private function detectLocale(Request $request): string
    {
        // 1. URL segment (/{locale}/...)
        $urlLocale = $request->segment(1);
        if (in_array($urlLocale, self::SUPPORTED_LOCALES)) {
            return $urlLocale;
        }

        // 2. Session
        $sessionLocale = Session::get('locale');
        if ($sessionLocale && in_array($sessionLocale, self::SUPPORTED_LOCALES)) {
            return $sessionLocale;
        }

        // 3. Auth user preference
        if (auth()->check() && in_array(auth()->user()->locale, self::SUPPORTED_LOCALES)) {
            return auth()->user()->locale;
        }

        // 4. Accept-Language header
        $acceptLanguage = $request->header('Accept-Language', '');
        foreach (explode(',', $acceptLanguage) as $lang) {
            $langCode = strtolower(trim(explode(';', $lang)[0]));
            $twoChar = substr($langCode, 0, 2);
            if (in_array($twoChar, self::SUPPORTED_LOCALES)) {
                return $twoChar;
            }
        }

        return self::DEFAULT_LOCALE;
    }
}
