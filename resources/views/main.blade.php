@extends('base')

@section('body')
    <nav class="navbar navbar-fixed-top navbar-light bg-faded">
        <a class="navbar-brand" href="#">{{settings('site.title')}}</a>
        @widget(mainmenu)
    </nav>

    <div class="container-fluid top-pad">
        <div class="row">

            <div class="col-sm-3">
                @widgets(left)
                @yield('left')
            </div>

            <div class="col-sm-9">
                @widgets(page)
                @yield('content')
            </div>

        </div>
    </div>
@endsection