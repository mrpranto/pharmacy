<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\BaseServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthServices extends BaseServices
{
    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Login check and process user information.
     *
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function processLogin(): RedirectResponse
    {
        request()->validate([
            'email_or_phone' => 'required',
            'password' => 'required|string|min:8'
        ]);

        $this->model = $this->model
            ->newQuery()
            ->where('email', request()->get('email_or_phone'))
            ->orWhere('phone_number', request()->get('email_or_phone'))
            ->first();

        if ($this->model){
            $credentials = ['phone_number' => $this->model->phone_number, 'password' => request()->get('password')];
            if (Auth::attempt($credentials)){

                Session::put('lang', 'en');

                return redirect()->intended('/home')->with('success', 'Your login successful.');
            }else{
                return redirect()->route('login')->withErrors(['password' => 'Incorrect password.']);
            }
        }else{
            return redirect()->route('login')->withErrors(['email_or_phone' => 'Invalid email or phone number.']);
        }
    }

    /**
     * Process logout.
     *
     * @return void
     */
    public function processLogout(): void
    {
        Auth::logout();
    }

    /**
     * @return $this
     */
    public function validateChangePassword(): static
    {
        request()->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('Current password does not match.');
                }
            }],
            'new_password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required|min:8',
        ]);

        return $this;
    }


    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function changePassword(): void
    {
        Auth::user()->update([
            'password' => bcrypt(request()->get('new_password'))
        ]);

        Auth::logout();
    }

}
