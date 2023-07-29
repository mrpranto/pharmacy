<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthController extends Controller
{
    public function __construct(AuthServices $authServices)
    {
        $this->services = $authServices;
    }

    /**
     * @return View|RedirectResponse
     */
    public function loginPage(): View|RedirectResponse
    {
        if (auth()->check()){
            return redirect()->to('home');
        }
        return view('pages.auth.login');
    }

    /**
     * Process login
     *
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function processLogin(): RedirectResponse
    {
        return $this->services->processLogin();
    }

    /**
     * Process logout.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->services->processLogout();

        return redirect()->route('login')->with('success', 'Logout Successful');
    }
}
