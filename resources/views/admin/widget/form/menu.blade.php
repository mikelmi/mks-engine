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