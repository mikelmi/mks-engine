<?php

namespace App\Http\Middleware;

use Closure;

class RegisterAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!settings('users.registration')) {
            abort(404, 'Page not found');
        }

        return $next($request);
    }
}
