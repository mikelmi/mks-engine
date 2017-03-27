@verbatim
<div class="form-inline" ng-repeat="item in $ctrl.items track by $index">
    <button type="button" class="btn btn-outline-danger btn-sm no-b mb-2 mr-sm-2 mb-sm-0" ng-click="$ctrl.remove(item)">
        <i class="fa fa-remove"></i>
    </button>
    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="key" size="15" ng-model="item.id">
    <label class="mr-sm-2">=</label>
    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="value" size="30" ng-model="item.value" ng-attr-name="{{$ctrl.nameValue(item)}}">
</div>
<button type="button" class="btn btn-outline-success btn-sm" ng-click="$ctrl.add()">
    <i class="fa fa-plus"></i>
</button>
@endverbatim