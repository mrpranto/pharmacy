<?php

namespace App\Services\Setting;

use App\Models\Setting;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingServices extends BaseServices
{
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * @return mixed
     */
    public function getSetting(): mixed
    {
        $setting = $this->model
            ->newQuery()
            ->where('type', request('type'))
            ->first();

        if ($setting){
            Artisan::call('cache:clear');

            if (request()->filled('general')){
                Cache::set('general_setting', $setting->settings_info);
            }

            return  $setting->settings_info;
        }

        return (object) [];
    }

    /**
     * @return RedirectResponse
     */
    public function storeSetting(): RedirectResponse
    {
        $settingData = request()->except('_token');

        foreach ($settingData['setting'] ?? [] as $key => $setting) {

            $existSetting = $this->model->newQuery()->where('type', $key)->first();

            if ($existSetting) {

                $existSetting->fill([
                    'type' => $key,
                    'settings_info' => $setting
                ])->save();

            } else {

                $this->model
                    ->fill([
                        'type' => $key,
                        'settings_info' => $setting
                    ])->save();
            }
        }

        return redirect()->back()->with('success', 'Setting created successful.');
    }
}
