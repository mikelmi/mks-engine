@extends('admin::page')

@section('header')
    <div class="breadcrumb">
        <a href="#/users" class="breadcrumb-item">@lang('a.Users')</a>
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
        <a class="btn btn-secondary" href="#/users">@lang('admin::messages.Cancel')</a>
    </div>
@endsection

@section('content')
<div class="card shd">

    <form class="card-block p-a-3" action="{{route('admin::user.save', [$model->id])}}" method="post" mks-form>

        @if($model->id)
            <div class="form-group row">
                <label class="col-sm-2 col-form-label form-control-label"> ID </label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{$model->id}}</p>
                </div>
            </div>
        @endif

        <div class="form-group row" ng-class="{'has-danger':page.errors.name}">
            <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Name') </label>
            <div class="col-sm-10">
                <input type="text" name="name" value="{{ old('name', $model->name) }}" class="form-control" />
                <small class="form-control-feedback" ng-show="page.errors.name">{[{page.errors.name[0]}]}</small>
            </div>
        </div>
        <div class="form-group row" ng-class="{'has-danger':page.errors.email}">
            <label class="col-sm-2 col-form-label form-control-label"> Email </label>
            <div class="col-sm-10">
                <input type="email" name="email" value="{{ old('email', $model->email) }}" class="form-control" />
                <small class="form-control-feedback" ng-if="page.errors.email">{[{page.errors.email[0]}]}</small>
            </div>
        </div>

        @if (!$model->is_current)
            <div class="form-group row">
                <label class="col-sm-2 col-form-label form-control-label">@lang('admin::messages.Status')</label>
                <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-outline-success @if (old('active',$model->active)) active @endif">
                            <input type="radio" name="active" autocomplete="off" value="1"@if (old('active',$model->active)) checked @endif >
                            @lang('admin::messages.Active')
                        </label>
                        <label class="btn btn-outline-warning @if (!old('active',$model->active)) active @endif">
                            <input type="radio" name="active" autocomplete="off" value="0"@if (!old('active',$model->active)) checked @endif >
                            @lang('admin::messages.Inactive')
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Roles') </label>
                <div class="col-sm-10">
                    <select multiple class="form-control form-block" name="roles[]" mks-select data-url="{{route('admin::user.roles', $model->id)}}">
                    </select>
                </div>
            </div>
        @else
            <div class="form-group row">
                <label class="col-sm-2 col-form-label form-control-label">@lang('admin::messages.Status')</label>
                <div class="col-sm-10">
                    <h5 class="form-control-static">
                        @if ($model->active)
                            <span class="tag tag-success">@lang('admin::messages.Active')</span>
                        @else
                            <span class="tag tag-warning">@lang('admin::messages.Inactive')</span>
                        @endif
                    </h5>
                </div>
            </div>
        @endif

        <div class="form-group row" ng-class="{'has-danger':page.errors.password}">
            <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Password') </label>
            <div class="col-sm-10">
                <div class="input-group">
                <span class="input-group-addon">
                    &nbsp;
                    <input type="checkbox" ng-model="psw_checked" class="form-check-input" />
                </span>
                    <input type="password" name="password" class="form-control" ng-disabled="!psw_checked" placeholder="@lang('a.Password')" /><br />
                    &nbsp;&nbsp;
                    <input type="password" name="password_confirmation" class="form-control" ng-disabled="!psw_checked" placeholder="@lang('a.Confirm Password')" />
                    <div>
                        <small class="form-control-feedback" ng-if="page.errors.password">{[{page.errors.password[0]}]}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label form-control-label"> @lang('admin::messages.Created at') </label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$model->created_at}}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label form-control-label"> @lang('admin::messages.Updated at') </label>
            <div class="col-sm-10">
                <p class="form-control-static">{{$model->updated_at}}</p>
            </div>
        </div>

    </form>

</div>
@endsection