<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Picks the response language for the Partner API from the Accept-Language header.
 */
class SetApiLocale
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales.supported', []));

        $locale = $request->hasHeader('Accept-Language')
            ? $request->getPreferredLanguage($supported)
            : null;

        app()->setLocale(in_array($locale, $supported, true) ? $locale : config('app.locale'));

        $response = $next($request);
        $response->headers->set('Content-Language', app()->getLocale());

        return $response;
    }
}
