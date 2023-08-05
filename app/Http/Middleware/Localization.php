<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Cache::forget('translations');
        Cache::rememberForever('translations', function () {

            $translations = collect();
            app()->setLocale(session()->get('lang'));
            $locale = app()->getLocale();

            $translations[$locale] = [
                'php' => $this->phpTranslations($locale),
                'json' => $this->jsonTranslations($locale),
            ];

            return $translations;
        });

        return $next($request);
    }
    private function phpTranslations($locale): Collection
    {
        $path = lang_path("$locale");

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        });
    }

    private function jsonTranslations($locale)
    {
        $path = lang_path("$locale.json");

        if (is_string($path) && is_readable($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }
}
