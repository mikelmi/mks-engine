@if (!$page->param('hide_title'))
    <h2>{{$page->title}}</h2>
@endif
<div>
    {!! $page->page_text !!}
</div>