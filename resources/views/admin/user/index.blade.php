@extends('admin::page')

@section('controller')
    ng-controller="TableCtrl as grid" ng-init="grid.init('{{route('admin::users.data')}}')"
@endsection

@section('title')
    @lang('a.Users')
@endsection

@section('right')
    <input class="form-control form-control-search ic-left" type="search" placeholder="@lang('a.Search')..." ng-model="gridQuery" />
@endsection

@section('tools')
    <a class="btn btn-primary" href="#/user/edit">
        <i class="fa fa-plus"></i>
        {{trans('admin::messages.Add')}}
    </a>
    <div class="btn btn-group">
        <button class="btn btn-success" ng-disabled="!grid.hasSelected" title="@lang('Activate')" ng-click="grid.updateSelected('{{route('admin::user.toggleBatch', [1])}}')">
            <i class="fa fa-check"></i>
        </button>
        <button class="btn btn-warning" ng-disabled="!grid.hasSelected" title="@lang('Deactivate')" ng-click="grid.updateSelected('{{route('admin::user.toggleBatch', [0])}}')">
            <i class="fa fa-minus"></i>
        </button>
        <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::user.delete')}}', '{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i>
        </button>
    </div>
@endsection

@section('content')
    <div class="card shd">
        <div class="card-block">
            <table class="table table-grid table-hover table-sm" st-pipe="grid.pipeServer" st-table="grid.rows">
                <thead mst-watch-query="gridQuery">
                <tr class="thead-default">
                    <th mst-select-all-rows="grid.rows"> </th>
                    <th st-sort="id" class="st-sortable">#</th>
                    <th st-sort="users.name" class="st-sortable">@lang('a.Name')</th>
                    <th st-sort="email" class="st-sortable">Email</th>
                    <th st-sort="active" class="st-sortable">@lang('admin::messages.Status')</th>
                    <th st-sort="rolesList" class="st-sortable">@lang('a.Roles')</th>
                    <th st-sort="users.created_at" class="st-sortable">@lang('admin::messages.Created at')</th>
                    <th> </th>
                </tr>
                <tr>
                    <th><!-- checkbox --></th>
                    <th><!-- id --></th>
                    <th><!-- name -->
                        <input st-search="name" data-placeholder="@lang('a.Name')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- email -->
                        <input st-search="email" data-placeholder="Email" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- status -->
                        <select st-search="active" class="form-control form-block">
                            <option value=""></option>
                            <option value="1">@lang('admin::messages.Active')</option>
                            <option value="0">@lang('admin::messages.Inactive')</option>
                        </select>
                    </th>
                    <th><!-- Roles -->
                        <input st-search="rolesList" data-placeholder="@lang('a.Roles')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- created_at -->
                        <input st-search="created_at" data-placeholder="@lang('admin::messages.Created at')" class="form-control form-control-sm form-block" type="date"/>
                    </th>
                    <th class="st-actions-th"><!-- actions --></th>
                </tr>
                </thead>

                <tbody>
                <tr ng-repeat="row in grid.rows" ng-class="{'table-success': row.is_current}">
                    <td mst-select-row="row"></td>
                    <td>{[{row.id}]}</td>
                    <td>{[{row.name}]}</td>
                    <td>{[{row.email}]}</td>
                    <td class="text-xs-center">
                        <button class="btn btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::user.toggle') }}/'+row.id)" title="@lang('admin::messages.Activate')/@lang('admin::messages.Deactivate')" ng-class="{'btn-success':row.active,'btn-warning':!row.active}" ng-disabled="row.is_current">
                            <i class="fa" ng-class="{'fa-check':row.active,'fa-minus':!row.active}"></i>
                        </button>
                    </td>
                    <td>{[{row.rolesList}]}</td>
                    <td>{[{row.created_at}]}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-primary" href="#/user/edit/{[{row.id}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                            <button ng-if="!row.is_current" class="btn btn-outline-danger" ng-click="grid.removeRow(row,'{{route('admin::user.delete')}}/'+row.id,'@lang('admin::messages.Delete')?')" title="@lang('admin::messages.Delete')">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>

                <tfoot>

                <tr>
                    <td colspan="8">
                        <div class="pull-left text-muted">
                            {[{ grid.start }]} - {[{ grid.end }]} / {[{ grid.total }]}<br />
                            @lang('a.Selected_s'): {[{ grid.hasSelected }]}
                        </div>
                        <div class="pull-right" st-pagination="" st-items-by-page="10"></div>
                        <div class="clearfix"></div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection