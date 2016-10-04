<div ng-controller="FileManagerCtrl" ngf-drop="addForUpload($files)" ngf-drag-over-class="'upload-dragover'" ngf-multiple="true">
    <div ng-include="config.tplPath + '/navbar.html'"></div>

    <div class="container-fluid card shd">
        <div class="row">

            <div class="col-sm-4 col-md-3 sidebar file-tree animated slow fadeIn" ng-include="config.tplPath + '/sidebar.html'" ng-show="config.sidebar &amp;&amp; fileNavigator.history[0]">
            </div>

            <div class="main" ng-class="config.sidebar &amp;&amp; fileNavigator.history[0] &amp;&amp; 'col-sm-8 col-md-9'">
                <div ng-include="config.tplPath + '/' + viewTemplate" class="main-navigation clearfix"></div>
            </div>
        </div>
    </div>

    <div ng-include="config.tplPath + '/modals.html'"></div>
    <div ng-include="config.tplPath + '/item-context-menu.html'"></div>
</div>
