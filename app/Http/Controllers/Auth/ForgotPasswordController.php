<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\SiteController;
use App\Services\Settings;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Settings $settings)
    {
        parent::__construct($settings);

        $this->middleware('guest');
        $this->middleware('auth.ability');
    }

    public function showLinkRequestForm()
    {
        $this->seo()->setTitle(trans('auth.Reset Password'));

        return view('auth.passwords.email');
    }
}
