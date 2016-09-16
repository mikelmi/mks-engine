<div class="form-group row" ng-class="{'has-danger':page.errors.content}">
    <label class="col-sm-2 col-form-label form-control-label"> @lang('a.Menu') </label>
    <div class="col-sm-10">
        <select name="content" class="form-control">
            @foreach($menu as $item)
                <option value="{{$item->id}}" @if($item->id == old('content', $model->content)) selected="selected" @endif>
                    {{$item->name}}
                </option>
            @endforeach
        </select>
        <small class="form-control-feedback" ng-show="page.errors.content">{[{page.errors.content[0]}]}</small>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label form-control-label">@lang('a.Type')</label>
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