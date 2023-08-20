<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
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
}
