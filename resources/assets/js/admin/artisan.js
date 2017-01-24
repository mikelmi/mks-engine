(function(){
    var app = angular.module('artisan', []);

    app.controller('ArtisanCtrl', ['$scope', '$http', 'UrlBuilder', function($scope, $http, UrlBuilder) {

        $scope.commands = [];
        $scope.commandsQuery = '';

        $scope.command = null;
        $scope.arguments = {};
        $scope.options = {};

        var self = this;

        $http.get(UrlBuilder.get('artisan/commands')).then(function(r) {
            $scope.commands = r.data;
        });

        $scope.commandsFilter = function(item){
            if (!$scope.commandsQuery
                || (item.name.toLowerCase().indexOf($scope.commandsQuery) != -1)
                || (item.description.toLowerCase().indexOf($scope.commandsQuery.toLowerCase()) != -1) ){
                return true;
            }
            return false;
        };

        $scope.run = function(url) {
            $scope.inProgress = true;
            $scope.errors = {};

            $http.post(url, {command: $scope.command.name, arguments: $scope.arguments, options: $scope.options}).then(function(r) {
                $scope.inProgress = false;
                $scope.output = r.data;
            }, function(r) {
                if (r.status == 422) {
                    $scope.errors = r.data;
                }
                $scope.inProgress = false;
            });
        }
    }]);

})(window.angular);
