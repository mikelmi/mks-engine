@component($template, ['title' => $title, 'attr' => $attr])

<ul class="nav navbar-nav">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            {!! $current->iconImage() !!} {{$current->getIso()}}
        </a>
        <div class="dropdown-menu">
            @foreach($languages as $lang)
                <a class="dropdown-item" href="{{route('lang.change', $lang->getIso())}}">
                    {!! $lang->iconImage() !!}
                    {{$lang->getTitle()}}
                </a>
            @endforeach
        </div>
    </li>
</ul>

@endcomponent