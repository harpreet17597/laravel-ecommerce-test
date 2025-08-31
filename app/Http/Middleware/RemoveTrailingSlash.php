<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveTrailingSlash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        if ($uri !== '/' && preg_match('/.+\/$/', $uri)) {
            $newUri = rtrim($uri, '/');
            return redirect($newUri, 301);
        }

        return $next($request);
    }
}
