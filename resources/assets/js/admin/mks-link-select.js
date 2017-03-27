(function(){
    var app = angular.module('mks-link-select', []);

    app.factory('mksLinkService', ['$q', '$http', 'UrlBuilder', function($q, $http, UrlBuilder) {
        this.getParamsData = function(params, url) {
            var canceler = $q.defer();

            var request = $http({
                method: "get",
                url: url||(UrlBuilder.get('route/params')),
                params: params,
                timeout: canceler.promise
            });

            var promise = request.then(
                function( response ) {
                    return( response.data );
                },
                function( response ) {
                    return( $q.reject( "Something went wrong" ) );
                }
            );

            promise.abort = function() {
                canceler.resolve();
            };

            promise.finally(
                function() {
                    promise.abort = angular.noop;
                    canceler = request = promise = null;
                }
            );

            return promise;
        };

        var _routesPromise = $http.get(UrlBuilder.get('route')).then(function(r) {
            return r.data;
        });

        this.getRoutes = function () {
            return _routesPromise;
        };

        return this;
    }]);

    app.directive('mksLinkSelect', ['$http', 'mksLinkService', 'UrlBuilder', function($http, mksLinkService, UrlBuilder) {
        return {
            restrict: 'E',
            scope: {
                model: '@model',
                rawEnabled: '@rawEnabled',
                emptyTitle: '@emptyTitle'
            },
            templateUrl: UrlBuilder.get('templates/link-select.html'),
            link: function(scope, elem, attr) {
                scope.field = {
                    route: attr.fieldRoute || 'route[name]',
                    params: attr.fieldParams || 'route[params]',
                    raw: attr.fieldRaw || 'route[raw]'
                };

                scope.items = [];
                scope.routeOption = null;
                scope.routes = {};

                function selectRouteOption (id) {
                    angular.forEach(scope.items, function (i) {
                        if (i.id == id) {
                            scope.routeOption = i;
                        }
                    });
                }

                if (scope.model) {
                    scope.$watch(scope.model, function(val) {
                        if (val && typeof val['id'] != 'undefined') {
                            scope.route = val;
                        }
                    });
                }

                scope.$watch(function() {return scope.route; }, function(val) {
                    if (val && typeof val['id'] != 'undefined' && typeof scope.routes[val.id] == 'undefined') {
                        scope.routes[val.id] = val;
                        selectRouteOption(val.id);
                    }
                });

                scope.route = {
                    id: attr.route,
                    title: attr.title,
                    params: attr.params,
                    raw: attr.rawValue
                };

                mksLinkService.getRoutes().then(function(items) {
                    scope.items = items;
                    if (scope.route.id) {
                        selectRouteOption(scope.route.id);
                    }
                });

                scope.modal = {
                    id: 'modal-' + (new Date()).getTime(),
                    loading: false
                };

                var paramsRequest = null;
                var currentRoute = null;
                var currentPage = 1;

                function loadItems() {
                    if (!scope.routeOption) {
                        return;
                    }

                    if (paramsRequest && paramsRequest.abort) {
                        paramsRequest.abort();
                    }

                    scope.modal.loading = true;

                    (paramsRequest = mksLinkService.getParamsData({
                        name: scope.routeOption.id,
                        page: scope.modal.current_page || null,
                        q: scope.modal.searchQuery || null
                    }, scope.routeOption.selectUrl)).then(function (data) {
                        scope.modal.data = data;
                        currentRoute = scope.routeOption.id;
                        currentPage = data.pagination ? data.pagination.current_page : 1;
                        scope.modal.current_page = currentPage;
                    }).finally(function () {
                        scope.modal.loading = false;
                    });
                }

                scope.modal.open = function() {
                    if (scope.routeOption) {
                        if (scope.routeOption.extended) {
                            if (currentRoute != scope.routeOption.id) {
                                scope.modal.searchQuery = '';
                                loadItems();
                            }
                        } else {
                            if (typeof scope.routes[scope.routeOption.id] != 'undefined') {
                                scope.modal.form = scope.routes[scope.routeOption.id].params;
                            }
                        }

                        angular.element('#' + scope.modal.id).modal('show');
                    }
                };

                scope.modal.close = function() {
                    angular.element('#' + scope.modal.id).modal('hide');
                };

                scope.modal.select = function(item) {
                    if (scope.modal.data.params && scope.routeOption) {
                        var params = {};
                        angular.forEach(scope.modal.data.params, function(p) {
                            params[p] = item[p];
                        });
                        scope.routes[scope.routeOption.id] = {
                            title: item.title,
                            params: params
                        };
                        scope.modal.close();
                    }
                };

                scope.modal.save = function() {
                    if (scope.modal.form && scope.routeOption) {
                        scope.routes[scope.routeOption.id] = {
                            title: scope.paramsEncoded(scope.modal.form),
                            params: scope.modal.form
                        };
                    }
                    scope.modal.close();
                };

                scope.modal.prevPage = function(){
                    if (typeof scope.modal.current_page != 'undefined' && scope.modal.current_page > 1) {
                        scope.modal.current_page--;
                    }
                };

                scope.modal.nextPage = function(){
                    if (typeof scope.modal.current_page != 'undefined'
                        && typeof scope.modal.data.pagination.last_page != 'undefined'
                        && scope.modal.current_page < scope.modal.data.pagination.last_page) {
                        scope.modal.current_page++;
                    }
                };

                scope.$watch('modal.current_page', function(n, o) {
                    if (n && n > 0 && n != currentPage) {
                        loadItems();
                    }
                });

                scope.modal.search = function(q) {
                    if (scope.modal.current_page != 1) {
                        scope.modal.current_page = 1;
                    } else {
                        loadItems();
                    }
                };

                scope.paramsEncoded = function(params) {
                    if (params && typeof params == 'object') {
                        return angular.toJson(params);
                    }
                    return params;
                };

                scope.paramsVisible = function(data) {
                    if (data) {
                        if (data.title) {
                            return data.title;
                        }

                        return scope.paramsEncoded(data.params);
                    }
                    return '';
                }
            }
        };
    }]);

    app.directive('mksModalPaginator', ['$http', 'mksLinkService', function($http, mksLinkService, UrlBuilder) {
        return {
            restrict: 'E',
            require: ['^', '@paginator'],
            scope: {
                paginator: '@paginator'
            },
            link: function(scope, elem, attr) {

            }
        }
    }]);

    app.component('mksRoutesSelect', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/routes-select.html');
        }],
        bindings: {
            url: '@',
            showing: '@value',
            name: '@'
        },
        controller: ['$http', function($http) {
            var ctrl = this;

            this.$onInit = function () {
                ctrl.routes = [];
                ctrl.route_params = {};

                if (ctrl.url) {
                    $http.get(ctrl.url).then(function (r) {
                        ctrl.routes = r.data;

                        if (!ctrl.routes.length) {
                            ctrl.addRoute();
                        }
                    });
                }
            };

            this.addRoute = function() {
                ctrl.routes.push({id:null});
            };

            this.removeRoute = function (item) {
                var i = ctrl.routes.indexOf(item);
                if (i >= 0) {
                    ctrl.routes.splice(i, 1);
                }
            };
        }]
    });

})(window.angular);
