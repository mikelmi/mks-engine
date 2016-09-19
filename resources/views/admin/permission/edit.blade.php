@extends('admin::page')

@section('header')
    <div class="breadcrumb">
        <a href="#/permission" class="breadcrumb-item">@lang('a.Permissions')</a>
        <span class="breadcrumb-item">
            {{  trans('admin::messages.' . ($model->id ? 'Edit' : 'Add')) }}
        </span>
    </div>
@endsection

@section('tools')
    <div class="btn-group">
        <button type="button" class="btn btn-primary" mks-submit>@lang('admin::messages.Save')</button>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#" mks-submit data-flag="1">@lang('admin::messages.Save and New')</a>
        </div>
    </div>
    <div class="btn-group">
        <a class="btn btn-secondary" href="#/permission">@lang('admin::messages.Cancel')</a>
    </div>
@endsection

@section('content')
<div class="card shd">

    <form class="card-block p-a-3" action="{{route('admin::permission.save', [$model->id])}}" method="post" mks-form>

        <div class="form-group row" ng-class="{'has-danger':page.errors.name}">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Title') </label>
            <div class="col-sm-9">
                <input type="text" name="name" value="{{ old('name', $model->name) }}" class="form-control" />
                <small class="form-control-feedback" ng-show="page.errors.name">{[{page.errors.name[0]}]}</small>
            </div>
        </div>
        <div class="form-group row" ng-class="{'has-danger':page.errors.display_name}">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Display Name') </label>
            <div class="col-sm-9">
                <input type="text" name="display_name" value="{{ old('display_name', $model->display_name) }}" class="form-control" />
                <small class="form-control-feedback" ng-if="page.errors.email">{[{page.errors.display_name[0]}]}</small>
            </div>
        </div>
        <div class="form-group row" ng-class="{'has-danger':page.errors.description}">
            <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Description') </label>
            <div class="col-sm-9">
                <textarea name="description"class="form-control">{{ old('description', $model->description) }}</textarea>
                <small class="form-control-feedback" ng-if="page.errors.email">{[{page.errors.description[0]}]}</small>
            </div>
        </div>
    </form>

</div>
@endsection