<div ng-init="slides={{$field->getValue()}};lastId=0;"></div>
<div class="form-group row" ng-repeat="slide in slides track by $index">
    <label class="col-sm-2 col-form-label text-sm-right">
        @lang('general.Slide') @{{ $index+1 }}
        <button type="button" class="btn btn-sm btn-danger" ng-click="slides.splice(slides.indexOf(slide),1)"><i class="fa fa-remove"></i></button>
    </label>
    <div class="col-sm-10">
        <div class="pull-left">
            <mks-image-select name="params[slides][@{{$index}}][image]" image="@{{ slide.image }}" id="slide-id-@{{$index}}"></mks-image-select>
        </div>
        <div style="margin-left: 160px;">
            <textarea mks-data-id="slide-content-@{{$index}}" name="params[slides][@{{$index}}][content]" class="form-control" rows="5" mks-editor ng-model="slide.content"></textarea>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-10 offset-sm-2">
        <button type="button" class="btn btn-success" ng-click="slides.push({'image': '', 'content':''});">
            <i class="fa fa-plus"></i> @lang('general.Add Slide')
        </button>
    </div>
</div>