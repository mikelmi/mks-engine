<div class="form-group row">
    <label class="col-sm-2 col-form-label text-sm-right"> @lang('general.Showing') </label>
    <div class="col-sm-10">
        <select class="form-control" name="@{{$ctrl.name}}" ng-model="$ctrl.showing">
            <option value="">@lang('general.Show on all')</option>
            <option value="1">@lang('general.Show for'):</option>
            <option value="2">@lang('general.Hide for'):</option>
        </select>
    </div>
</div>
<div class="form-group row" ng-hide="!$ctrl.showing">
    <div class="col-sm-10 offset-sm-2">
        @verbatim
            <div class="form-group" ng-repeat="route in $ctrl.routes track by $index">
                <button type="button" class="float-left btn btn-outline-danger btn-sm no-b" ng-click="$ctrl.removeRoute(route)">
                    <i class="fa fa-remove"></i>
                </button>
                <mks-link-select field-route="routes[{{ $index }}]"
                                 field-params="route_params[{{ $index }}]"
                                 model="$parent.$ctrl.routes[{{ $index }}]"
                                 routes="{{ $ctrl.routes[$index] }}"
                >
                </mks-link-select>
                <input type="hidden" name="route_ids[{{ $index }}]" ng-value="route.model_id" />
            </div>
        @endverbatim
        <button type="button" class="btn btn-outline-success btn-sm" ng-click="$ctrl.addRoute()">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>