<div class="input-group">
    <span class="input-group-btn">
        <button class="btn btn-secondary" type="button" ng-click="$ctrl.browse()">
            <i ng-if="$ctrl.icon" class="fa" ng-class="'fa-'+$ctrl.icon"></i>
            <span ng-if="!$ctrl.icon">&hellip;</span>
        </button>
    </span>
    <input name="@{{ $ctrl.name }}" type="text" class="form-control" ng-model="$ctrl.icon">
</div>

<!-- Modal -->
<div class="modal fade" ng-attr-id="@{{$ctrl.modalId}}" tabindex="-1" role="dialog" aria-labelledby="@{{$ctrl.modalId}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="@{{$ctrl.modalId}}Label">@lang('general.Icons')</span>
                </h5>
                <div class="input-group" style="width: 250px; display: inline-flex">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input placeholder="@lang('general.Search')..." type="search" ng-model="$ctrl.searchQuery" class="form-control" />
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('general.Close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap icons-grid">
                    <div class="p-2 m-1 text-center icon-item"
                         ng-repeat="icon in $ctrl.icons | filter: {name: $ctrl.searchQuery}"
                         ng-click="$ctrl.select(icon.name)"
                         title="@{{ icon.name }}"
                    >
                        <i class="fa fa-@{{ icon.name }}"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin::messages.Cancel')</button>
            </div>
        </div>
    </div>
</div>