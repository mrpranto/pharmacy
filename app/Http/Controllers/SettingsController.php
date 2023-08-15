<?php

namespace App\Http\Controllers;

use App\Services\Setting\SettingServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function __construct(SettingServices $settingServices)
    {
        $this->services = $settingServices;
    }

    public function getSetting(): View
    {
        Gate::authorize('app.setting');

        return view('pages.settings.settings',['setting' => $this->services->getSetting()]);
    }

    /**
     * Store setting info.
     *
     * @return RedirectResponse
     */
    public function storeSetting(): RedirectResponse
    {
         return $this->services->storeSetting();
    }

    public function backup()
    {
        return $this->services->backup();
    }
}
