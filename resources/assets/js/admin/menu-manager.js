(function(){
    var app = angular.module('mks-menu-manager', ['ui.tree']);

    app.controller('MenuController', ['$scope', '$http', 'UrlBuilder', function($scope, $http, UrlBuilder) {

        $scope.menu = [];
        $scope.currentMenu = null;
        $scope.menuModel = null;
        $scope.prevMenu = null;
        $scope.menuItems = {};

        var self = this;

        $scope.init = function(menuId) {
            $http.get(UrlBuilder.get('menuman/list')).then(function(r) {
                $scope.menu = r.data;
                if ($scope.menu.length) {
                    if (menuId) {
                        $scope.selectMenu(self.getMenu(menuId));
                    } else {
                        $scope.selectMenu($scope.menu[0]);
                    }
                }
            });
        };

        this.getMenu = function(id) {
            for (var i = 0; i < $scope.menu.length; i++) {
                if ($scope.menu[i].id == id) {
                    return $scope.menu[i];
                }
            }

            return false;
        };

        this.getMenuIndex = function(id) {
            for (var i = 0; i < $scope.menu.length; i++) {
                if ($scope.menu[i].id == id) {
                    return i;
                }
            }

            return false;
        };

        this.loadMenuItems = function(menuId) {
          if (!$scope.menuItems[menuId]) {
              $http.get(UrlBuilder.get('menuman/items/'+menuId)).then(function(r) {
                  $scope.menuItems[menuId] = r.data;
              });
          }
        };

        $scope.selectMenu = function(item) {
            $scope.currentMenu = item;
            $scope.prevMenu = item;
            $scope.menuModel = null;
            self.loadMenuItems(item.id);
        };

        $scope.addMenu = function () {
            $scope.currentMenu = null;
            $scope.menuModel = {active: true};
        };

        $scope.editMenu = function (item) {
            $scope.currentMenu = null;
            $scope.menuModel = angular.copy(item);
        };

        $scope.saveMenu = function () {
            if (!$scope.menuModel) {
                return false;
            }

            $http.post(UrlBuilder.get('menuman/save'), $scope.menuModel).then(function(r) {
                if (!r.data.id) {
                    return false;
                }

                if (!$scope.menuModel.id) {
                    $scope.menu.push(r.data);
                    $scope.selectMenu(r.data);
                } else {
                    var i = self.getMenuIndex(r.data.id);
                    if (i >= 0) {
                        $scope.menu[i] = r.data;
                        $scope.selectMenu($scope.menu[i]);
                    }
                }
            });
        };

        $scope.cancel = function() {
            if (!$scope.menuModel || !$scope.prevMenu) {
                return false;
            }

            $scope.selectMenu($scope.prevMenu);
        };

        $scope.deleteMenu = function (item, msg) {
            if (item.id && confirm(msg||'Delete?')) {
                $http.post(UrlBuilder.get('menuman/delete'), {id: item.id}).then(function(r) {
                    $scope.menu.splice(self.getMenuIndex(item.id), 1);
                    if ($scope.menu.length) {
                        $scope.selectMenu($scope.menu[0]);
                    }
                });
            }
        }

    }]);
    
    app.controller('MenuTreeController', ['$http', 'UrlBuilder', function($http, UrlBuilder) {
        this.menuItem = null;

        var self = this;

        this.toggle = function(scope) {
            scope.toggle();
        };

        this.remove = function(scope, msg) {
            if (scope.$nodeScope && confirm(msg || 'Delete?')) {
                var id = scope.$nodeScope.$modelValue.id;

                $http.post(UrlBuilder.get('menuman/items/delete/' + id))
                    .then(function(r) {
                        scope.remove();
                    });
            }
        };

        this.treeOptions = {
            beforeDrop: function(e) {
                var params = {
                    old: {
                        index: e.source.index,
                        parent: null
                    },
                    new: {
                        index: e.dest.index,
                        parent: null
                    }
                };
                if (e.source.nodeScope.$parentNodeScope) {
                    params.old.parent = e.source.nodeScope.$parentNodeScope.$modelValue.id;
                }

                if (e.dest.nodesScope.$nodeScope) {
                    params.new.parent = e.dest.nodesScope.$nodeScope.$modelValue.id;
                }

                if (params.old.index == params.new.index && params.old.parent == params.new.parent) {
                    return false;
                }

                var menu_id = e.source.nodeScope.$modelValue.menu_id;
                var id = e.source.nodeScope.$modelValue.id;

                return $http.post(UrlBuilder.get('menuman/items/' + menu_id + '/move/' + id), params)
                    .then(function(r) {
                        return true;
                    });
            }
        };
    }]);

})(window.angular);