<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\SiteController;
use App\Services\Settings;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Settings $settings)
    {
        parent::__construct($settings);

        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        $this->seo()->setTitle(trans('auth.Sign In'));

        return view('auth.login');
    }
}
