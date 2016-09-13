(function(){
    var app = angular.module('mks-admin-ext');

    app.controller('WidgetRoutesCtrl', ['$scope', '$http', 'UrlBuilder', function($scope, $http, UrlBuilder) {

        $scope.routes = [];

        var self = this;

        $scope.init = function(widgetId) {
            $http.get(UrlBuilder.get('widget/routes' + (widgetId ? '/'+widgetId : ''))).then(function(r) {
                $scope.routes = r.data;

                if (!$scope.routes.length) {
                    $scope.addChoice();
                }
            });
        };

        $scope.addChoice = function() {
            $scope.routes.push({id:null});
        };

        $scope.removeChoice = function (item) {
            var i = $scope.routes.indexOf(item);
            if (i >= 0) {
                $scope.routes.splice(i, 1);
            }
        };
    }]);

})(window.angular);
