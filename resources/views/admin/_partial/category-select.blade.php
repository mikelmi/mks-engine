<div class="row">
    <div class="col-sm-6">
        <select class="form-control" ng-model="$ctrl.section"
                ng-options="item as item.text for item in $ctrl.items">
            <option value="">{[{ $ctrl.sectionEmpty }]}</option>
        </select>
    </div>
    <div class="col-sm-6">
        <select class="form-control" ng-model="$ctrl.category"
                ng-options="item as item.text for item in $ctrl.section.children">
            <option value="">{[{ $ctrl.categoryEmpty }]}</option>
        </select>
    </div>
    <input type="hidden" name="{[{$ctrl.sectionField}]}" ng-value="$ctrl.section.id" />
    <input type="hidden" name="{[{$ctrl.ctaegoryField}]}" ng-value="$ctrl.category.id" />
</div>