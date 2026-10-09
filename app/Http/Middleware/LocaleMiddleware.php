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
        $isStudent = auth()->check() && auth()->user()->hasRole('mahasiswa');

        // Dynamic HTML content post-processing translations for Dosen portal
        if (method_exists($response, 'getContent') && str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
            if ($request->is('dosen*') || $request->is('mahasiswa*') || $request->is('admin*') || $request->is('help-center*') || ($isStudent && $request->is('notifikasi*'))) {
                $content = $response->getContent();
                if ($locale === 'en') {
                    $translations = include base_path('lang/en_translations.php');
                } else {
                    $translations = include base_path('lang/id_translations.php');
                }
                
                // Translate common visible attributes only in the student portal.
                $pattern = '/(<[^>]+>)|([^<]+)/';
                $content = preg_replace_callback($pattern, function($matches) use ($translations, $isStudent) {
                    if (isset($matches[1]) && $matches[1] !== '') {
                        if (!$isStudent) {
                            return $matches[1];
                        }

                        return preg_replace_callback('/\b(placeholder|title|aria-label|alt)=([\'"])(.*?)\2/is', function ($attribute) use ($translations) {
                            return $attribute[1] . '=' . $attribute[2] . strtr($attribute[3], $translations) . $attribute[2];
                        }, $matches[1]);
                    }
                    if (isset($matches[2]) && $matches[2] !== '') {
                        $text = $matches[2];
                        
                        // Handle whitespaces that break exact match
                        $trimmed = trim($text);
                        if ($trimmed === '') {
                            return $text; // It's just whitespace
                        }
                        
                        // Extract leading and trailing whitespaces
                        preg_match('/^(\s*)/', $text, $leadingMatches);
                        preg_match('/(\s*)$/', $text, $trailingMatches);
                        $leading = $leadingMatches[1] ?? '';
                        $trailing = $trailingMatches[1] ?? '';
                        
                        // Attempt translation on trimmed text, otherwise try strtr for partials
                        $translated = $translations[$trimmed] ?? strtr($trimmed, $translations);
                        
                        return $leading . $translated . $trailing;
                    }
                    return '';
                }, $content);
                
                $response->setContent($content);
            }
        }

        return $response;
    }
}
