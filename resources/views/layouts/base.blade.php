<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! app('seotools')->generate() !!}

    @section('css')
        <link href="{{ asset('css/system.css') }}" rel="stylesheet">
    @show
</head>

<body @yield('bodyAttr')>

    @section('body-wrap')
        @include('_partials.notifications')

        @yield('body')
    @show

    @section('js')
        <script src="{{ asset('js/system.js') }}"></script>
    @show

</body>
</html>