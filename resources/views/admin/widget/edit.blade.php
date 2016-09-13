@extends('admin::page')

@section('header')
    <div class="breadcrumb">
        <a href="#/widget" class="breadcrumb-item">@lang('a.Widgets')</a>
        <span class="breadcrumb-item">
            {{  trans('admin::messages.' . ($model->id ? 'Edit' : 'Add')) }}
        </span>
    </div>
@endsection

@section('tools')
    <div class="btn-group">
        <button type="button" class="btn btn-primary" mks-submit>@lang('admin::messages.Save')</button>
        <a class="btn btn-secondary" href="#/widget">@lang('admin::messages.Cancel')</a>
    </div>
@endsection

@section('content')
    <form class="card shd" action="{{route('admin::widget.save', [$model->id])}}" method="post" mks-form>

        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-xs-left">
                <li class="nav-item">
                    <a class="nav-link active" href="#" role="tab" data-toggle="tab" data-target="#tab-widget">@lang('a.Widget')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" role="tab" data-toggle="tab" data-target="#tab-params">@lang('a.Params')</a>
                </li>
            </ul>
        </div>

        <div class="card-block">
            <div class="tab-content">

                <div class="tab-pane active" id="tab-widget" role="tabpanel">
                    @if($model->id)
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-control-label">ID</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{$model->id}}</p>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label">@lang('a.Type')</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{$widget->title()}}</p>
                            <input type="hidden" name="class" value="{{get_class($widget)}}" />
                        </div>
                    </div>

                    <div class="form-group row" ng-class="{'has-danger':page.errors.title}">
                        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Title') </label>
                        <div class="col-sm-10">
                            <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control" />
                            <small class="form-control-feedback" ng-show="page.errors.title">{[{page.errors.title[0]}]}</small>
                        </div>
                    </div>
                    <div class="form-group row" ng-class="{'has-danger':page.errors.path}">
                        <label class="col-sm-2 col-form-label form-control-label">@lang('a.Name')</label>
                        <div class="col-sm-10">
                            <div class="input-group" mv-checked-input>
                            <span class="input-group-addon">
                                <input type="checkbox" ng-model="page.hasName">
                            </span>
                                <input ng-disabled="!page.hasName" type="text" name="name" value="{{ old('name', $model->name) }}" class="form-control" />
                            </div>
                            <small class="form-control-feedback" ng-show="page.errors.name">{[{page.errors.name[0]}]}</small>
                        </div>
                    </div>
                    <div class="form-group row" ng-class="{'has-danger':page.errors.position}">
                        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Position') </label>
                        <div class="col-sm-10">
                            <input type="text" name="position" value="{{ old('position', $model->position) }}" class="form-control" />
                            <small class="form-control-feedback" ng-show="page.errors.position">{[{page.errors.position[0]}]}</small>
                        </div>
                    </div>
                    <div class="form-group row" ng-class="{'has-danger':page.errors.ordering}">
                        <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Order') </label>
                        <div class="col-sm-10">
                            <input type="number" name="ordering" value="{{ old('ordering', $model->ordering) }}" class="form-control" />
                            <small class="form-control-feedback" ng-show="page.errors.ordering">{[{page.errors.ordering[0]}]}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label">@lang('admin::messages.Status')</label>
                        <div class="col-sm-10">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-outline-success @if (old('status',$model->status)) active @endif">
                                    <input type="radio" name="status" autocomplete="off" value="1"@if (old('status',$model->status)) checked @endif >
                                    @lang('admin::messages.Active')
                                </label>
                                <label class="btn btn-outline-danger @if (!old('status',$model->status)) active @endif">
                                    <input type="radio" name="status" autocomplete="off" value="0"@if (!old('status',$model->status)) checked @endif >
                                    @lang('admin::messages.Inactive')
                                </label>
                            </div>
                        </div>
                    </div>

                    {!! $widget->form()->render() !!}

                </div>

                <div class="tab-pane" id="tab-params" role="tabpanel" ng-controller="WidgetRoutesCtrl" ng-init="init({{$model->id}})">
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
                        <label class="col-sm-3 col-form-label form-control-label"> @lang('a.Showing') </label>
                        <div class="col-sm-9">
                            <select class="form-control" name="params[showing]" ng-model="paramShowing" ng-init="paramShowing='{{old('params.showing', $model->param('showing'))}}'">
                                <option value="">@lang('a.Show on all')</option>
                                <option value="1">@lang('a.Show for'):</option>
                                <option value="2">@lang('a.Hide for'):</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" ng-hide="!paramShowing">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="form-group" ng-repeat="route in routes track by $index">
                                <button type="button" class="pull-xs-left btn btn-outline-danger btn-sm" ng-click="removeChoice(route)">
                                    <i class="fa fa-remove"></i>
                                </button>
                                <mks-link-select field-route="routes[{[{ $index }]}]"
                                                 field-params="route_params[{[{ $index }]}]"
                                                 model="$parent.routes[{[{ $index }]}]"
                                                 routes="{[{ routes[$index] }]}"
                                >
                                </mks-link-select>
                                <input type="hidden" name="route_ids[{[{ $index }]}]" ng-value="route.model_id" />
                            </div>
                            <button type="button" class="btn btn-outline-primary" ng-click="addChoice()">
                                @lang('admin::messages.Add')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection