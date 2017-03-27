@php
    /** @var \App\Contracts\NestedMenuInterface $item */

    $li_attr = ['class' => $item->getDepth() > 1 ? 'dropdown-item' : 'nav-item'];
    $a_attr = [
        'href' => $item->getUrl(),
        'class' => $item->getDepth() > 1 ?  'dropdown-toggle' : 'nav-link'
    ];

    if ($item->hasChildren()) {
        $li_attr['class'] .= ' dropdown';
    }

    if ($item->isCurrent()) {
        $a_attr['class'] .= ' active';
    }
@endphp

<li{!! html_attr($li_attr) !!}>
    <a{!! html_attr($a_attr) !!}>{!! $item->getTitle() !!}</a>

    @if($item->hasChildren())
        <ul class="dropdown-menu">
            @each('widget.nav.item', $item->getChildren(), 'item')
        </ul>
    @endif
</li>