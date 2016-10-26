@extends ('admin.settings.index')

@section('form')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.Type') </label>
        <div class="col-sm-9">
            <select name="type" class="form-control" ng-model="captchaType" ng-init="captchaType='{{$model->get('type')}}'">
                <option value=""> </option>
                <option value="simple">@lang('messages.Simple')</option>
                <option value="recaptcha">Google reCaptcha</option>
            </select>
        </div>
    </div>

    <div ng-show="captchaType=='simple'">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.Template') </label>
            <div class="col-sm-9">
                <select name="config[template]" class="form-control" ng-model="captchaTemplate" ng-init="captchaTemplate='{{$model->get('config.template')}}'">
                    <option value=""> </option>
                    <option value="flat">flat</option>
                    <option value="mini">mini</option>
                    <option value="inverse">inverse</option>
                </select>
            </div>
        </div>
    </div>

    <div ng-show="captchaType=='recaptcha'">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.SiteKey') </label>
            <div class="col-sm-9">
                <input type="text" name="config[sitekey]" value="{{ $model->get('config.sitekey', config('captcha.sitekey')) }}" class="form-control" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.SecretKey') </label>
            <div class="col-sm-9">
                <input type="text" name="config[secret]" value="{{ $model->get('config.secret', config('captcha.secret')) }}" class="form-control" />
            </div>
        </div>
    </div>
@endsection