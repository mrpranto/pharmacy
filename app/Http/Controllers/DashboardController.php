<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function home(): View
    {
        return view('dashboard');
    }
}
