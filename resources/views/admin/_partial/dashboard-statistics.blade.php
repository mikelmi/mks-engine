<div class="card shd">
    <div class="card-header">
        <div class="btn-group btn-group-sm float-right">
            <a href="#" class="btn btn-outline-secondary" ng-click="$ctrl.load()">
                <i class="fa fa-refresh"></i>
            </a>
            <a href="#" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#statistics" aria-expanded="true"
               ng-click="$ctrl.collapsed=!$ctrl.collapsed">
                <i class="fa" ng-class="{'fa-angle-down': $ctrl.collapsed, 'fa-angle-up': !$ctrl.collapsed}"></i>
            </a>
        </div>
        <h5>@lang('general.Statistics')</h5>
    </div>

    <div class="collapse show" id="statistics">
        <div class="list-group list-group-flush">
            <a href="@{{ item.url }}" ng-repeat="item in $ctrl.items" class="list-group-item justify-content-between list-group-item-action">
                @{{ item.title }}
                <span class="badge badge-default badge-pill">@{{ item.count }}</span>
            </a>
        </div>
    </div>
</div>