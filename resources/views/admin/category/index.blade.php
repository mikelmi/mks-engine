@extends('admin::page')

@section('title')
    @lang('general.Categories')
@endsection

@section('controller')
    ng-controller="CategoryController" ng-init="init({{$scope}})"
@endsection

@section('tools')
    @can('admin.category.edit')
        <button class="btn btn-primary" ng-hide="sectionModel" ng-click="addSection()">
            <i class="fa fa-plus"></i>
            {{trans('general.Add Section')}}
        </button>
        <button class="btn btn-success" ng-show="currentSection.id" ng-click="editSection(currentSection)">
            <i class="fa fa-pencil"></i>
            {{trans('admin::messages.Edit')}}
        </button>
        <button class="btn btn-primary" ng-if="sectionModel" ng-click="saveSection()" ng-disabled="sectionForm.$invalid">
            {{trans('admin::messages.Save')}}
        </button>
    @endcan
    <button class="btn btn-secondary" ng-if="sectionModel" ng-click="cancel()">
        {{trans('admin::messages.Cancel')}}
    </button>
    @can('admin.category.delete')
        <button class="btn btn-danger" ng-show="currentSection.id" title="{{trans('admin::messages.Delete')}}" ng-click="deleteSection(currentSection, '{{trans('admin::messages.Delete')}}?')">
            <i class="fa fa-remove"></i>
        </button>
    @endcan
@endsection

@section('content')
    <div class="card shd">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-xs-left">
                <li class="nav-item" ng-repeat="item in sections">
                    <a class="nav-link" ng-class="{'active': item.id==currentSection.id || item.id==sectionModel.id}" href="#" ng-click="selectSection(item)">@{{ item.title }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" ng-class="{'active': sectionModel && !sectionModel.id}" href="#" title="@lang('admin::messages.Add')" ng-click="addSection()">
                        <i class="fa fa-plus"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-block">
            <div ng-if="currentSection && !sectionModel" ng-controller="CategoryTreeController as tree">

                @can('admin.category.edit')
                    <div>
                        <a class="btn btn-link" ng-href="#/category/edit/@{{currentSection.id}}">
                            <i class="fa fa-plus"></i>
                            @lang('general.Add Category')
                        </a>
                    </div>
                @endcan

                <div ui-tree="tree.treeOptions" data-empty-placeholder-enabled="false">
                    <ol ui-tree-nodes ng-model="categories[currentSection.id]">
                        <li ng-repeat="node in categories[currentSection.id]" ui-tree-node ng-include="'category_nodes_renderer.html'"></li>
                    </ol>
                </div>

            </div>

            <div ng-show="sectionModel">
                @include('admin.category.section-form')
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="category_nodes_renderer.html">
        <div ui-tree-handle class="tree-node tree-node-content">
            <button class="btn btn-link btn-sm text-muted tree-toggle" ng-if="node.children && node.children.length > 0" data-nodrag ng-click="tree.toggle(this)">
                <i class="fa" ng-class="{'fa-chevron-right': collapsed, 'fa-chevron-down': !collapsed}"></i>
            </button>
            @{{node.title}}
            <span class="pull-right btn-group btn-group-sm tree-tools" data-nodrag>
                @can('admin.category.edit')
                    <a class="btn btn-success btn-sm" data-nodrag title="@lang('admin::messages.Edit')" ng-href="#/category/edit/@{{currentSection.id}}/@{{node.id}}">
                        <i class="fa fa-pencil"></i>
                    </a>
                @endcan
                @can('admin.category.delete')
                    <button class="btn btn-danger" data-nodrag ng-click="tree.remove(this, '{{trans('admin::messages.Delete')}}?')" title="@lang('admin::messages.Delete')">
                        <i class="fa fa-remove"></i>
                    </button>
                @endcan
            </span>
        </div>
        <ol ui-tree-nodes="" ng-model="node.children" ng-class="{hidden: collapsed}">
            <li ng-repeat="node in node.children" ui-tree-node ng-include="'category_nodes_renderer.html'"></li>
        </ol>
    </script>

@endsection