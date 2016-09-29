@extends('layouts.main')

@section('content')
    <h1 class="page-title">@lang('user.Profile')</h1>

    <form method="post" action="{{route('user.save')}}">
        {{ csrf_field() }}

        <div class="form-group row @if($errors->has('name')) has-danger @endif">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Name') </label>
            <div class="col-sm-9">
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" />
                @if($errors->has('name'))
                    <small class="form-control-feedback">{{ $errors->first('name') }}</small>
                @endif
            </div>
        </div>
        <div class="form-group row @if($errors->has('email')) has-danger @endif">
            <label class="col-sm-3 col-form-label text-sm-right"> Email </label>
            <div class="col-sm-9">
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" />
                @if($errors->has('email'))
                    <small class="form-control-feedback">{{ $errors->first('email') }}</small>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 text-sm-right"> @lang('a.Password') </label>
            <div class="col-sm-9">
                <div class="form-check">
                    <label class="form-check-label" onclick="var e=document.getElementById('changePasswordCheckbox');e.checked=!e.checked" data-toggle="collapse" data-target="#passwordFields" aria-expanded="{{old('change_password') ? 'true':'false'}}" aria-controls="passwordFields">
                        <input class="form-check-input" id="changePasswordCheckbox" type="checkbox" name="change_password" @if (old('change_password')) checked @endif />
                        @lang('a.Change')
                    </label>
                </div>
            </div>
        </div>

        <div id="passwordFields" class="collapse @if (old('change_password')) in @endif">

            <div class="form-group row @if($errors->has('password_current')) has-danger @endif">
                <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Current Password') </label>
                <div class="col-sm-9">
                    <input type="password" name="password_current" value="" class="form-control" />
                    @if($errors->has('password_current'))
                        <small class="form-control-feedback">{{ $errors->first('password_current') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group row @if($errors->has('password_new')) has-danger @endif">
                <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.New Password') </label>
                <div class="col-sm-9">
                    <input type="password" name="password_new" value="" class="form-control" />
                    @if($errors->has('password_new'))
                        <small class="form-control-feedback">{{ $errors->first('password_new') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group row @if($errors->has('password_new_confirmation')) has-danger @endif">
                <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Confirm Password') </label>
                <div class="col-sm-9">
                    <input type="password" name="password_new_confirmation" value="" class="form-control" />
                    @if($errors->has('password_new_confirmation'))
                        <small class="form-control-feedback">{{ $errors->first('password_new_confirmation') }}</small>
                    @endif
                </div>
            </div>

        </div>

        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary">
                    @lang('admin::messages.Save')
                </button>
                <a class="btn btn-secondary" href="{{ route('user.profile') }}">
                    @lang('admin::messages.Cancel')
                </a>
            </div>
        </div>

    </form>
@endsection