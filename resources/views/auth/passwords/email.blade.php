@extends('layouts.main')

<!-- Main Content -->
@section('content')

    <h1 class="page-title">@lang('auth.Reset Password')</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form role="form" method="post" action="{{ url('/password/email') }}">
        {{ csrf_field() }}

        <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
            <label for="email" class="col-md-2 col-form-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-2">
                <button type="submit" class="btn btn-primary">
                    @lang('auth.send_reset')
                </button>
            </div>
        </div>
    </form>

@endsection
