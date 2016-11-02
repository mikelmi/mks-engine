@if(!$empty)
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-home"></i></a></li>
        @foreach($items as $item)
            <li class="breadcrumb-item">
                @if ($item['url'])
                    <a href="{{$item['url']}}">{{$item['title']}}</a>
                @else
                    {{$item['title']}}
                @endif
            </li>
        @endforeach
    </ol>
@endif