<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Role;
use Illuminate\Support\Facades\View;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && Auth::user()->role_id != 0) {
            $module = $request->segment(2);

            $accesses = Role::getModuleAccesses($module);
            View::share('accesses', $accesses);
            View::share('request_module', $module);
            return $next($request);
        }

        Auth::logout();
        return redirect('/login');
    }
}
