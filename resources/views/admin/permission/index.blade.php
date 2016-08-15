@extends('admin::page')

@section('controller')
    ng-controller="TableCtrl as grid" ng-init="grid.init('{{route('admin::permissions.data')}}')"
@endsection

@section('title')
    @lang('a.Permissions')
@endsection

@section('right')
    <input class="form-control form-control-search ic-left" type="search" placeholder="@lang('a.Search')..." ng-model="gridQuery" />
@endsection

@section('tools')
    <a class="btn btn-primary" href="#/permission/edit">
        <i class="fa fa-plus"></i>
        {{trans('admin::messages.Add')}}
    </a>
    <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::permission.delete')}}', '{{trans('admin::messages.Delete')}}?')">
        <i class="fa fa-remove"></i>
    </button>
@endsection

@section('content')
    <div class="card shd">
        <div class="card-block">
            <table class="table table-grid table-hover table-sm" st-pipe="grid.pipeServer" st-table="grid.rows">
                <thead mst-watch-query="gridQuery">
                <tr class="thead-default">
                    <th mst-select-all-rows="grid.rows"> </th>
                    <th st-sort="id" class="st-sortable">#</th>
                    <th st-sort="name" class="st-sortable">@lang('a.Title')</th>
                    <th st-sort="display_name" class="st-sortable">@lang('a.Display Title')</th>
                    <th> </th>
                </tr>
                <tr>
                    <th><!-- checkbox --></th>
                    <th><!-- id --></th>
                    <th><!-- name -->
                        <input st-search="name" data-placeholder="@lang('a.Name')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- display_name -->
                        <input st-search="display_name" data-placeholder="@lang('a.Display Title')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th class="st-actions-th"><!-- actions --></th>
                </tr>
                </thead>

                <tbody>
                <tr ng-repeat="row in grid.rows" ng-class="{'table-success': row.is_system}">
                    <td mst-select-row="row"></td>
                    <td>{[{row.id}]}</td>
                    <td>{[{row.name}]}</td>
                    <td>{[{row.display_name}]}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-primary" href="#/permission/edit/{[{row.id}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                            <button ng-if="!row.is_system" class="btn btn-outline-danger" ng-click="grid.removeRow(row,'{{route('admin::permission.delete')}}/'+row.id,'@lang('admin::messages.Delete')?')" title="@lang('admin::messages.Delete')">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>

                <tfoot>

                <tr>
                    <td colspan="5">
                        <div class="pull-left text-muted">
                            {[{ grid.start }]} - {[{ grid.end }]} / {[{ grid.total }]}<br />
                            @lang('Selected_s'): {[{ grid.hasSelected }]}
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