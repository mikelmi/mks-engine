<div class="iconset noselect">
    <div class="item-list clearfix" ng-click="selectOrUnselect(null, $event)" ng-right-click="selectOrUnselect(null, $event)" prevent="true">
        <div class="col-120" ng-repeat="item in $parent.fileList = (fileNavigator.fileList | filter: {model:{name: query}})" ng-show="!fileNavigator.requesting && !fileNavigator.error">
            <a href="" class="thumbnail text-center" ng-click="selectOrUnselect(item, $event)" ng-dblclick="smartClick(item)" ng-right-click="selectOrUnselect(item, $event)" title="{{item.model.name}} ({{item.model.size | humanReadableFileSize}})" ng-class="{selected: isSelected(item), 'item-file': item.model.type=='file', 'item-dir': item.model.type=='dir'}">
                <div class="thumbnail-wrap">
                    <div class="item-thumbnail" ng-if="item.model.thumbnail">
                        <img ng-src="{{item.model.thumbnail}}" />
                    </div>
                    <div class="item-icon" ng-if="!item.model.thumbnail">
                        <i class="glyphicon glyphicon-folder-open" ng-show="item.model.type === 'dir'"></i>
                        <i class="glyphicon glyphicon-file" data-ext="{{ item.model.name | fileExtension }}" ng-show="item.model.type === 'file'" ng-class="{'item-extension': config.showExtensionIcons}"></i>
                    </div>
                    {{item.model.name | strLimit : 11 }}
                </div>
            </a>
        </div>
    </div>

    <div ng-show="fileNavigator.requesting">
        <div ng-include="config.tplPath + '/spinner.html'"></div>
    </div>

    <div class="alert alert-warning" ng-show="!fileNavigator.requesting && fileNavigator.fileList.length < 1 && !fileNavigator.error">
        {{"no_files_in_folder" | translate}}...
    </div>
    
    <div class="alert alert-danger" ng-show="!fileNavigator.requesting && fileNavigator.error">
        {{ fileNavigator.error }}
    </div>
</div>