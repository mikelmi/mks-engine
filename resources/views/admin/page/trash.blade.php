@extends('admin.page.index')

@section('tools')
    <div class="btn btn-group">
        <button class="btn btn-success" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Restore')" ng-click="grid.removeSelected('{{route('admin::page.restore')}}', '{{trans('admin::messages.Restore')}}?')">
            <i class="fa fa-arrow-circle-o-up"></i>
        </button>
        <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::page.delete')}}', '{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i>
        </button>
    </div>
@endsection

@section('grid')
    <table class="table table-grid table-hover table-sm" st-pipe="grid.pipeServer" st-table="grid.rows">
        <thead mst-watch-query="gridQuery">
        <tr class="thead-default">
            <th mst-select-all-rows="grid.rows"> </th>
            <th st-sort="id" class="st-sortable">#</th>
            <th st-sort="title" class="st-sortable">@lang('a.Title')</th>
            <th st-sort="path" class="st-sortable">@lang('a.Path')</th>
            <th st-sort="created_at" class="st-sortable">@lang('admin::messages.Created at')</th>
            <th> </th>
        </tr>
        <tr>
            <th><!-- checkbox --></th>
            <th><!-- id --></th>
            <th><!-- title -->
                <input st-search="name" data-placeholder="@lang('a.Title')" class="form-control form-control-sm form-block" type="search"/>
            </th>
            <th><!-- path -->
                <input st-search="path" data-placeholder="@lang('a.Path')" class="form-control form-control-sm form-block" type="search"/>
            </th>
            <th><!-- created_at -->
                <input st-search="created_at" data-placeholder="@lang('admin::messages.Created at')" class="form-control form-control-sm form-block" type="date"/>
            </th>
            <th class="st-actions-3"><!-- actions --></th>
        </tr>
        </thead>

        <tbody>
        <tr ng-repeat="row in grid.rows" ng-class="{'table-success': row.is_current}">
            <td mst-select-row="row"></td>
            <td>{[{row.id}]}</td>
            <td>{[{row.title}]}</td>
            <td>{[{row.path}]}</td>
            <td>{[{row.created_at}]}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-primary" href="#/page/edit/{[{row.id}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                    <button class="btn btn-outline-success" ng-click="grid.removeRow(row,'{{route('admin::page.restore')}}/'+row.id,'@lang('admin::messages.Restore')?')" title="@lang('admin::messages.Restore')">
                        <i class="fa fa-arrow-circle-o-up"></i>
                    </button>
                    <button class="btn btn-outline-danger" ng-click="grid.removeRow(row,'{{route('admin::page.delete')}}/'+row.id,'@lang('admin::messages.Delete')?')" title="@lang('admin::messages.Delete')">
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
@endsection