<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Cache::rememberForever('translations', function () {
            $translations = collect();

            $locale = app()->getLocale();

            $translations[$locale] = [
                'php' => $this->phpTranslations($locale),
                'json' => $this->jsonTranslations($locale),
            ];

            return $translations;
        });
    }

    /**
     * @param $locale
     * @return Collection
     */
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
