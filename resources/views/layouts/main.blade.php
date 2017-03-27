@extends('layouts.base')

@section('body-wrap')
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">{!! site_logo() !!}</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @widget(mainmenu)
            @widgets(top-right)
            @include('_partials.user_top_nav')
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
                @widgets(bottom)
            </div>

        </div>
    </div>
@endsection