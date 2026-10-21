<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'id';

        if (auth()->check()) {
            $locale = session('locale', auth()->user()->language ?? 'id');
            if (session('locale') !== $locale) {
                session(['locale' => $locale]);
            }
            if (auth()->user()->language !== $locale) {
                try {
                    auth()->user()->forceFill(['language' => $locale])->saveQuietly();
                } catch (\Throwable $e) {}
            }
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        App::setLocale($locale);
        \Carbon\Carbon::setLocale($locale);

        $response = $next($request);

        // Keep raw cookie in sync for Google Translate
        if ($locale === 'en') {
            if (!isset($_COOKIE['googtrans']) || $_COOKIE['googtrans'] !== '/id/en') {
                @setcookie('googtrans', '/id/en', time() + (86400 * 365), '/');
                $_COOKIE['googtrans'] = '/id/en';
            }
        } else {
            if (isset($_COOKIE['googtrans'])) {
                @setcookie('googtrans', '', time() - 3600, '/');
                $host = $request->getHost();
                if ($host && $host !== 'localhost') {
                    @setcookie('googtrans', '', time() - 3600, '/', '.' . $host);
                }
                unset($_COOKIE['googtrans']);
            }
        }

        return $response;
    }
}
