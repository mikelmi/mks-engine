@extends('admin::page')

@section('header')
    <div class="breadcrumb">
        <a href="#/page" class="breadcrumb-item">@lang('a.Pages')</a>
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
            @if ($model->trashed())
                <a class="dropdown-item" href="#" mks-submit data-flag="2">@lang('admin::messages.Save and Restore')</a>
            @endif
            <a class="dropdown-item" href="#" mks-submit data-flag="1">@lang('admin::messages.Save and New')</a>
        </div>
    </div>
    <div class="btn-group">
        <a class="btn btn-secondary" href="#/page{{$model->trashed() ? '/trash' : ''}}">@lang('admin::messages.Cancel')</a>
    </div>
@endsection

@section('content')
    @if ($model->trashed())
        <div class="alert alert-warning">@lang('admin::messages.In Trash')</div>
    @endif

<form class="card shd" action="{{route('admin::page.save', [$model->id])}}" method="post" mks-form>

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs pull-xs-left">
            <li class="nav-item">
                <a class="nav-link active" href="#" role="tab" data-toggle="tab" data-target="#tab-page">@lang('a.Page')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" role="tab" data-toggle="tab" data-target="#tab-seo">SEO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" role="tab" data-toggle="tab" data-target="#tab-params">@lang('a.Params')</a>
            </li>
        </ul>
    </div>

    <div class="card-block">
        <div class="tab-content">

            <div class="tab-pane active" id="tab-page" role="tabpanel">
                @if($model->id)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label"> ID </label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{$model->id}}</p>
                        </div>
                    </div>
                @endif
                <div class="form-group row" ng-class="{'has-danger':page.errors.title}">
                    <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Title') </label>
                    <div class="col-sm-10">
                        <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control" />
                        <small class="form-control-feedback" ng-show="page.errors.title">{[{page.errors.title[0]}]}</small>
                    </div>
                </div>
                <div class="form-group row" ng-class="{'has-danger':page.errors.path}">
                    <label class="col-sm-2 col-form-label form-control-label"> URL </label>
                    <div class="col-sm-10">
                        <div class="input-group" mv-checked-input>
                            <span class="input-group-addon">
                                <input type="checkbox" ng-model="page.hasPath"> {{ url('/') }}/
                            </span>
                            <input ng-disabled="!page.hasPath" type="text" name="path" value="{{ old('path', $model->path) }}" class="form-control" />
                        </div>
                        <small class="form-control-feedback" ng-show="page.errors.path">{[{page.errors.path[0]}]}</small>
                    </div>
                </div>
                <div class="form-group row" ng-class="{'has-danger':page.errors.page_text}">
                    <label class="col-sm-12 col-form-label form-control-label"> @lang('a.Text') </label>
                    <div class="col-sm-12">
                        <textarea name="page_text" class="form-control" rows="5" mks-editor>{{ old('page_text', $model->page_text) }}</textarea>
                        <small class="form-control-feedback" ng-show="page.errors.page_text">{[{page.errors.page_text[0]}]}</small>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab-seo" role="tabpanel">
                <div class="form-group row" ng-class="{'has-danger':page.errors.meta_title}">
                    <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Title') </label>
                    <div class="col-sm-10">
                        <input type="text" name="meta_title" value="{{ old('meta_title', $model->meta_title) }}" class="form-control" />
                        <small class="form-control-feedback" ng-show="page.errors.meta_title">{[{page.errors.meta_title[0]}]}</small>
                    </div>
                </div>
                <div class="form-group row" ng-class="{'has-danger':page.errors.meta_description}">
                    <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Description') </label>
                    <div class="col-sm-10">
                        <textarea name="meta_description" class="form-control">{{ old('meta_description', $model['meta_description']) }}</textarea>
                        <small class="form-control-feedback" ng-show="page.errors.meta_description">{[{page.errors.meta_description[0]}]}</small>
                    </div>
                </div>
                <div class="form-group row" ng-class="{'has-danger':page.errors.meta_keywords}">
                    <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Keywords') </label>
                    <div class="col-sm-10">
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $model['meta_keywords']) }}" class="form-control" />
                        <small class="form-control-feedback" ng-show="page.errors.meta_keywords">{[{page.errors.meta_keywords[0]}]}</small>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab-params" role="tabpanel">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Hide Title') </label>
                    <div class="col-sm-9">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-outline-success @if (old('params.hide_title', $model->param('hide_title'))) active @endif">
                                <input type="radio" name="params[hide_title]" autocomplete="off" value="1"@if (old('hide_title', $model->param('hide_title'))) checked @endif >
                                @lang('a.Yes')
                            </label>
                            <label class="btn btn-outline-danger @if (!old('params.hide_title', $model->param('hide_title'))) active @endif">
                                <input type="radio" name="params[hide_title]" autocomplete="off" value="0"@if (!old('hide_title', $model->param('hide_title'))) checked @endif >
                                @lang('a.No')
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Roles') </label>
                    <div class="col-sm-9">
                        <select class="form-control" name="params[roles]" ng-model="paramRoles" ng-init="paramRoles='{{old('params.roles', $model->param('roles'))}}'">
                            <option value="">@lang('a.Show for all')</option>
                            <option value="1">@lang('a.Show for registered')</option>
                            <option value="2">@lang('a.Show for roles'):</option>
                            <option value="-1">@lang('a.Hide for roles'):</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" ng-hide="!paramRoles || paramRoles==1">
                    <div class="col-sm-9 offset-sm-3 row-block">
                        <select multiple class="form-control form-block" name="roles[]" mks-select data-url="{{route('admin::roles.forModel', [get_class($model), $model->id])}}"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection