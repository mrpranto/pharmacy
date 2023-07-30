<?php

namespace App\Http\Controllers;

use App\Services\Setting\SettingServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function __construct(SettingServices $settingServices)
    {
        $this->services = $settingServices;
    }

    public function getSetting(): View
    {
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
}
