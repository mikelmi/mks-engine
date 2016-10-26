<div class="form-group row">
    <label class="col-sm-2 col-form-label text-sm-right">@lang('general.Type')</label>
    <div class="col-sm-10">
        <select name="params[type]" class="form-control">
            @foreach($presenters as $type => $title)
                <option value="{{$type}}" @if($type == old('params.type', $model->param('type'))) selected="selected" @endif>
                    {{$title}}
                </option>
            @endforeach
        </select>
    </div>
</div>