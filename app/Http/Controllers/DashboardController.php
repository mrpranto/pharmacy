<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
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
            Artisan::call('cache:clear');
        }
        return redirect()->back();
    }
}
