<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {

        if (auth()->check()) {
            $user_type = auth()->user()->user_type;
            switch ($user_type) {
                case User::CLIENT_USER_TYPE:
                    return redirect(RouteServiceProvider::HOME);
                case User::ADMIN_USER_TYPE:
                    return redirect(RouteServiceProvider::ADMIN_HOME);
            }
        }

        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         dd('dd');
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        return $next($request);
    }
}
