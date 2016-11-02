@extends('admin::page')

@section('controller')
    ng-controller="TableCtrl as grid" ng-init="grid.init('{{$data_url}}')"
@endsection

@section('title')
    @lang('general.Pages')
@endsection

@section('right')
    <input class="form-control form-control-search ic-left" type="search" placeholder="@lang('general.Search')..." ng-model="gridQuery" />
@endsection

@section('tools')
    <a class="btn btn-primary" href="#/page/edit">
        <i class="fa fa-plus"></i>
        {{trans('admin::messages.Add')}}
    </a>
    <div class="btn btn-group">
        <button class="btn btn-warning" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Move to trash')" ng-click="grid.removeSelected('{{route('admin::page.toTrash')}}', '{{trans('admin::messages.Move to trash')}}?')">
            <i class="fa fa-trash"></i>
        </button>
        <button class="btn btn-danger" ng-disabled="!grid.hasSelected" title="@lang('admin::messages.Delete Selected')" ng-click="grid.removeSelected('{{route('admin::page.delete')}}', '{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i>
        </button>
    </div>
@endsection

@section('content')
    <div class="card shd">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-xs-left">
                <li class="nav-item">
                    <a class="nav-link @if($scope != 'trash') active @endif " href="#/page">@lang('general.Pages') <span class="tag tag-pill tag-default">{[{ page.model.pages_count }]}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($scope == 'trash') active @endif " href="#/page/trash">@lang('admin::messages.Trash') <span class="tag tag-pill tag-default">{[{ page.model.trash_count }]}</span></a>
                </li>
            </ul>
        </div>

        <div class="card-block">
            @section('grid')
                <table class="table table-grid table-hover table-sm" st-pipe="grid.pipeServer" st-table="grid.rows">
                    <thead mst-watch-query="gridQuery">
                    <tr class="thead-default">
                        <th mst-select-all-rows="grid.rows"> </th>
                        <th st-sort="id" class="st-sortable">#</th>
                        <th st-sort="title" class="st-sortable">@lang('general.Title')</th>
                        <th st-sort="lang" class="st-sortable text-xs-center">@lang('general.Language')</th>
                        <th st-sort="path" class="st-sortable">URL</th>
                        <th st-sort="created_at" class="st-sortable">@lang('admin::messages.Created at')</th>
                        <th> </th>
                    </tr>
                    <tr>
                        <th><!-- checkbox --></th>
                        <th><!-- id --></th>
                        <th><!-- title -->
                            <input st-search="name" data-placeholder="@lang('general.Title')" class="form-control form-control-sm form-block" type="search"/>
                        </th>
                        <th><!-- lang -->
                            <select class="form-control form-block" st-search="lang">
                                <option value=""> </option>
                                @foreach($languages as $lang)
                                    <option value="{{$lang->iso}}">{{$lang->title}}</option>
                                @endforeach
                            </select>
                        </th>
                        <th><!-- path -->
                            <input st-search="path" data-placeholder="@lang('general.Path')" class="form-control form-control-sm form-block" type="search"/>
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
                        <td class="text-xs-center">
                            <img ng-if="row.lang" alt="" src="{{$lang_icon_url}}/{[{row.lang}]}" />
                            {[{row.lang}]}
                        </td>
                        <td>
                            <a href="{{route('page')}}/{[{row.path}]}" target="_blank">{[{row.path}]}</a>
                        </td>
                        <td>{[{row.created_at}]}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary" href="#/page/edit/{[{row.id}]}" title="@lang('admin::messages.Edit')"><i class="fa fa-pencil"></i></a>
                                <button class="btn btn-outline-warning" ng-click="grid.removeRow(row,'{{route('admin::page.toTrash')}}/'+row.id,'@lang('admin::messages.Move to trash')?')" title="@lang('admin::messages.Move to trash')">
                                    <i class="fa fa-trash"></i>
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
                        <td colspan="7">
                            <div class="pull-left text-muted">
                                {[{ grid.start }]} - {[{ grid.end }]} / {[{ grid.total }]}<br />
                                @lang('general.Selected_s'): {[{ grid.hasSelected }]}
                            </div>
                            <div class="pull-right" st-pagination="" st-items-by-page="10"></div>
                            <div class="clearfix"></div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            @show
        </div>
    </div>
@endsection