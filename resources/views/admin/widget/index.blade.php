@extends('admin::page')

@section('controller')
    ng-controller="TableCtrl as grid" ng-init="grid.init('{{route('admin::widgets.data')}}')"
@endsection

@section('title')
    @lang('a.Widgets')
@endsection

@section('right')
    <input class="form-control form-control-search ic-left" type="search" placeholder="@lang('a.Search')..." ng-model="gridQuery" />
@endsection

@section('tools')
    <div class="btn-group">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('admin::messages.Add')
            </button>
            <div class="dropdown-menu">
                @foreach($types as $class => $title)
                    <a class="dropdown-item" href="#/widget/add/{{urlencode($class)}}">{{$title}}</a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="btn btn-group">
        <button class="btn btn-success" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Activate')" ng-click="grid.updateSelected('{{route('admin::widget.toggleBatch', 1)}}')">
            <i class="fa fa-check"></i>
        </button>
        <button class="btn btn-warning" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Deactivate')" ng-click="grid.updateSelected('{{route('admin::widget.toggleBatch', 0)}}')">
            <i class="fa fa-minus"></i>
        </button>
        <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::widget.delete')}}', '{{trans('admin::messages.Delete')}}?')">
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
                    <th st-sort="title" class="st-sortable">@lang('a.Title')</th>
                    <th st-sort="class" class="st-sortable">@lang('a.Type')</th>
                    <th st-sort="status" class="st-sortable">@lang('admin::messages.Status')</th>
                    <th st-sort="position" class="st-sortable">@lang('a.Position')</th>
                    <th st-sort="ordering" class="st-sortable">@lang('a.Order')</th>
                    <th st-sort="lang" class="st-sortable">@lang('a.Language')</th>
                    <th> </th>
                </tr>
                <tr>
                    <th><!-- checkbox --></th>
                    <th><!-- id --></th>
                    <th><!-- title -->
                        <input st-search="title" data-placeholder="@lang('a.Title')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- class -->
                        <select st-search="class" class="form-control form-control-sm form-control-block">
                            <option value=""> </option>
                            @foreach($types as $class => $title)
                                <option value="{{$class}}">{{$title}}</option>
                            @endforeach
                        </select>
                    </th>
                    <th><!-- status -->
                        <select st-search="status" class="form-control form-control-sm form-control-block">
                            <option value=""> </option>
                            <option value="1">@lang('admin::messages.Active')</option>
                            <option value="0">@lang('admin::messages.Inactive')</option>
                        </select>
                    </th>
                    <th><!-- title -->
                        <input st-search="position" data-placeholder="@lang('a.Position')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- ordering -->
                        <input st-search="ordering" data-placeholder="@lang('a.Order')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th><!-- lang -->
                        <input st-search="lang" data-placeholder="@lang('a.Language')" class="form-control form-control-sm form-block" type="search"/>
                    </th>
                    <th class="st-actions-th"><!-- actions --></th>
                </tr>
                </thead>

                <tbody>
                <tr ng-repeat="row in grid.rows" ng-class="{'table-success': row.is_system}">
                    <td mst-select-row="row"></td>
                    <td>{[{row.id}]}</td>
                    <td>{[{row.title}]}</td>
                    <td>{[{row.class_title}]}</td>
                    <td class="text-xs-center">
                        <button class="btn btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::widget.toggle') }}/'+row.id)" title="@lang('admin::messages.Activate')/@lang('admin::messages.Deactivate')" ng-class="{'btn-success':row.status,'btn-warning':!row.status}">
                            <i class="fa" ng-class="{'fa-check':row.status,'fa-minus':!row.status}"></i>
                        </button>
                    </td>
                    <td>{[{row.position}]}</td>
                    <td class="text-xs-center">
                        <button class="btn btn-secondary btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::widget.move') }}/'+row.id+'/1')">
                            <i class="fa fa-angle-down"></i>
                        </button>
                        {[{row.ordering}]}
                        <button class="btn btn-secondary btn-sm" ng-click="grid.updateRow(row,'{{ route('admin::widget.move') }}/'+row.id)">
                            <i class="fa fa-angle-up"></i>
                        </button>
                    </td>
                    <td>
                        <img ng-if="row.lang" alt="" src="{{$lang_icon_url}}/{[{row.lang}]}" />
                        {[{row.lang}]}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-primary" href="#/widget/edit/{[{row.id}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                            <button ng-if="!row.is_system" class="btn btn-outline-danger" ng-click="grid.removeRow(row,'{{route('admin::widget.delete')}}/'+row.id,'@lang('admin::messages.Delete')?')" title="@lang('admin::messages.Delete')">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>

                <tfoot>

                <tr>
                    <td colspan="9">
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