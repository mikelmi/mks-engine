(function(){
    var app = angular.module('mks-components', []);

    app.directive('mksEditor', ['AppConfig', 'UrlBuilder', function (AppConfig, UrlBuilder) {
        return {
            restrict: 'A',
            link: function (scope, elem, attrs) {
                if (typeof CKEDITOR !== 'undefined') {
                    var opt = {
                        removePlugins: 'audio,Audio,lightbox',
                        extraPlugins: 'wpmore',
                        language: AppConfig.getLang('en'),
                        filebrowserBrowseUrl: UrlBuilder.get('file-manager'),
                        filebrowserImageBrowseUrl: UrlBuilder.get('file-manager?type=images'),
                        filebrowserFlashBrowseUrl: UrlBuilder.get('file-manager?type=flash'),
                        filebrowserWindowWidth: '85%'
                    };
                    if (attrs.mksEditor) {
                        try {
                            angular.extend(opt, scope.$eval(attrs.mksEditor));
                        } catch (err) {
                        }
                    }
                    CKEDITOR.replace(elem[0], opt);
                }
            }
        };
    }]);

    app.directive('mksPageIframe', ['$window', function ($window) {
        return {
            restrict: 'A',
            link: function (scope, elem, attrs) {
                var $sidebar = angular.element('#sidebar');
                if ($sidebar.length) {
                    elem.height($sidebar.height());

                    angular.element($window).on('resize.pageIframe', function() {
                        elem.height($sidebar.height());
                    });

                    scope.$on('$destroy', function() {
                        angular.element($window).off('resize.pageIframe');
                    });
                }

                elem.parent().css({'padding-left': 0, 'padding-right': 0});
            }
        };
    }]);

    app.directive('mksSelect', [function () {
        return {
            restrict: 'A',
            priority: -1,
            link: function(scope, elem, attrs) {
                var iconUrl = elem.data('langIcon');
                if (iconUrl) {
                    var formatResult = function(item) {
                        if (!item.id) { return item.text; }
                        var $item = angular.element(
                            '<span><img src="' + iconUrl + '/' + item.element.value.toLowerCase() + '" class="img-flag" /> ' + item.text + '</span>'
                        );
                        return $item;
                    };

                    elem.data('templateResult', formatResult);
                    elem.data('templateSelection', formatResult);
                }
            }
        };
    }]);

    app.directive('stSearchSelect2', [function() {
        return {
            restrict: 'A',
            require:'^stTable',
            'link': function(scope, el, attr, ctrl) {
                el.on('change', function() {
                    ctrl.search(this.value, attr.stSearchSelect2);
                });
            }
        }
    }]);

    app.directive('mksDataId', [function () {
        return {
            restrict: 'A',
            priority: -1,
            link: function (scope, elem, attrs) {
                var id = attrs['mksDataId'];
                if (id) {
                    elem.prop('id', id);
                }
            }
        }
    }]);

    app.component('mksImagesPicker', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/images-picker.html');
        }],
        bindings: {
            url: '@',
            items: '=?',
            inputName: '@name',
            pickMain: '@'
        },
        controller: ['$http', 'UrlBuilder', '$element', function($http, UrlBuilder, $element) {
            var ctrl = this;

            this.$onInit = function () {
                if (!this.inputName) {
                    this.inputName = 'images';
                }

                if (!this.items) {
                    this.items = [];

                    if (this.url) {
                        $http.get(this.url).then(function(r) {
                            if (r.data) {
                                ctrl.items = r.data;
                            }
                        });
                    }
                }

                window.pickImageMultiple = function(files) {
                    ctrl.safeApply(function() {
                        angular.forEach(files, function(file) {
                            ctrl.items.push({url: file.relativeUrl || file.url});
                        });
                    });
                };
            };

            this.add = function () {
                CKEDITOR.editor.prototype.popup(UrlBuilder.get('file-manager?type=images&multiple=1&callback=pickImageMultiple'));
            };

            this.delete = function (item) {
                var index = this.items.indexOf(item);
                if (index > -1) {
                    this.items.splice(index, 1);
                }
            };

            this.safeApply = function (fn) {
                var scope = $element.scope();
                var phase = scope.$$phase;
                if (phase == '$apply' || phase == '$digest') {
                    if (fn && (typeof (fn) === 'function')) {
                        fn();
                    }
                } else {
                    scope.$apply(fn);
                }
            };

            this.itemsValue = function () {
                return angular.toJson(this.items);
            };

            this.setMain = function (item) {
                var index = this.items.indexOf(item);
                if (index > -1) {
                    for (var i=0; i < this.items.length; i++) {
                        this.items[i].main = i == index;
                    }
                }
            };
        }]
    });

    app.component('mksImageSelect', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/image-select.html');
        }],
        bindings: {
            image: '=?',
            inputName: '@name',
            pickMain: '@',
            id: '@'
        },
        controller: ['$http', 'UrlBuilder', '$element', function($http, UrlBuilder, $element) {
            var ctrl = this;

            this.$onInit = function () {
                if (!this.inputName) {
                    this.inputName = 'image';
                }

                this.callbackName = 'pickImageSingle' + (this.id||'');

                window[this.callbackName] = function(files) {
                    ctrl.safeApply(function() {
                        ctrl.image = files[0].relativeUrl || files[0].url;
                    });
                };
            };

            this.browse = function () {
                CKEDITOR.editor.prototype.popup(UrlBuilder.get('file-manager?type=images&callback=' + this.callbackName));
            };

            this.clear = function () {
                this.image = null
            };

            this.safeApply = function (fn) {
                var scope = $element.scope();
                var phase = scope.$$phase;
                if (phase == '$apply' || phase == '$digest') {
                    if (fn && (typeof (fn) === 'function')) {
                        fn();
                    }
                } else {
                    scope.$apply(fn);
                }
            };
        }]
    });

    app.component('mksCategorySelect', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/category-select.html');
        }],
        bindings: {
            url: '@',
            sectionField: '@',
            categoryField: '@',
            sectionId: '@',
            categoryId: '@',
            sectionEmpty: '@',
            categoryEmpty: '@'
        },
        controller: ['$http', function($http) {
            var ctrl = this;

            this.items = [];
            this.section = null;
            this.category = null;

            this.$onInit = function () {
                if (!this.url) {
                    return false;
                }

                if (!this.sectionField) {
                    this.sectionField = 'section';
                }

                if (!this.categoryField) {
                    this.categoryField = 'category';
                }

                $http.get(this.url).then(function(r) {
                    if (r.data) {
                        ctrl.items = r.data;
                        if (ctrl.sectionId || ctrl.categoryId) {
                            angular.forEach(ctrl.items, function (section) {
                                if (ctrl.sectionId && section.id == ctrl.sectionId) {
                                    ctrl.section = section;
                                }
                                if (ctrl.categoryId && section.children) {
                                    angular.forEach(section.children, function (category) {
                                        if (category.id == ctrl.categoryId) {
                                            ctrl.category = category;
                                            if (!ctrl.section) {
                                                ctrl.section = section;
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            };

        }]
    });

    app.component('mksAssocInput', {
        templateUrl: ['UrlBuilder', function(UrlBuilder) {
            return UrlBuilder.get('templates/assoc-input.html');
        }],
        bindings: {
            value: '@value',
            name: '@',
            url: '@'
        },
        controller: ['$http', function($http) {
            var ctrl = this;

            this.$onInit = function () {
                this.items = [];

                var value = this.value;

                if (value) {
                    if (angular.isString(value)) {
                        try {
                            value = angular.fromJson(value);
                        } catch (e) {}
                    }

                    if (angular.isObject(value)) {
                        angular.forEach(value, function (v, k) {
                            ctrl.items.push({id: k, value: v});
                        });
                    } else if (angular.isArray(value)) {
                        this.items = value;
                    }
                }

                if (this.url) {
                    $http.get(this.url).then(function (r) {
                        ctrl.items = r.data;
                    });
                }
            };

            this.add = function() {
                this.items.push({id:null, value: ''});
            };

            this.remove = function(item) {
                var i = this.items.indexOf(item);
                if (i >= 0) {
                    this.items.splice(i, 1);
                }
            };

            this.nameValue = function(item) {
                return item.id ? this.name + '[' + item.id + ']' : '';
            }
        }]
    });

})(window.angular);
