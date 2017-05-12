<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\SiteController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected $cacheable = false;

    protected function init()
    {
        $this->middleware('guest');
        $this->middleware('auth.ability');
    }

    public function showLinkRequestForm()
    {
        $this->seo()->setTitle(__('auth.Reset Password'));

        return view('auth.passwords.email');
    }
}
