@component($template, ['title' => $title, 'attr' => $attr])
    <?php
        $sliderId = 'carouselSlider-' . $model->id;
    ?>
    <div id="{{$sliderId}}carouselSlider" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
            @foreach($model->param('slides', []) as $slide)
                <div class="carousel-item @if($loop->first) active @endif">
                    <img class="d-block img-fluid" src="{{asset($slide['image'])}}" alt="">
                    @if ($slide['content'])
                        <div class="carousel-caption d-none d-md-block">
                            <div class="cp-desc">
                                {!! $slide['content'] !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#{{$sliderId}}" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#{{$sliderId}}" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@endcomponent