@component($template, ['title' => $title, 'attr' => $attr])

    <div class="photo-gallery">
        @foreach($photos as $photo)
            <img src="{{route('image.proxy', $photo['url'])}}" width="100" height="100">
        @endforeach
    </div>

@endcomponent