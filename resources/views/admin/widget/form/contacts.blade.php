<div class="form-group row" ng-class="{'has-danger':page.errors['params.email']}">
    <label class="col-sm-2 col-form-label text-sm-right"> E-mail </label>
    <div class="col-sm-10">
        <input type="email" name="params[email]" class="form-control" value="{{ $model->param('email') }}" />
        <small class="form-control-feedback" ng-show="page.errors['params.email']">{[{page.errors['params.email'][0]}]}</small>
    </div>
</div>

<div class="form-group row" ng-class="{'has-danger':page.errors.content}">
    <label class="col-sm-2 col-form-label text-sm-right"> @lang('general.Text') </label>
    <div class="col-sm-10">
        <textarea name="content" class="form-control" rows="5" mks-editor="{allowedContent: true}">{{ $model->content }}</textarea>
        <small class="form-control-feedback" ng-show="page.errors.content">{[{page.errors.content[0]}]}</small>
    </div>
</div>