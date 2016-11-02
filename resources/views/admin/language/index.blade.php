@extends('admin::page')

@section('controller')
    ng-controller="TableCtrl as grid" ng-init="grid.init('{{route('admin::languages.data')}}', 'iso')"
@endsection

@section('title')
    @lang('general.Languages')
@endsection

@section('right')
    <input class="form-control form-control-search ic-left" type="search" placeholder="@lang('general.Search')..." ng-model="gridQuery" />
@endsection

@section('tools')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLangModal">
        <i class="fa fa-plus"></i>
        {{trans('admin::messages.Add')}}
    </button>
    <div class="btn btn-group">
        <button class="btn btn-success" ng-disabled="!grid.hasSelected" title="@lang('Activate')" ng-click="grid.updateSelected('{{route('admin::language.toggleBatch', [1])}}')">
            <i class="fa fa-check"></i>
        </button>
        <button class="btn btn-warning" ng-disabled="!grid.hasSelected" title="@lang('Deactivate')" ng-click="grid.updateSelected('{{route('admin::language.toggleBatch', [0])}}')">
            <i class="fa fa-minus"></i>
        </button>
        <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::language.delete')}}', '{{trans('admin::messages.Delete')}}?')">
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
                    <th st-sort="iso" class="st-sortable">ISO</th>
                    <th st-sort="name" class="st-sortable">@lang('general.Name')</th>
                    <th st-sort="title" class="st-sortable">@lang('general.Title')</th>
                    <th st-sort="enabled" class="st-sortable text-xs-center">@lang('admin::messages.Status')</th>
                    <th> </th>
                </tr>
                <tr>
                    <th><!-- checkbox --></th>
                    <th><!-- iso --></th>
                    <th><!-- name -->
                        <input st-search="name" data-placeholder="@lang('general.Name')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- title -->
                        <input st-search="title" data-placeholder="@lang('general.Title')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- enabled -->
                        <select st-search="enabled" class="form-control form-block">
                            <option value=""></option>
                            <option value="1">@lang('admin::messages.Active')</option>
                            <option value="0">@lang('admin::messages.Inactive')</option>
                        </select>
                    </th>
                    <th class="st-actions-th"><!-- actions --></th>
                </tr>
                </thead>

                <tbody>
                <tr ng-repeat="row in grid.rows" ng-class="{'table-warning': !row.enabled, 'table-info': row.default}">
                    <td mst-select-row="row"></td>
                    <td><img ng-src="{[{ row.icon }]}" /> {[{row.iso}]}</td>
                    <td>{[{row.name}]}</td>
                    <td>{[{row.title}]}</td>
                    <td class="text-xs-center">
                        <button class="btn btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::language.toggle') }}/'+row.iso)" title="@lang('admin::messages.Activate')/@lang('admin::messages.Deactivate')" ng-class="{'btn-success':row.enabled,'btn-warning':!row.enabled}">
                            <i class="fa" ng-class="{'fa-check':row.enabled,'fa-minus':!row.enabled}"></i>
                        </button>
                        <button class="btn btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::language.setDefault') }}/'+row.iso)" title="@lang('general.Default')" ng-class="{'btn-info':row.default,'btn-outline-secondary':!row.default}">
                            <i class="fa fa-star"></i>
                        </button>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-primary" href="#/language/edit/{[{row.iso}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                            <button class="btn btn-outline-danger" ng-click="grid.removeRow(row,'{{route('admin::language.delete')}}/'+row.iso,'@lang('admin::messages.Delete')?')" title="@lang('admin::messages.Delete')">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>

                <tfoot>

                <tr>
                    <td colspan="6">
                        <div class="pull-left text-muted">
                            {[{ grid.start }]} - {[{ grid.end }]} / {[{ grid.total }]}<br />
                            @lang('general.Selected_s'): {[{ grid.hasSelected }]}
                        </div>
                        <div class="pull-right"></div>
                        <div class="clearfix"></div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addLangModal" role="dialog" aria-labelledby="addLangModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('admin::messages.Cancel')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addLangModalLabel">@lang('general.Add Language')</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admin::language.add')}}" mks-form id="addLangForm">
                        <div class="form-group row">
                            <label class="col-xs-2 col-form-label">@lang('general.Language')</label>
                            <div class="col-xs-10 row-block">
                                <select name="language" class="form-control" mks-select
                                        data-url="{{route('admin::languages.all')}}"
                                        data-lang-icon="{{route('lang.icon')}}"
                                >
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" mks-submit="#addLangForm">@lang('admin::messages.Add')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin::messages.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endsection