<div class="card shd">
    <div class="card-header">
        <div class="btn-group btn-group-sm float-right">
            <a href="#" class="btn btn-outline-secondary" ng-click="$ctrl.refresh()">
                <i class="fa fa-refresh"></i>
            </a>
            <a href="#" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#notifications" aria-expanded="true"
               ng-click="$ctrl.collapsed=!$ctrl.collapsed">
                <i class="fa" ng-class="{'fa-angle-down': $ctrl.collapsed, 'fa-angle-up': !$ctrl.collapsed}"></i>
            </a>
        </div>
        <h5>@lang('general.Last events')</h5>
        <small>
            <span ng-class="{'text-muted': !$ctrl.unreadCount}">@{{ $ctrl.unreadCount }}</span>
            /
            <span class="text-muted">@{{ $ctrl.totalCount }}</span>
        </small>
    </div>

    <div id="notifications" class="collapse show">
        <div class="list-group list-group-flush">
            <a href="#" ng-repeat="item in $ctrl.items" class="list-group-item justify-content-between list-group-item-action" data-toggle="modal" data-target="#notificationDetails" ng-click="$ctrl.details(item)">
                <div class="float-right">
                    <button class="btn btn-sm btn-outline-danger" type="button" title="@lang('admin::messages.Delete')" ng-click="$event.stopPropagation(); $event.preventDefault(); $ctrl.delete(item, '{{trans('admin::messages.Delete')}}?')">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
                <small class="text-muted">@{{ item.created_at }}</small>
                <span ng-class="{'text-muted': item.read_at}">@{{ item.title }}</span>
            </a>
        </div>
    </div>

    <div class="card-footer">
        <button type="button" class="btn btn-sm btn-outline-primary" ng-show="$ctrl.nextUrl" ng-click="$ctrl.load()">
            <i class="fa fa-ellipsis-h"></i> @lang('general.Show more')
        </button>
        <button type="button" class="btn btn-sm btn-outline-warning" ng-click="$ctrl.deleteRead('{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i> @lang('general.Delete read')
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger" ng-click="$ctrl.deleteAll('{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i> @lang('general.Delete all')
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="notificationDetails" tabindex="-1" role="dialog" aria-labelledby="notificationDetailsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="notificationDetailsLabel">@{{ $ctrl.currentItem.title }}</h4>
            </div>
            <div class="modal-body">
                <div ng-bind-html="$ctrl.detailsHtml"></div>
            </div>
        </div>
    </div>
</div>