<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Танзими забони интерфейс (tg ё ru) аз Session ё Cookie.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost', '::1'], true);

        if (
            $request->header('X-Forwarded-Proto') === 'https'
            || $request->isSecure()
            || (!$isLocalHost && str_starts_with((string) config('app.url'), 'https://'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } else {
            \Illuminate\Support\Facades\URL::forceScheme('http');
        }

        $locale = session('locale');

        if (!in_array($locale, ['tg', 'ru'], true)) {
            $cookieLocale = $request->cookie('locale');
            if (in_array($cookieLocale, ['tg', 'ru'], true)) {
                $locale = $cookieLocale;
            }
        }

        if (in_array($locale, ['tg', 'ru'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
