<?php

namespace App\Http\Middleware;

use Closure;

class Auth
{
    public function handle($request, Closure $next)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
