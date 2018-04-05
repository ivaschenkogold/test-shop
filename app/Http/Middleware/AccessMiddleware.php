<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Role;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role_id == 1) {
            return $next($request);
        } else {
            if ($request->segment(3)) {
                if (stripos($request->segment(3), '-') !== false) {
                    $func = explode('-', $request->segment(3))[1];
                } else {
                    $func = $request->segment(3);
                }
            } else {
                $func = 'index';
            }

            $module = $request->segment(2);

            if (stripos($func, '_') !== false) {
                $probably_functions = explode('_', $func);

                for ($i = 0; $i < count($probably_functions); $i++) {
                    if(Role::hasAccess($module, $probably_functions[$i])){
                        return $next($request);
                    }
                }

            } elseif (Role::hasAccess($module, $func)) {
                return $next($request);
            }

            return redirect()->back();
        }
    }
}
