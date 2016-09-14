<div class="row">
    <div class="col-sm-6">
        <select class="form-control" ng-model="routeOption"
                ng-options="item as item.text for item in items">
            <option value="">{[{ emptyTitle }]}</option>
        </select>
    </div>
    <div class="col-sm-6">
        <input class="form-control" placeholder="URL" ng-if="rawEnabled && !routeOption" type="text" name="{[{ field.raw }]}" ng-model="route.raw" />
        <div class="input-group" ng-hide="!routeOption.hasParams">
            <input type="text" class="form-control" ng-value="paramsVisible(routes[routeOption.id])" readonly />
            <span class="input-group-btn">
                <button type="button" class="btn btn-info" ng-disabled="!routeOption" ng-click="modal.open()">@lang('a.Select')...</button>
            </span>
        </div>
    </div>
</div>
<input type="hidden" name="{[{field.route}]}" ng-value="routeOption.id" />
<input type="hidden" name="{[{field.params}]}" ng-value="paramsEncoded(routes[routeOption.id].params)" />

<!-- Modal -->
<div class="modal fade" ng-attr-id="{[{modal.id}]}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('a.Close')">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" ng-if="routeOption.extended">
                    <span>{[{ modal.data.title }]}&nbsp;&nbsp;&nbsp;</span>
                    <div class="input-group" style="width: 250px; display: inline-flex">
                        <input placeholder="@lang('a.Search')..." type="search" ng-model="modal.searchQuery" class="form-control" />
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="submit" ng-click="$event.preventDefault(); modal.search(modal.searchQuery)"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </h4>
            </div>
            <div class="modal-body">
                <div ng-if="routeOption.extended">
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th ng-repeat="(name, title) in modal.data.columns">{[{ title }]}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="item in modal.data.items" ng-click="modal.select(item)" style="cursor:pointer">
                            <td ng-repeat="(key, title) in modal.data.columns">{[{ item[key] }]}</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row flex-items-xs-center" ng-if="modal.data.pagination.last_page">
                        <div class="input-group" style="width: 150px">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-secondary" ng-disabled="modal.current_page < 2" ng-click="modal.prevPage()">&laquo;</button>
                            </span>
                            <input ng-model="modal.current_page" type="number" class="form-control text-xs-center" min="1" max="{[{ modal.data.pagination.last_page }]}" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-secondary" ng-disabled="modal.current_page > modal.data.pagination.last_page - 1" ng-click="modal.nextPage()">&raquo;</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div ng-if="!routeOption.extended">
                    <div class="form-group row" ng-repeat="param in routeOption.params">
                        <label class="col-sm-2 col-form-label form-control-label text-sm-right">{[{ param }]}</label>
                        <div class="col-sm-10">
                            <input type="text" ng-model="modal.form[param]" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button ng-if="!routeOption.extended" type="button" class="btn btn-primary" ng-click="modal.save()">@lang('admin::messages.Save')</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin::messages.Cancel')</button>
            </div>
        </div>
    </div>
</div>