@extends('base')

@section('body')
    <nav class="navbar navbar-fixed-top navbar-light bg-faded">
        <a class="navbar-brand" href="#">{{settings('site.title')}}</a>
        <ul class="nav navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid top-pad">
        <div class="row">

            <div class="col-sm-3">
                Sidebar
                @yield('left')
            </div>

            <div class="col-sm-9">
                @yield('content')
            </div>

        </div>
    </div>
@endsection