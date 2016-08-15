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
        <a class="btn btn-secondary" href="#/users">Cancel</a>
    </div>
@endsection

@section('content')
<div class="card shd">

    <form class="card-block p-a-3" action="{{route('admin::user.save', [$model->id])}}" method="post" mks-form>

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
                <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Roles') </label>
                <div class="col-sm-10">
                    <select multiple class="form-control form-block" name="roles[]" mks-select data-url="{{route('admin::user.roles', $model->id)}}">
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group row" ng-class="{'has-danger':page.errors.password}">
            <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Password') </label>
            <div class="col-sm-10">
                <div class="input-group">
                <span class="input-group-addon">
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

    </form>

</div>
@endsection