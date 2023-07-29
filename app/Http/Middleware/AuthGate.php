<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthGate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user)
        {
            $permissions =  cache()->rememberForever('permissions', function (){
                return Permission::all();
            });

            foreach ($permissions as $key => $permission)
            {
                Gate::define($permission->slug, function ($user) use($permission) {

                    return $this->checkHasPermissions($user, $permission->slug);
                });
            }
        }

        return $next($request);
    }

    public function checkHasPermissions($user, $slug): bool
    {
        $permission = collect($user->rolePermissions)->where('slug', $slug)->count();

        return $permission ? true : false;
    }
}
