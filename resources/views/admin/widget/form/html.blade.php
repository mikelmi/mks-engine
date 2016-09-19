<div class="form-group row" ng-class="{'has-danger':page.errors.content}">
    <label class="col-sm-12 col-form-label"> @lang('a.Text') </label>
    <div class="col-sm-12">
        <textarea name="content" class="form-control" rows="5" mks-editor>{{ old('content', $model->content) }}</textarea>
        <small class="form-control-feedback" ng-show="page.errors.content">{[{page.errors.content[0]}]}</small>
    </div>
</div>