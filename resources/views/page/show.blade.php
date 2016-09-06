@extends('main')

@section('content')

    @if (!$page->param('hide_title'))
        <h1>{{$page->title}}</h1>
    @endif

    <div>
        {!! $page->page_text !!}
    </div>

@endsection