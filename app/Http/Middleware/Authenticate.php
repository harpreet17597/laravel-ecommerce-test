<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('admin/*')) {
                return redirect('/admin/login');
            }
            return redirect('/');
        }
    }

    // public function handle($request, Closure $next)
    // {
    //     if (!auth()->check()) {
    //         // User is not authenticated, redirect to the login page
    //         return redirect('signin');
    //         // return redirect()->route('signin')->with('error', 'Please log in to access this page.');
    //     }

    //     // User is authenticated, allow the request to proceed
    //     return $next($request);
    // }
}
