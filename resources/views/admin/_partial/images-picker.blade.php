<div class="images-picker">
    <div class="img-thumbnail img-wrap pull-left" ng-repeat="item in $ctrl.items" ng-class="{'featured': item.main}">
        <img ng-src="{[{item.url}]}" class="img-fluid" />
        <div class="btn-group btn-group-sm img-tools">
            <button ng-if="$ctrl.pickMain" type="button" class="btn btn-secondary" ng-click="$ctrl.setMain(item)" ng-class="{'btn-warning': item.main}">
                <i class="fa fa-star"></i>
            </button>
            <button type="button" class="btn btn-danger" ng-click="$ctrl.delete(item)">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<input type="hidden" ng-value="$ctrl.itemsValue()" name="{[{ $ctrl.inputName }]}" />

<button type="button" class="btn btn-outline-primary" ng-click="$ctrl.add()">
    <i class="fa fa-plus">
    @lang('admin::messages.Add')
</button>