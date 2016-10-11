@extends('admin::page')

@section('title')
    <div class="breadcrumb">
        <a href="#/language" class="breadcrumb-item">@lang('a.Languages')</a>
        <span class="breadcrumb-item">
            {!! $model->iconImage() !!}
            {{  $model->name }} ({{  $model->iso }})
        </span>
    </div>
@endsection

@section('tools')
    <button type="button" class="btn btn-primary" mks-submit>@lang('admin::messages.Save')</button>
@endsection

@section('content')
    <form class="card shd" action="{{route('admin::language.save', $model->iso)}}" method="post" mks-form>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-xs-left">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-target="#lang-tab" data-toggle="tab" role="tab">@lang('a.Language')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-target="#lang-pages-tab" data-toggle="tab" role="tab">@lang('a.Pages')</a>
                </li>
            </ul>
        </div>

        <div class="card-block">
            <div class="tab-content">

                <div class="tab-pane active" id="lang-tab" role="tabpanel">
                    <div class="form-group row" ng-class="{'has-danger':page.errors.title}">
                        <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Title') </label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{ $model->title }}" class="form-control" />
                            <small class="form-control-feedback" ng-show="page.errors.title">{[{page.errors.title[0]}]}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right">@lang('admin::messages.Status')</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-outline-success @if ($model->enabled) active @endif">
                                    <input type="radio" name="enabled" autocomplete="off" value="1"@if ($model->enabled) checked @endif >
                                    @lang('admin::messages.Active')
                                </label>
                                <label class="btn btn-outline-danger @if (!$model->enabled) active @endif">
                                    <input type="radio" name="enabled" autocomplete="off" value="0"@if (!$model->enabled) checked @endif >
                                    @lang('admin::messages.Inactive')
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Site name') </label>
                        <div class="col-sm-9">
                            <input type="text" name="site[title]" value="{{ $model->get('site.title') }}" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Description') </label>
                        <div class="col-sm-9">
                            <textarea name="site[description]" class="form-control">{{ $model->get('site.description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right"> @lang('a.Keywords') </label>
                        <div class="col-sm-9">
                            <input type="text" name="site[keywords]" value="{{ $model->get('site.keywords') }}" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="lang-pages-tab" role="tabpanel">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right">@lang('a.Homepage')</label>
                        <div class="col-sm-9">
                            <mks-link-select field-route="home[route]"
                                             field-params="home[params]"
                                             route="{{$model->get('home.route')}}"
                                             params="{{$model->get('home.params')}}"
                                             data-title="{{$model->get('home.params')}}"
                            >
                            </mks-link-select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right">404</label>
                        <div class="col-sm-9">
                            <select name="404" class="form-control" mks-select>
                                <option value=""> - </option>
                                @foreach($pages as $id => $title)
                                    <option value="{{$id}}" @if($id == $model->get('404')) selected @endif>{{$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right">@lang('a.Error page')</label>
                        <div class="col-sm-9">
                            <select name="500" class="form-control" mks-select>
                                <option value=""> - </option>
                                @foreach($pages as $id => $title)
                                    <option value="{{$id}}" @if($id == $model->get('500')) selected @endif>{{$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-sm-right">@lang('a.Offline page')</label>
                        <div class="col-sm-9">
                            <select name="503" class="form-control" mks-select>
                                <option value=""> - </option>
                                @foreach($pages as $id => $title)
                                    <option value="{{$id}}" @if($id == $model->get('503')) selected @endif>{{$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>
@endsection