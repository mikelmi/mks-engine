(function() {
    angular.module('FileManagerApp').config(['fileManagerConfigProvider', '$provide', function (config, $provide) {

        $provide.decorator('item', [
            '$delegate',
            function itemDecorator($delegate) {

                var oConstruct = $delegate.prototype.constructor;

                var oldProto = $delegate.prototype;
                $delegate = function(model, path) {
                    oConstruct.apply(this, arguments);
                    if (model && this.model) {
                        this.model.thumbnail = model.thumbnail;
                    }
                };
                $delegate.prototype = oldProto;

                return $delegate;
            }
        ]);

        var settings = {};
        var defaults = config.$get();

        if (typeof window.FM_CONFIG != 'undefined' && angular.isObject(window.FM_CONFIG)) {
            settings = window.FM_CONFIG;
        }

        if (typeof window.FM_ACTIONS != 'undefined' && angular.isObject(window.FM_ACTIONS)) {
            if (window.FM_ACTIONS_CLEAR) {
                settings.allowedActions = window.FM_ACTIONS;
            } else {
                settings.allowedActions = angular.extend(defaults.allowedActions, window.FM_ACTIONS);
            }
        }

        config.set(settings);

    }]);
})(window.angular);
