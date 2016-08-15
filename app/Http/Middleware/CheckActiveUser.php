<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15.08.16
 * Time: 19:57
 */

namespace App\Http\Middleware;


use Illuminate\Http\Request;

class CheckActiveUser
{
    public function handle(Request $request, \Closure $next, $guard = null)
    {
        if ($user = $request->user()) {
            if (!$user->active) {
                \Auth::logout();

                $message = trans('user.blocked');
                $request->session()->flash('error', $message);

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