<?php

namespace App\Http\Controllers;

use App\Services\DashboardServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * @param DashboardServices $dashboardServices
     */
    public function __construct(DashboardServices $dashboardServices)
    {
        $this->services = $dashboardServices;
    }

    /**
     * @return View
     */
    public function home(): View
    {
        return view('dashboard');
    }

    /**
     * @param $lang
     * @return RedirectResponse
     */
    public function setLang($lang): RedirectResponse
    {
        if (in_array($lang, ['en', 'bn'])) {
            Session::put('lang', $lang);
        }
        return redirect()->back();
    }

    public function setColorMode($mode): RedirectResponse
    {
        if (in_array($mode, ['dark', 'white'])) {
            Session::put('color-mode', $mode);
        }
        return redirect()->back();
    }

    /**
     * @return View
     */
    public function profile(): View
    {
        return view('pages.profile.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        return $this->services
            ->validateProfileUpdate($request)
            ->updateProfile($request);
    }
}
