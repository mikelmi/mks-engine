(function(){
    var app = angular.module('mks-admin-ext', []);

    app.factory('mksLinkService', ['$q', '$http', function($q, $http) {
        this.getParamsData = function(url, params) {
            var canceler = $q.defer();

            var request = $http({
                method: "get",
                url: url,
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

        return this;
    }]);

    app.directive('mksLinkSelect', ['$http', 'mksLinkService', 'UrlBuilder', function($http, mksLinkService, UrlBuilder) {
        return {
            restrict: 'E',
            scope: {
                url: '@url',
                paramsUrl: '@paramsUrl',
                route: '@route'
            },
            templateUrl: UrlBuilder.get('templates/link-selector.html'),
            link: function(scope, elem, attr) {
                scope.fieldRoute = attr.fieldRoute || 'route_name';
                scope.fieldParams = attr.fieldParams || 'route_params';

                scope.routeItem = {};

                scope.$watch('routeItem', function(val) {
                    if (val && typeof(val['id']) != 'undefined') {
                        scope.route = val.id;
                    }
                });

                if (scope.url) {
                    $http.get(scope.url).then(function(r) {
                        scope.items = r.data;
                        if (scope.route) {
                            angular.forEach(scope.items, function(i) {
                                if (i.id == scope.route) {
                                    scope.routeItem = i;
                                }
                            });
                        }
                    });
                }

                scope.routes = {};

                if (scope.route) {
                    scope.routes[scope.route] = {
                        title: attr.title,
                        params: typeof attr.params == 'string' && attr.params ? angular.fromJson(attr.params) : attr.params
                    }
                }
                
                scope.modal = {
                    id: 'modal-' + (new Date()).getTime(),
                    loading: false
                };

                var paramsRequest = null;
                var currentRoute = null;
                var currentPage = 1;

                function loadItems() {
                    if (paramsRequest && paramsRequest.abort) {
                        paramsRequest.abort();
                    }

                    scope.modal.loading = true;

                    (paramsRequest = mksLinkService.getParamsData(scope.paramsUrl, {
                        name: scope.route,
                        page: scope.modal.current_page || null,
                        q: scope.modal.searchQuery || null
                    })).then(function (data) {
                        scope.modal.data = data;
                        currentRoute = scope.route;
                        currentPage = data.pagination ? data.pagination.current_page : 1;
                        scope.modal.current_page = currentPage;
                    }).finally(function () {
                        scope.modal.loading = false;
                    });
                }

                scope.modal.open = function() {
                    if (scope.route) {
                        if (scope.routeItem.extended) {
                            if (currentRoute != scope.route) {
                                scope.modal.searchQuery = '';
                                loadItems();
                            }
                        } else {
                            if (typeof scope.routes[scope.route] != 'undefined') {
                                scope.modal.form = scope.routes[scope.route].params;
                            }
                        }

                        $('#' + scope.modal.id).modal('show');
                    }
                };

                scope.modal.close = function() {
                    $('#' + scope.modal.id).modal('hide');
                };

                scope.modal.select = function(item) {
                    if (scope.modal.data.params && scope.route) {
                        var params = {};
                        angular.forEach(scope.modal.data.params, function(p) {
                            params[p] = item[p];
                        });
                        scope.routes[scope.route] = {
                            title: item.title,
                            params: params
                        };
                        scope.modal.close();
                    }
                };

                scope.modal.save = function() {
                    if (scope.modal.form && scope.route) {
                        scope.routes[scope.route] = {
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

})(window.angular);