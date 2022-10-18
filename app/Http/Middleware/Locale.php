<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = self::getLocale();

        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    public static function getLocale()
    {
        $locale = request()->segment(2);

        if (!empty($locale) && in_array($locale, config('app.available_locales'), true)) {
            return $locale;
        }

        return App::currentLocale();
    }
}
