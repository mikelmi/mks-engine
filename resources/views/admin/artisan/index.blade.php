@extends('admin::page')

@section('title')
    Artisan
@endsection

@section('controller')
    ng-controller="ArtisanCtrl"
@endsection

@section('tools')
    <button type="button" class="btn btn-primary" ng-click="run('{{route('admin::artisan.run')}}')" ng-disabled="inProgress">
        <i class="fa fa-terminal"></i> Run
    </button>

    <a class="btn btn-secondary" href="#/home">@lang('admin::messages.Cancel')</a>
@endsection

@section('content')
<div class="card shd">

    <div class="card-block p-a-3">

        <div class="form-group row" ng-class="{'has-danger':errors.command}">
            <label class="col-sm-3 col-form-label text-sm-right"> Command </label>
            <div class="col-sm-9">
                <select class="form-control" required mks-select
                        ng-options="item.name for item in commands"
                        ng-model="command"
                    >
                </select>
                <input type="hidden" ng-value="command.name">
                <p class="form-text text-muted" ng-if="command">@{{command.description}}</p>
                <small class="form-control-feedback" ng-show="errors.command">@{{errors.command[0]}}</small>
            </div>
        </div>
        <div class="form-group row" ng-if="command.arguments.length">
            <label class="col-sm-3 col-form-label text-sm-right"> Arguments </label>
            <div class="col-sm-9">
                <div class="form-group" ng-repeat="arg in command.arguments | orderBy: required"  ng-class="{'has-danger':errors['arguments.'+arg.name]}">
                    <div class="input-group">
                        <label class="input-group-addon" ng-class="{'required': arg.required}">@{{arg.name}}</label>
                        <input type="text" class="form-control" placeholder="@{{arg.description}}"
                               ng-model="arguments[arg.name]"
                               ng-required="arg.required"
                        >
                    </div>
                </div>
            </div>
            <small class="form-control-feedback" ng-show="errors['arguments.'+arg.name]">&nbsp;&nbsp;@{{errors['arguments.'+arg.name][0]}}</small>
        </div>
        <div class="form-group row" ng-if="command.options.length">
            <label class="col-sm-3 col-form-label text-sm-right"> Options </label>
            <div class="col-sm-9">
                <div class="form-group" ng-repeat="opt in command.options | orderBy: required" ng-class="{'has-danger':errors['options.'+opt.name]}">
                    <div class="input-group" ng-if="opt.accept_value">
                        <label class="input-group-addon" ng-class="{'required': opt.required}">@{{opt.name}}</label>
                        <input type="text" class="form-control" placeholder="@{{opt.description}}"
                               ng-model="options[opt.name]"
                               ng-required="opt.required"
                        >
                    </div>
                    <div class="form-check" ng-if="!opt.accept_value">
                        <label class="form-check-label">
                            &nbsp;&nbsp;
                            <input type="checkbox" class="form-check-input" ng-model="options[opt.name]">
                            @{{opt.name}}
                            <small class="text-muted">@{{opt.description}}</small>
                        </label>
                    </div>
                    <small class="form-control-feedback" ng-show="errors['options.'+opt.name]">&nbsp;&nbsp;@{{errors['options.'+opt.name][0]}}</small>
                </div>
            </div>
        </div>

        <div ng-if="output">
            <hr />
            <pre class="bg-inverse p-3 text-white">@{{output}}</pre>
        </div>

    </div>

</div>
@endsection