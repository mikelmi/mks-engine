@extends('layouts.main')

@section('content')

    @if (!$page->param('hide_title'))
        <h1 class="page-title">{{$page->title}}</h1>
    @endif

    <div>
        {!! $page->page_text !!}
    </div>

@endsection