<div ng-init="slides={{$field->getValue()}};lastId=0;"></div>

<ul class="nav nav-tabs pull-xs-left">
    <li class="nav-item d-flex justify-content-between align-items-center" ng-repeat="slide in slides track by $index">
        <a class="nav-link" role="tab" data-toggle="tab" ng-href="#slide-@{{ $index }}">@lang('general.Slide') @{{ $index+1 }}
        </a>
        <i class="fa fa-remove"></i>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" title="@lang('admin::messages.Add')" ng-click="slides.push({'image': '', 'content':''});">
            <i class="fa fa-plus"></i>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane" role="tabpanel" ng-repeat="slide in slides track by $index" id="slide-@{{ $index }}">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-sm-right">
                @lang('general.Slide') @{{ $index+1 }}
                <button type="button" class="btn btn-sm btn-danger" ng-click="slides.splice(slides.indexOf(slide),1)"><i class="fa fa-remove"></i></button>
            </label>
            <div class="col-sm-10">
                <div class="pull-left">
                    <mks-image-select name="params[slides][@{{$index}}][image]" image="slide.image" id="slide-id-@{{$index}}"></mks-image-select>
                </div>
                <div style="margin-left: 160px;">
                    <textarea mks-data-id="slide-content-@{{$index}}" name="params[slides][@{{$index}}][content]" class="form-control" rows="5" mks-editor ng-model="slide.content"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>