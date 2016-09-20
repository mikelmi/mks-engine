@extends ('admin.settings.index')

@section('form')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('user.Enable Registration') </label>
        <div class="col-sm-9">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-outline-success @if (old('registration',$model['registration'])) active @endif">
                    <input type="radio" name="registration" autocomplete="off" value="1"@if (old('registration',$model['registration'])) checked @endif >
                    @lang('a.Yes')
                </label>
                <label class="btn btn-outline-danger @if (!old('registration',$model['registration'])) active @endif">
                    <input type="radio" name="registration" autocomplete="off" value="0"@if (!old('registration',$model['registration'])) checked @endif >
                    @lang('a.No')
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('user.Enable Auth') </label>
        <div class="col-sm-9">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-outline-success @if (old('auth',$model['auth'])) active @endif">
                    <input type="radio" name="auth" autocomplete="off" value="1"@if (old('auth',$model['auth'])) checked @endif >
                    @lang('a.Yes')
                </label>
                <label class="btn btn-outline-danger @if (!old('auth',$model['auth'])) active @endif">
                    <input type="radio" name="auth" autocomplete="off" value="0"@if (!old('auth',$model['auth'])) checked @endif >
                    @lang('a.No')
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('user.Email Verification') </label>
        <div class="col-sm-9">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-outline-success @if (old('verification', $model['verification'])) active @endif">
                    <input type="radio" name="verification" autocomplete="off" value="1"@if (old('verification', $model['verification'])) checked @endif >
                    @lang('a.Yes')
                </label>
                <label class="btn btn-outline-danger @if (!old('verification', $model['verification'])) active @endif">
                    <input type="radio" name="verification" autocomplete="off" value="0"@if (!old('verification', $model['verification'])) checked @endif >
                    @lang('a.No')
                </label>
            </div>
        </div>
    </div>
@endsection