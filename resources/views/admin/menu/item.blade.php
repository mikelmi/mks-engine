@extends('admin::page')

@section('header')
    <div class="breadcrumb">
        <a href="#/menuman" class="breadcrumb-item">@lang('general.Menu')</a>
        <a href="#/menuman/{{$menu->id}}" class="breadcrumb-item">{{$menu->name}}</a>
        <span class="breadcrumb-item">
            {{  trans('general.' . ($model->id ? 'Edit Menu Item' : 'Add Menu Item')) }}
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
        <a class="btn btn-secondary" href="#/menuman/{{$menu->id}}">@lang('admin::messages.Cancel')</a>
    </div>
@endsection

@section('content')

<form class="card shd" action="{{route('admin::menu.items.save', ['scope' => $menu->id, 'id' => $model->id])}}" method="post" mks-form>

    <div class="card-block">

        @if($model->id)
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right"> ID </label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{$model->id}}</p>
                </div>
            </div>
        @endif

        <div class="form-group row" ng-class="{'has-danger':page.errors.title}">
            <label class="col-sm-2 col-form-label text-sm-right"> @lang('general.Title') </label>
            <div class="col-sm-10">
                <input type="text" name="title" value="{{ old('title', $model->title) }}" class="form-control" />
                <small class="form-control-feedback" ng-show="page.errors.title">{[{page.errors.title[0]}]}</small>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right">@lang('general.Link')</label>
            <div class="col-sm-10">
                <mks-link-select field-route="route"
                                 field-params="params"
                                 route="{{old('route', $model->route)}}"
                                 params="{{old('params', $model->params)}}"
                                 empty-title="URL"
                                 raw-enabled="true"
                                 field-raw="url"
                                 raw-value="{{old('url', $model->url)}}"
                >
                </mks-link-select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right">@lang('general.Parent Item')</label>
            <div class="col-sm-10">
                <select name="parent_id" class="form-control" mks-select data-url="{{route('admin::menu.tree.options', ['scope'=>$menu->id, 'id'=>$model->id])}}">
                    <option value=""> - </option>
                </select>
            </div>
        </div>

    </div>

</form>
@endsection