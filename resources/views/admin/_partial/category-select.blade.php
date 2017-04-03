<div class="row">
    <div class="col-sm-6">
        <select class="form-control" ng-model="$ctrl.section"
                ng-options="item as item.text for item in $ctrl.items">
            <option value="">@{{ $ctrl.sectionEmpty }}</option>
        </select>
    </div>
    <div class="col-sm-6">
        <select class="form-control" ng-model="$ctrl.category"
                ng-options="cat as cat.text for cat in $ctrl.section.children">
            <option value="">@{{ $ctrl.categoryEmpty }}</option>
        </select>
    </div>
    <input type="hidden" name="@{{$ctrl.sectionField}}" ng-value="$ctrl.section.id" />
    <input type="hidden" name="@{{$ctrl.categoryField}}" ng-value="$ctrl.category.id" />
</div>