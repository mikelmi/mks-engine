<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserActivated;
use App\Events\UserRegistered;
use App\Http\Controllers\SiteController;
use App\Repositories\LanguageRepository;
use App\Services\CaptchaManager;
use App\Services\Settings;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
    public function __construct(Settings $settings, LanguageRepository $languageRepository)
    {
        parent::__construct($settings, $languageRepository);

        $this->middleware('guest');
        $this->middleware('register.ability');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /** @var CaptchaManager $captcha */
        $captcha = app(CaptchaManager::class);

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $rules = array_merge($rules, $captcha->rules());

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        if (settings('users.verification')) {
            $user->activation_token = $user->generateToken();
            $user->active = false;
        } else {
            $user->active = true;
        }

        $user->save();

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        
        $redirectPath = $this->redirectPath();

        if (!settings('users.verification')) {
            $this->guard()->login($user);
        } else {
            $this->flashNotice(trans('auth.verification_info'));
        }

        event(new UserRegistered($user));

        return redirect($redirectPath);
    }

    public function activate($token)
    {
        $user = User::where('active', false)->where('activation_token', $token)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $user->active = true;
        $user->activation_token = null;
        $user->save();

        event(new UserActivated($user));

        $this->flashSuccess(trans('auth.verification_success'));

        return redirect('/login');
    }
}
