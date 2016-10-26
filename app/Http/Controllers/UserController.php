<?php

namespace App\Http\Controllers;


use App\Services\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class UserController extends SiteController
{
    public function __construct(Settings $settings)
    {
        parent::__construct($settings);

        $this->middleware('auth', ['except' => ['info']]);
    }

    public function profile(Request $request, $id = null)
    {
        $canEdit = false;

        if ($id) {
            $user = User::findOrFail($id);
        } else {
            $user = $request->user();
            $canEdit = true;
        }

        $this->seo()->setTitle($user->name);

        return view('user.profile', compact('user', 'canEdit'));
    }

    public function info(Request $request, $id)
    {
        return $this->profile($request, $id);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        $this->seo()->setTitle(trans('user.Profile'));

        return view('user.edit', compact('user'));
    }

    public function save(Request $request)
    {
        $user = $request->user();

        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required'
        ];

        $changePassword = $request->has('change_password');

        if ($changePassword) {
            $rules['password_new'] = 'required|min:6|confirmed';
            $rules['password_current'] = 'required';
        }

        $validator = validator($request->all(), $rules);

        if ($changePassword) {
            $validator->after(function (Validator $validator) use ($user) {

                $current_password = array_get($validator->getData(), 'password_current');

                if ($current_password && !\Hash::check($current_password, $user->password)) {
                    $validator->errors()->add('password_current', trans('validation.invalid_value', ['attribute' => 'password_current']));
                }
            });
        }

        $validator->validate();

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($changePassword) {
            $user->password = bcrypt($request->input('password_new'));
        }

        $user->save();

        $this->flashSuccess(trans('general.Saved'));

        return redirect()->route('user.profile');
    }
}