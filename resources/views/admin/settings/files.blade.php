@extends ('admin.settings.index')

@section('form')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('filemanager.Enable upload') </label>
        <div class="col-sm-9">
            <select name="upload" class="form-control">
                <option value="" @if (!$model['upload']) selected @endif>
                    @lang('filemanager.upload_with_permissions')
                </option>
                <option value="1" @if ($model['upload'] == '1') selected @endif>
                    @lang('filemanager.upload_for_all')
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row" ng-class="{'has-danger':page.errors.extensions}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('filemanager.Extensions') </label>
        <div class="col-sm-9">
            <select mks-select class="form-control" multiple="multiple"
                    name="extensions[]"
                    data-tags="true"
                    data-token-separators="[',']"
            >
                @foreach($model['extensions'] as $ext)
                    <option value="{{$ext}}" selected>{{$ext}}</option>
                @endforeach
            </select>
            <small class="form-control-feedback" ng-show="page.errors.extensions">{[{page.errors.extensions[0]}]}</small>
        </div>
    </div>
    <div class="form-group row" ng-class="{'has-danger':page.errors.max_size}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('filemanager.max_size') (Mb)</label>
        <div class="col-sm-9">
            <input type="number" name="max_size" class="form-control" value="{{$model['max_size']}}" />
            <small class="form-control-feedback" ng-show="page.errors.max_size">{[{page.errors.max_size[0]}]}</small>
        </div>
    </div>
@endsection