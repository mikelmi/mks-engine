<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>Starter Template for Bootstrap</title>

    @section('css')
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    @show
</head>

<body @yield('bodyAttr')>

    @section('notifications')
        <div class="notifications">
            @if (Session::has('message'))
                <div class="alert {{ session('alert-class', 'alert-info') }} alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('message') }}
                </div>
            @endif
        </div>
    @show

    @section('body')
    @show

    @section('js')
        <script src="{{ asset('js/bootstrap.js') }}"></script>
    @show

</body>
</html>