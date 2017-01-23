@extends('layouts.empty')

@section('body')

    @if (!$page->param('hide_title'))
        <h1 class="page-title">{{$page->title}}</h1>
    @endif

    {!! $page->page_text !!}

@endsection