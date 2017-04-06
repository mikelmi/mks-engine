@extends('admin::page')

@section('title')
    @lang('general.Menu')
@endsection

@section('controller')
    ng-controller="MenuController" ng-init="init({{$scope}})"
@endsection

@section('tools')
    @can('admin.menu.edit')
        @can('admin.menu.edit')
            <a ng-if="currentMenu.id" class="btn btn-secondary" ng-href="#/menuman/items/@{{currentMenu.id}}/edit">
                <i class="fa fa-plus"></i>
                @lang('general.Add Menu Item')
            </a>
        @endcan
        <button class="btn btn-secondary" ng-hide="menuModel" ng-click="addMenu()">
            <i class="fa fa-plus"></i>
            {{trans('general.Add Menu')}}
        </button>
        <button class="btn btn-primary" ng-show="currentMenu.id" ng-click="editMenu(currentMenu)">
            <i class="fa fa-pencil"></i>
            {{trans('admin::messages.Edit')}}
        </button>
        <button class="btn btn-primary" ng-if="menuModel" ng-click="saveMenu()" ng-disabled="menuForm.$invalid">
            {{trans('admin::messages.Save')}}
        </button>
    @endcan
    <button class="btn btn-secondary" ng-if="menuModel" ng-click="cancel()">
        {{trans('admin::messages.Cancel')}}
    </button>
    @can('admin.menu.delete')
        <button class="btn btn-danger" ng-show="currentMenu.id" title="{{trans('admin::messages.Delete')}}" ng-click="deleteMenu(currentMenu, '{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i>
        </button>
    @endcan
@endsection

@section('content')
    <div class="card shd">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-xs-left">
                <li class="nav-item" ng-repeat="item in menu">
                    <a class="nav-link" ng-class="{'active': item.id==currentMenu.id || item.id==menuModel.id}" href="#" ng-click="selectMenu(item)">@{{ item.name }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" ng-class="{'active': menuModel && !menuModel.id}" href="#" title="@lang('admin::messages.Add')" ng-click="addMenu()">
                        <i class="fa fa-plus"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-block">
            <div ng-if="currentMenu && !menuModel" ng-controller="MenuTreeController as tree">

                @can('admin.menu.edit')
                    <div>
                        <a class="btn btn-link" ng-href="#/menuman/items/@{{currentMenu.id}}/edit">
                            <i class="fa fa-plus"></i>
                            @lang('general.Add Menu Item')
                        </a>
                    </div>
                @endcan

                <div ui-tree="tree.treeOptions" data-empty-placeholder-enabled="false">
                    <ol ui-tree-nodes ng-model="menuItems[currentMenu.id]">
                        <li ng-repeat="node in menuItems[currentMenu.id]" ui-tree-node ng-include="'nodes_renderer.html'"></li>
                    </ol>
                </div>

            </div>

            <div ng-show="menuModel">
                @include('admin.menu.form')
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="nodes_renderer.html">
        <div ui-tree-handle class="tree-node tree-node-content">
            <button class="btn btn-link btn-sm text-muted tree-toggle" ng-if="node.children && node.children.length > 0" data-nodrag ng-click="tree.toggle(this)">
                <i class="fa" ng-class="{'fa-chevron-right': collapsed, 'fa-chevron-down': !collapsed}"></i>
            </button>
            <i ng-if="node.icon" class="fa fa-@{{ node.icon }}"></i>
            @{{node.title}}
            <span class="pull-right btn-group btn-group-sm tree-tools" data-nodrag>
                <small class="text-muted hidden-sm-down">@{{ node.finalUrl }}</small>
                @can('admin.menu.edit')
                    <a class="btn btn-outline-primary no-b btn-sm" data-nodrag title="@lang('admin::messages.Edit')" ng-href="#/menuman/items/@{{currentMenu.id}}/edit/@{{node.id}}">
                        <i class="fa fa-pencil"></i>
                    </a>
                @endcan
                @can('admin.menu.delete')
                    <button class="btn btn-outline-danger no-b btn-sm" data-nodrag ng-click="tree.remove(this, '{{trans('admin::messages.Delete')}}?')" title="@lang('admin::messages.Delete')">
                        <i class="fa fa-remove"></i>
                    </button>
                @endcan
            </span>
        </div>
        <ol ui-tree-nodes="" ng-model="node.children" ng-class="{hidden: collapsed}">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_renderer.html'"></li>
        </ol>
    </script>

@endsection