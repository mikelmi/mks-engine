<div
@if (isset($attr) && is_array($attr))
    {!! html_attr($attr) !!}
@endif
>
    @if (isset($title) && $title)
        <div class="card-header widget-title">
            {{ $title }}
        </div>
    @endif

    <div class="card-block widget-body">
        {{ $slot }}
    </div>
</div>