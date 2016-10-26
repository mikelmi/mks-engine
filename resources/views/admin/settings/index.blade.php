@extends('admin::page')

@section('title')
    @lang('general.Settings')
@endsection

@section('tools')
    <button type="button" class="btn btn-primary" mks-submit>@lang('admin::messages.Save')</button>
@endsection

@section('content')
<form class="card shd" action="{{route('admin::settings.save', $scope)}}" method="post" mks-form>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs pull-xs-left">
            @foreach($scopes as $name => $item)
                <li class="nav-item">
                    <a class="nav-link @if($name == $scope) active @endif " href="#/settings/{{$name}}">{{$item->title}}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card-block">
        @yield('form')
    </div>

</form>
@endsection