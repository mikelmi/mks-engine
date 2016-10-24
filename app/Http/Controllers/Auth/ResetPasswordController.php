<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\SiteController;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Settings $settings, LanguageRepository $languageRepository)
    {
        parent::__construct($settings, $languageRepository);

        $this->middleware('guest');
        $this->middleware('auth.ability');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $this->seo()->setTitle(trans('auth.Reset Password'));

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
