<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class AdminMiddleware
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!Auth::check()) {
                throw new \Exception('Unauthorized. Please login first.');
            }

            if (Auth::user()->user_type !== User::ADMIN_USER_TYPE) {
                throw new \Exception('Forbidden. You are not an admin.');
            }

            return $next($request);
        } catch (\Exception $exception) {
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
