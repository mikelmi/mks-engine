@extends('layouts.main')

@section('content')

    <h1 class="page-title">@lang('auth.Register')</h1>

    <form role="form" method="post" action="{{ url('/register') }}">
        {{ csrf_field() }}

        <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
            <label for="name" class="col-md-4 col-form-label">@lang('general.Name')</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label for="email" class="col-md-4 col-form-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
            <label for="password" class="col-md-4 col-form-label">@lang('general.Password')</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <label for="password-confirm" class="col-md-4 col-form-label">@lang('passwords.Confirm')</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                    <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>
        </div>

        @if (captcha_enabled())
            <div class="form-group row{{ $errors->has(captcha_field_name()) ? ' has-danger' : '' }}">
                <label class="col-md-4 col-form-label">
                    @if (captcha_has_input())
                        @lang('messages.Captcha')
                    @endif
                </label>
                <div class="col-md-6">
                    {!! captcha_display(true) !!}
                    @if ($errors->has(captcha_field_name()))
                        <div class="form-control-feedback">{{ $errors->first(captcha_field_name()) }}</div>
                    @endif
                </div>
            </div>
        @endif

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    @lang('auth.Register')
                </button>
            </div>
        </div>
    </form>

@endsection
