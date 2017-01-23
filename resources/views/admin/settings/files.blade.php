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

    <div class="form-group row" ng-class="{'has-danger':page.errors.max_width||page.errors.max_height}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.image_max_size') </label>
        <div class="col-sm-9">
            <input type="number" min="200" max="3000" step="1" name="max_width" value="{{$model['max_width']}}" class="form-control form-control-dim" title="@lang('general.Width')" /> x
            <input type="number" min="200" max="3000" step="1" name="max_height" value="{{$model['max_height']}}" class="form-control form-control-dim" title="@lang('general.Height')" />
            <small class="form-control-feedback" ng-show="page.errors.max_width||page.errors.max_height">{[{page.errors.max_width[0]||page.errors.max_height[0]}]}</small>
        </div>
    </div>

    <div class="form-group row" ng-class="{'has-danger':page.errors.watermark}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.watermark') </label>
        <div class="col-sm-9">
            <mks-image-select id="watermark" name="watermark" data-image="'{{$model['watermark']}}'"></mks-image-select>
            <small class="form-control-feedback" ng-show="page.errors.watermark">{[{page.errors.watermark[0]}]}</small>
        </div>
    </div>

    <div class="form-group row" ng-class="{'has-danger':page.errors.mark_width||page.errors.mark_height}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.watermark_size') </label>
        <div class="col-sm-9">
            <input type="number" min="10" max="500" step="1" name="mark_width" value="{{$model['mark_width']}}" class="form-control form-control-dim" title="@lang('general.Width')" /> x
            <input type="number" min="10" max="500" step="1" name="mark_height" value="{{$model['mark_height']}}" class="form-control form-control-dim" title="@lang('general.Height')" />
            <small class="form-control-feedback" ng-show="page.errors.mark_width||page.errors.mark_height">{[{page.errors.mark_width[0]||page.errors.mark_height[0]}]}</small>
        </div>
    </div>

    <div class="form-group row" ng-class="{'has-danger':page.errors.mark_pos}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.watermark_pos') </label>
        <div class="col-sm-9">
            <select class="form-control" name="mark_pos">
                @foreach($model['mark_positions'] as $pos)
                    <option value="{{$pos}}" @if($pos == $model['mark_pos']) selected @endif>{{trans('general.'.$pos)}}</option>
                @endforeach
            </select>
            <small class="form-control-feedback" ng-show="page.errors.mark_pos">{[{page.errors.mark_pos[0]}]}</small>
        </div>
    </div>

    <div class="form-group row" ng-class="{'has-danger':page.errors.mark_alpha}">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.watermark_alpha') </label>
        <div class="col-sm-9">
            <input type="number" min="0" max="100" step="1" name="mark_alpha" value="{{$model['mark_alpha']}}" class="form-control form-control-dim" placeholder="50" />
            <small class="form-control-feedback" ng-show="page.errors.mark_alphat">{[{page.errors.mark_alpha[0]}]}</small>
        </div>
    </div>

@endsection