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
            $locale = auth()->user()->language ?? 'id';
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        App::setLocale($locale);

        $response = $next($request);

        // Dynamic HTML content post-processing translations for Dosen portal
        if (method_exists($response, 'getContent') && str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
            if ($request->is('dosen*')) {
                $content = $response->getContent();
                if ($locale === 'en') {
                    $translations = include base_path('lang/en_translations.php');
                } else {
                    $translations = include base_path('lang/id_translations.php');
                }
                
                // Safe text-only rendering translation (ignores HTML tag attributes/paths)
                $pattern = '/(<[^>]+>)|([^<]+)/';
                $content = preg_replace_callback($pattern, function($matches) use ($translations) {
                    if (isset($matches[1]) && $matches[1] !== '') {
                        return $matches[1]; // Return tag attributes untouched
                    }
                    if (isset($matches[2]) && $matches[2] !== '') {
                        return strtr($matches[2], $translations); // Translate text nodes safely
                    }
                    return '';
                }, $content);
                
                $response->setContent($content);
            }
        }

        return $response;
    }
}
