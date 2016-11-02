@extends('layouts.base')

@section('body-wrap')
    <nav class="navbar navbar-fixed-top navbar-light bg-faded">
        <a class="navbar-brand" href="#">{{settings('site.title')}}</a>
        @widget(mainmenu)
        <div class="pull-right" style="margin-left: 20px">
            @include('_partials.user_top_nav')
        </div>
        <div class="pull-right">
            @widgets(top-right)
        </div>
    </nav>

    <div class="container-fluid top-pad">
        @include('_partials.breadcrumbs')
        <div class="row">
            <div class="col-sm-3">
                @widgets(left)
                @yield('left')
            </div>

            <div class="col-sm-9">
                @include('_partials.notifications')
                @widgets(page)
                @yield('content')
            </div>

        </div>
    </div>
@endsection