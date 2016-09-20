@extends('layouts.main')

@section('content')
    <h1 class="page-title">@lang('auth.Sign In')</h1>

    <div>
        <form role="form" method="post" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                <label for="email" class="col-md-2 col-form-label">E-mail</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-2 col-form-label">@lang('auth.Password')</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-2">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="remember"> @lang('auth.Remember me')
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-md-2 col-md-6">
                    <button type="submit" class="btn btn-primary">@lang('auth.Sign In')</button>
                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                        @lang('auth.Forgot Password')
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection
