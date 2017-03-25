@if (isset($attr) && is_array($attr))
    <div {!! html_attr($attr) !!}>
@endif

@if (isset($title) && $title)
    <div class="widget-title">
        {{ $title }}
    </div>
@endif

{{ $slot }}

@if (isset($attr) && is_array($attr))
    </div>
@endif
