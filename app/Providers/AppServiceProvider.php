<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')){
            if (Cache::get('general_setting') == null){
                $setting = Setting::query()->where('type', 'general')->first();
                if ($setting){
                    Cache::set('general_setting', $setting->settings_info);
                }
            }

            if (Cache::get('app_setting') == null){
                $setting = Setting::query()->where('type', 'app_setting')->first();
                if ($setting){
                    Cache::set('app_setting', $setting->settings_info);
                }
            }
        }
    }
}
