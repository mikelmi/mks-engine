<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 col-md-10 hidden-xs">
                <div ng-show="!config.breadcrumb">
                    <a class="navbar-brand hidden-xs ng-binding" href="">angular-filemanager</a>
                </div>
                <div ng-include="config.tplPath + '/current-folder-breadcrumb.html'" ng-show="config.breadcrumb">
                </div>
            </div>
            <div class="col-sm-3 col-md-2">
                <div class="navbar-collapse">
                    <div class="navbar-form navbar-right text-right">
                        <div class="btn-group">
                            <button class="btn btn-flat btn-sm dropdown-toggle" type="button" id="dropDownMenuLang" data-toggle="dropdown" aria-expanded="true">
                                <i class="glyphicon glyphicon-search mr2"></i>
                            </button>
                            <div class="dropdown-menu animated fast fadeIn pull-right" role="menu" aria-labelledby="dropDownMenuLang">
                                <input type="text" class="form-control" ng-show="config.searchForm" placeholder="{{'search' | translate}}..." ng-model="$parent.query">
                            </div>
                        </div>

                        <button class="btn btn-flat btn-sm" ng-click="$parent.setTemplate('main-icons.html')" ng-show="$parent.viewTemplate !=='main-icons.html'" title="{{'icons' | translate}}">
                            <i class="glyphicon glyphicon-th-large"></i>
                        </button>

                        <button class="btn btn-flat btn-sm" ng-click="$parent.setTemplate('main-table.html')" ng-show="$parent.viewTemplate !=='main-table.html'" title="{{'list' | translate}}">
                            <i class="glyphicon glyphicon-th-list"></i>
                        </button>

                        <div class="btn-group">
                            <button class="btn btn-flat btn-sm dropdown-toggle" type="button" id="more" data-toggle="dropdown" aria-expanded="true">
                                <i class="glyphicon glyphicon-option-vertical"></i>
                            </button>

                            <ul class="dropdown-menu scrollable-menu animated fast fadeIn pull-right" role="menu" aria-labelledby="more">
                                <li role="presentation" ng-show="config.allowedActions.createFolder" ng-click="modal('newfolder') && prepareNewFolder()">
                                    <a href="#" role="menuitem" tabindex="-1">
                                        <i class="glyphicon glyphicon-plus"></i> {{"new_folder" | translate}}
                                    </a>
                                </li>
                                <li role="presentation" ng-show="config.allowedActions.upload" ng-click="modal('uploadfile')">
                                    <a href="#" role="menuitem" tabindex="-1">
                                        <i class="glyphicon glyphicon-cloud-upload"></i> {{"upload_files" | translate}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>