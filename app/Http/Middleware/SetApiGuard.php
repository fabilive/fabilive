<?php

namespace App\Http\Middleware;

use Closure;

class SetApiGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        config(['auth.defaults.guard' => 'api']);

        return $next($request);
    }
}
