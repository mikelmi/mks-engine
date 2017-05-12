<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\SiteController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $cacheable = false;

    protected function init()
    {
        $this->middleware('guest');
        $this->middleware('auth.ability');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $this->seo()->setTitle(__('auth.Reset Password'));

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
