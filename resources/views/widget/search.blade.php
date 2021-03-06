@component($template, ['title' => $title, 'attr' => $attr])

<form class="form" action="{{ route('search') }}" method="get">
    <div>
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="@lang('general.Search')..." required="required">
            <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
        </div>
    </div>
</form>

@endcomponent