(function(){
    var app = angular.module('mks-dashboard', ['ngSanitize']);

    app.component('dashboardNotifications', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/dashboard-notifications.html');
        }],
        bindings: {
            url: '@'
        },
        controller: ['$http', 'UrlBuilder', '$sce', function($http, UrlBuilder, $sce) {
            this.items = [];
            this.nextUrl = null;
            this.totalCount = 0;
            this.unreadCount = 0;
            this.currentItem = null;
            this.detailsHtml = null;

            var ctrl = this;

            function updateCounts() {
                var count = 0;
                angular.forEach(ctrl.items, function(item) {
                    if (!item.read_at) {
                        count++;
                    }
                });
                ctrl.unreadCount = count;
            }

            this.load = function() {
                $http.get(this.nextUrl || this.url).then(function(r) {
                    if (r.data) {
                        ctrl.items = ctrl.items.concat(r.data.data);
                        ctrl.nextUrl = r.data.next_page_url;
                        ctrl.totalCount = r.data.total;
                        updateCounts();
                    }
                });
            };
            
            this.details = function (item) {
                $http.get(UrlBuilder.get('dashboard/notification-details/' + item.id)).then(function (r) {
                    if (r.data) {
                        ctrl.detailsHtml = $sce.trustAsHtml(r.data.details);
                        item.read_at = r.data.read_at;
                        updateCounts();
                        ctrl.currentItem = item;
                    }
                });
            };

            this.delete = function(item, confirmation) {
                if (confirmation && !confirm(confirmation)) {
                    return false;
                }

                $http.post(UrlBuilder.get('dashboard/notification-delete/' + item.id)).then(function (r) {
                    var i = ctrl.items.indexOf(item);
                    if (i >= 0) {
                        ctrl.items.splice(i, 1);
                        ctrl.totalCount--;
                        updateCounts();
                    }
                });
            };

            this.refresh = function() {
                this.items = [];
                this.nextUrl = null;
                this.load();
            };

            function deleteMany(url, confirmation) {
                if (confirmation && !confirm(confirmation)) {
                    return false;
                }

                $http.post(url).then(function (r) {
                    ctrl.refresh();
                });
            }

            this.deleteRead = function(confirmation) {
                deleteMany(UrlBuilder.get('dashboard/notifications-delete'), confirmation);
            };

            this.deleteAll = function(confirmation) {
                deleteMany(UrlBuilder.get('dashboard/notifications-delete/all'), confirmation);
            };

            this.load();
        }]
    });

    app.component('dashboardStatistics', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/dashboard-statistics.html');
        }],
        bindings: {
            url: '@'
        },
        controller: ['$http', 'UrlBuilder', function($http, UrlBuilder) {
            var ctrl = this;

            this.items = [];

            this.load = function() {
                $http.get(this.url).then(function(r) {
                    if (r.data) {
                        ctrl.items = r.data;
                    }
                });
            };

            this.load();
        }]
    });

})(window.angular);
