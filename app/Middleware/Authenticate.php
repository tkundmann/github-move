<?php

namespace App\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Lang;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        if (Auth::user()->disabled) {
            Auth::logout();
        }

        if (!Auth::user()->confirmed && (Route::currentRouteName() != 'password.change')) {
            return redirect()->route('password.change')->with('message', Lang::get('auth.login.initial_login_reset_password'));
        }

        return $next($request);
    }
}
