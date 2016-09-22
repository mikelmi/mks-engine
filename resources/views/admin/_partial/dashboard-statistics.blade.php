<div class="card shd">
    <div class="card-header">
        <div class="btn-group btn-group-sm pull-xs-right">
            <a href="#" class="btn btn-outline-secondary" ng-click="$ctrl.load()">
                <i class="fa fa-refresh"></i>
            </a>
            <a href="#" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#statistics"
               ng-click="$ctrl.collapsed=!$ctrl.collapsed"
            >
                <i class="fa" ng-class="{'fa-angle-down': $ctrl.collapsed, 'fa-angle-up': !$ctrl.collapsed}"></i>
            </a>
        </div>
        <h5>@lang('a.Statistics')</h5>
    </div>

    <div class="list-group collapse in" id="statistics">
        <a href="{[{ item.url }]}" ng-repeat="item in $ctrl.items" class="list-group-item list-group-item-action">
            <span class="tag tag-default tag-pill pull-xs-right">{[{ item.count }]}</span>
            <span>{[{ item.title }]}</span>
        </a>
    </div>
</div>