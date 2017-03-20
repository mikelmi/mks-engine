require('angular-ui-tree');

(function(){
    var app = angular.module('mks-category-manager', ['ui.tree']);

    app.controller('CategoryController', ['$scope', '$http', 'UrlBuilder', '$location',
        function($scope, $http, UrlBuilder, $location) {

        $scope.sections = [];
        $scope.currentSection = null;
        $scope.sectionModel = null;
        $scope.prevSection = null;
        $scope.categories = {};

        var self = this;

        $scope.init = function(sectionId) {
            $http.get(UrlBuilder.get('category/sections')).then(function(r) {
                $scope.sections = r.data;
                if ($scope.sections.length) {
                    if (sectionId) {
                        $scope.selectSection(self.getSection(sectionId));
                    } else {
                        $scope.selectSection($scope.sections[0]);
                    }
                }
            });
        };

        this.getSection = function(id) {
            for (var i = 0; i < $scope.sections.length; i++) {
                if ($scope.sections[i].id == id) {
                    return $scope.sections[i];
                }
            }

            return false;
        };

        this.getSectionIndex = function(id) {
            for (var i = 0; i < $scope.sections.length; i++) {
                if ($scope.sections[i].id == id) {
                    return i;
                }
            }

            return false;
        };

        this.loadCategories = function(sectionId) {
          if (!$scope.categories[sectionId]) {
              $http.get(UrlBuilder.get('category/categories/'+sectionId)).then(function(r) {
                  $scope.categories[sectionId] = r.data;
              });
          }
        };

        $scope.selectSection = function(item) {
            $scope.currentSection = item;
            $scope.prevSection = item;
            $scope.sectionModel = null;
            self.loadCategories(item.id);
        };

        $scope.addSection = function () {
            $scope.currentSection = null;
            $scope.sectionModel = {active: true};
        };

        $scope.editSection = function (item) {
            $scope.currentSection = null;
            $scope.sectionModel = angular.copy(item);
        };

        $scope.saveSection = function () {
            if (!$scope.sectionModel) {
                return false;
            }

            $http.post(UrlBuilder.get('category/save-section'), $scope.sectionModel).then(function(r) {
                if (!r.data.id) {
                    return false;
                }

                if (!$scope.sectionModel.id) {
                    $scope.sections.push(r.data);
                    $scope.selectSection(r.data);
                } else {
                    var i = self.getSectionIndex(r.data.id);
                    if (i >= 0) {
                        $scope.sections[i] = r.data;
                        $scope.selectSection($scope.sections[i]);
                    }
                }
            });
        };

        $scope.cancel = function() {
            if (!$scope.sectionModel || !$scope.prevSection) {
                return false;
            }

            $scope.selectSection($scope.prevSection);
        };

        $scope.deleteSection = function (item, msg) {
            if (item.id && confirm(msg||'Delete?')) {
                $http.post(UrlBuilder.get('category/delete-section'), {id: item.id}).then(function(r) {
                    $scope.sections.splice(self.getSectionIndex(item.id), 1);
                    if ($location.path() == '/category/' + item.id) {
                        $location.path('/category');
                    } else if ($scope.sections.length) {
                        $scope.selectSection($scope.sections[0]);
                    }
                });
            }
        }

    }]);
    
    app.controller('CategoryTreeController', ['$http', 'UrlBuilder', function($http, UrlBuilder) {
        this.category = null;

        var self = this;

        this.toggle = function(scope) {
            scope.toggle();
        };

        this.remove = function(scope, msg) {
            if (scope.$nodeScope && confirm(msg || 'Delete?')) {
                var id = scope.$nodeScope.$modelValue.id;

                $http.post(UrlBuilder.get('category/delete/' + id))
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

                var section_id = e.source.nodeScope.$modelValue.section_id;
                var id = e.source.nodeScope.$modelValue.id;

                return $http.post(UrlBuilder.get('category/move/' + section_id + '/' + id), params)
                    .then(function(r) {
                        return true;
                    });
            }
        };
    }]);

})(window.angular);