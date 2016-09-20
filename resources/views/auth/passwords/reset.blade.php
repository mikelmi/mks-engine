@extends('layouts.main')

@section('content')
    <h1 class="page-title">@lang('auth.Reset Password')</h1>

    <form role="form" method="POST" action="{{ url('/password/reset') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
            <label for="email" class="col-md-3 col-form-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
            <label for="password" class="col-md-3 col-form-label">@lang('auth.Password')</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <label for="password-confirm" class="col-md-3 col-form-label">@lang('passwords.Confirm')</label>
            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                    <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn btn-primary">
                    @lang('auth.Reset Password')
                </button>
            </div>
        </div>
    </form>

@endsection
