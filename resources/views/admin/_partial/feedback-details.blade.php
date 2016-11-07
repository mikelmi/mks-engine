<div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right">@lang('messages.From')</label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$name}} {{$from}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label text-sm-right"> @lang('general.Message') </label>
        <div class="col-sm-9">
            <p class="form-control-static">{{$message}}</p>
        </div>
    </div>

</div>