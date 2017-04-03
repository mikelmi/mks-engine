<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;

class CheckActiveUser
{
    public function handle(Request $request, \Closure $next, $guard = null)
    {
        if ($user = $request->user()) {
            if (!$user->active) {
                \Auth::logout();

                $message = __('user.blocked');
                $request->session()->flash('message', $message);
                $request->session()->flash('alert-class', 'alert-danger');

                if ($request->ajax() || $request->wantsJson()) {
                    $response = response('Unauthorized.', 401);
                    $response->headers->set('X-Flash-Message', urlencode($message));
                    $response->headers->set('X-Flash-Message-Type', 'danger');

                    return $response;
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}