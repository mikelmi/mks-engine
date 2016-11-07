<div class="images-picker image-select">
    <div class="img-thumbnail img-wrap">
        <img ng-src="{{route('thumbnail')}}/{[{$ctrl.image}]}?p=picker" class="img-fluid" ng-if="$ctrl.image" />
        <div class="btn-group btn-group-sm img-tools">
            <button type="button" class="btn btn-outline-primary" ng-click="$ctrl.browse()">
                <i class="fa fa-pencil"></i>
            </button>
            <button type="button" class="btn btn-danger" ng-click="$ctrl.clear()">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<input type="hidden" ng-value="$ctrl.image" name="{[{ $ctrl.inputName }]}" />