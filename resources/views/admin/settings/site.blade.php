@extends ('admin.settings.index')

@section('form')
    <div class="form-group row" ng-class="{'has-danger':page.errors.title}">
        <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Title') </label>
        <div class="col-sm-9">
            <input type="text" name="title" value="{{ old('title', $model['title']) }}" class="form-control" />
            <small class="form-control-feedback" ng-show="page.errors.title">{[{page.errors.title[0]}]}</small>
        </div>
    </div>
    <div class="form-group row" ng-class="{'has-danger':page.errors.description}">
        <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Description') </label>
        <div class="col-sm-9">
            <textarea name="description" class="form-control">{{ old('description', $model['description']) }}</textarea>
            <small class="form-control-feedback" ng-show="page.errors.description">{[{page.errors.description[0]}]}</small>
        </div>
    </div>
    <div class="form-group row" ng-class="{'has-danger':page.errors.keywords}">
        <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Keywords') </label>
        <div class="col-sm-9">
            <input type="text" name="keywords" value="{{ old('keywords', $model['keywords']) }}" class="form-control" />
            <small class="form-control-feedback" ng-show="page.errors.keywords">{[{page.errors.keywords[0]}]}</small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Site off') </label>
        <div class="col-sm-9">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-outline-success @if (old('off',$model['off'])) active @endif">
                    <input type="radio" name="off" autocomplete="off" value="1"@if (old('off',$model['off'])) checked @endif >
                    @lang('a.Yes')
                </label>
                <label class="btn btn-outline-danger @if (!old('off',$model['off'])) active @endif">
                    <input type="radio" name="off" autocomplete="off" value="0"@if (!old('off',$model['off'])) checked @endif >
                    @lang('a.No')
                </label>
            </div>
        </div>
    </div>
@endsection