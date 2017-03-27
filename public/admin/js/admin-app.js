!function(e){function t(o){if(n[o])return n[o].exports;var i=n[o]={i:o,l:!1,exports:{}};return e[o].call(i.exports,i,i.exports,t),i.l=!0,i.exports}var n={};return t.m=e,t.c=n,t.i=function(e){return e},t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:o})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="./",t(t.s=28)}([,,,,,,function(e,t,n){n(24),e.exports="ui.tree"},,,,,,,function(e,t){!function(){var e=angular.module("artisan",[]);e.controller("ArtisanCtrl",["$scope","$http","UrlBuilder",function(e,t,n){e.commands=[],e.commandsQuery="",e.command=null,e.arguments={},e.options={};t.get(n.get("artisan/commands")).then(function(t){e.commands=t.data}),e.commandsFilter=function(t){return!e.commandsQuery||t.name.toLowerCase().indexOf(e.commandsQuery)!=-1||t.description.toLowerCase().indexOf(e.commandsQuery.toLowerCase())!=-1},e.run=function(n){e.inProgress=!0,e.errors={},t.post(n,{command:e.command.name,arguments:e.arguments,options:e.options}).then(function(t){e.inProgress=!1,e.output=t.data},function(t){422==t.status&&(e.errors=t.data),e.inProgress=!1})}}])}(window.angular)},function(e,t,n){n(6),function(){var e=angular.module("mks-category-manager",["ui.tree"]);e.controller("CategoryController",["$scope","$http","UrlBuilder","$location",function(e,t,n,o){e.sections=[],e.currentSection=null,e.sectionModel=null,e.prevSection=null,e.categories={};var i=this;e.init=function(o){t.get(n.get("category/sections")).then(function(t){e.sections=t.data,e.sections.length&&(o?e.selectSection(i.getSection(o)):e.selectSection(e.sections[0]))})},this.getSection=function(t){for(var n=0;n<e.sections.length;n++)if(e.sections[n].id==t)return e.sections[n];return!1},this.getSectionIndex=function(t){for(var n=0;n<e.sections.length;n++)if(e.sections[n].id==t)return n;return!1},this.loadCategories=function(o){e.categories[o]||t.get(n.get("category/categories/"+o)).then(function(t){e.categories[o]=t.data})},e.selectSection=function(t){e.currentSection=t,e.prevSection=t,e.sectionModel=null,i.loadCategories(t.id)},e.addSection=function(){e.currentSection=null,e.sectionModel={active:!0}},e.editSection=function(t){e.currentSection=null,e.sectionModel=angular.copy(t)},e.saveSection=function(){return!!e.sectionModel&&void t.post(n.get("category/save-section"),e.sectionModel).then(function(t){if(!t.data.id)return!1;if(e.sectionModel.id){var n=i.getSectionIndex(t.data.id);n>=0&&(e.sections[n]=t.data,e.selectSection(e.sections[n]))}else e.sections.push(t.data),e.selectSection(t.data)})},e.cancel=function(){return!(!e.sectionModel||!e.prevSection)&&void e.selectSection(e.prevSection)},e.deleteSection=function(r,a){r.id&&confirm(a||"Delete?")&&t.post(n.get("category/delete-section"),{id:r.id}).then(function(t){e.sections.splice(i.getSectionIndex(r.id),1),o.path()=="/category/"+r.id?o.path("/category"):e.sections.length&&e.selectSection(e.sections[0])})}}]),e.controller("CategoryTreeController",["$http","UrlBuilder",function(e,t){this.category=null;this.toggle=function(e){e.toggle()},this.remove=function(n,o){if(n.$nodeScope&&confirm(o||"Delete?")){var i=n.$nodeScope.$modelValue.id;e.post(t.get("category/delete/"+i)).then(function(e){n.remove()})}},this.treeOptions={beforeDrop:function(n){var o={old:{index:n.source.index,parent:null},new:{index:n.dest.index,parent:null}};if(n.source.nodeScope.$parentNodeScope&&(o.old.parent=n.source.nodeScope.$parentNodeScope.$modelValue.id),n.dest.nodesScope.$nodeScope&&(o.new.parent=n.dest.nodesScope.$nodeScope.$modelValue.id),o.old.index==o.new.index&&o.old.parent==o.new.parent)return!1;var i=n.source.nodeScope.$modelValue.section_id,r=n.source.nodeScope.$modelValue.id;return e.post(t.get("category/move/"+i+"/"+r),o).then(function(e){return!0})}}}])}(window.angular)},function(e,t){!function(){var e=angular.module("mks-dashboard",["ngSanitize"]);e.component("dashboardNotifications",{templateUrl:["UrlBuilder",function(e){return e.get("templates/dashboard-notifications.html")}],bindings:{url:"@"},controller:["$http","UrlBuilder","$sce",function(e,t,n){function o(){var e=0;angular.forEach(r.items,function(t){t.read_at||e++}),r.unreadCount=e}function i(t,n){return!(n&&!confirm(n))&&void e.post(t).then(function(e){r.refresh()})}this.items=[],this.nextUrl=null,this.totalCount=0,this.unreadCount=0,this.currentItem=null,this.detailsHtml=null;var r=this;this.load=function(){e.get(this.nextUrl||this.url).then(function(e){e.data&&(r.items=r.items.concat(e.data.data),r.nextUrl=e.data.next_page_url,r.totalCount=e.data.total,o())})},this.details=function(i){e.get(t.get("dashboard/notification-details/"+i.id)).then(function(e){e.data&&(r.detailsHtml=n.trustAsHtml(e.data.details),i.read_at=e.data.read_at,o(),r.currentItem=i)})},this.delete=function(n,i){return!(i&&!confirm(i))&&void e.post(t.get("dashboard/notification-delete/"+n.id)).then(function(e){var t=r.items.indexOf(n);t>=0&&(r.items.splice(t,1),r.totalCount--,o())})},this.refresh=function(){this.items=[],this.nextUrl=null,this.load()},this.deleteRead=function(e){i(t.get("dashboard/notifications-delete"),e)},this.deleteAll=function(e){i(t.get("dashboard/notifications-delete/all"),e)},this.$onInit=function(){this.load()}}]}),e.component("dashboardStatistics",{templateUrl:["UrlBuilder",function(e){return e.get("templates/dashboard-statistics.html")}],bindings:{url:"@"},controller:["$http",function(e){var t=this;this.items=[],this.load=function(){e.get(this.url).then(function(e){e.data&&(t.items=e.data)})},this.$onInit=function(){this.load()}}]})}(window.angular)},function(e,t,n){n(6),function(){var e=angular.module("mks-menu-manager",["ui.tree"]);e.controller("MenuController",["$scope","$http","UrlBuilder","$location","Page",function(e,t,n,o,i){e.menu=[],e.currentMenu=null,e.menuModel=null,e.prevMenu=null,e.menuItems={};var r=this;e.init=function(o){t.get(n.get("menuman/list")).then(function(t){e.menu=t.data,e.menu.length&&(o?e.selectMenu(r.getMenu(o)):e.selectMenu(e.menu[0]))})},this.getMenu=function(t){for(var n=0;n<e.menu.length;n++)if(e.menu[n].id==t)return e.menu[n];return!1},this.getMenuIndex=function(t){for(var n=0;n<e.menu.length;n++)if(e.menu[n].id==t)return n;return!1},this.loadMenuItems=function(o){e.menuItems[o]||t.get(n.get("menuman/items/"+o)).then(function(t){e.menuItems[o]=t.data})},e.selectMenu=function(t){e.currentMenu=t,e.prevMenu=t,e.menuModel=null,r.loadMenuItems(t.id)},e.addMenu=function(){e.currentMenu=null,e.menuModel={active:!0}},e.editMenu=function(t){e.currentMenu=null,e.menuModel=angular.copy(t)},e.saveMenu=function(){return!!e.menuModel&&void t.post(n.get("menuman/save"),e.menuModel).then(function(t){if(!t.data.id)return!1;if(e.menuModel.id){var n=r.getMenuIndex(t.data.id);n>=0&&(e.menu[n]=t.data,e.selectMenu(e.menu[n]))}else e.menu.push(t.data),e.selectMenu(t.data)})},e.cancel=function(){return!(!e.menuModel||!e.prevMenu)&&void e.selectMenu(e.prevMenu)},e.deleteMenu=function(a,l){a.id&&confirm(l||"Delete?")&&t.post(n.get("menuman/delete"),{id:a.id}).then(function(t){e.menu.splice(r.getMenuIndex(a.id),1),o.path()=="/menuman/"+a.id?o.path("/menuman"):e.menu.length?e.selectMenu(e.menu[0]):(o.path("/menuman"),i.reload())})}}]),e.controller("MenuTreeController",["$http","UrlBuilder",function(e,t){this.menuItem=null;this.toggle=function(e){e.toggle()},this.remove=function(n,o){if(n.$nodeScope&&confirm(o||"Delete?")){var i=n.$nodeScope.$modelValue.id;e.post(t.get("menuman/items/delete/"+i)).then(function(e){n.remove()})}},this.treeOptions={beforeDrop:function(n){var o={old:{index:n.source.index,parent:null},new:{index:n.dest.index,parent:null}};if(n.source.nodeScope.$parentNodeScope&&(o.old.parent=n.source.nodeScope.$parentNodeScope.$modelValue.id),n.dest.nodesScope.$nodeScope&&(o.new.parent=n.dest.nodesScope.$nodeScope.$modelValue.id),o.old.index==o.new.index&&o.old.parent==o.new.parent)return!1;var i=n.source.nodeScope.$modelValue.menu_id,r=n.source.nodeScope.$modelValue.id;return e.post(t.get("menuman/items/"+i+"/move/"+r),o).then(function(e){return!0})}}}])}(window.angular)},function(e,t){!function(){var e=angular.module("mks-components",[]);e.directive("mksEditor",["AppConfig","UrlBuilder",function(e,t){return{restrict:"A",link:function(n,o,i){if("undefined"!=typeof CKEDITOR){var r={removePlugins:"audio,Audio,lightbox",extraPlugins:"wpmore",language:e.getLang("en"),filebrowserBrowseUrl:t.get("file-manager"),filebrowserImageBrowseUrl:t.get("file-manager?type=images"),filebrowserFlashBrowseUrl:t.get("file-manager?type=flash"),filebrowserWindowWidth:"85%"};if(i.mksEditor)try{angular.extend(r,n.$eval(i.mksEditor))}catch(e){}CKEDITOR.replace(o[0],r)}}}}]),e.directive("mksPageIframe",["$window",function(e){return{restrict:"A",link:function(t,n,o){var i=angular.element("#sidebar");i.length&&(n.height(i.height()),angular.element(e).on("resize.pageIframe",function(){n.height(i.height())}),t.$on("$destroy",function(){angular.element(e).off("resize.pageIframe")})),n.parent().css({"padding-left":0,"padding-right":0})}}}]),e.directive("mksSelect",[function(){return{restrict:"A",priority:-1,link:function(e,t,n){var o=t.data("langIcon");if(o){var i=function(e){if(!e.id)return e.text;var t=angular.element('<span><img src="'+o+"/"+e.element.value.toLowerCase()+'" class="img-flag" /> '+e.text+"</span>");return t};t.data("templateResult",i),t.data("templateSelection",i)}}}}]),e.directive("stSearchSelect2",[function(){return{restrict:"A",require:"^stTable",link:function(e,t,n,o){t.on("change",function(){o.search(this.value,n.stSearchSelect2)})}}}]),e.directive("mksDataId",[function(){return{restrict:"A",priority:-1,link:function(e,t,n){var o=n.mksDataId;o&&t.prop("id",o)}}}]),e.component("mksImagesPicker",{templateUrl:["UrlBuilder",function(e){return e.get("templates/images-picker.html")}],bindings:{url:"@",items:"=?",inputName:"@name",pickMain:"@"},controller:["$http","UrlBuilder","$element",function(e,t,n){var o=this;this.$onInit=function(){this.inputName||(this.inputName="images"),this.items||(this.items=[],this.url&&e.get(this.url).then(function(e){e.data&&(o.items=e.data)})),window.pickImageMultiple=function(e){o.safeApply(function(){angular.forEach(e,function(e){o.items.push({url:e.relativeUrl||e.url})})})}},this.add=function(){CKEDITOR.editor.prototype.popup(t.get("file-manager?type=images&multiple=1&callback=pickImageMultiple"))},this.delete=function(e){var t=this.items.indexOf(e);t>-1&&this.items.splice(t,1)},this.safeApply=function(e){var t=n.scope(),o=t.$$phase;"$apply"==o||"$digest"==o?e&&"function"==typeof e&&e():t.$apply(e)},this.itemsValue=function(){return angular.toJson(this.items)},this.setMain=function(e){var t=this.items.indexOf(e);if(t>-1)for(var n=0;n<this.items.length;n++)this.items[n].main=n==t}}]}),e.component("mksImageSelect",{templateUrl:["UrlBuilder",function(e){return e.get("templates/image-select.html")}],bindings:{image:"=?",inputName:"@name",pickMain:"@",id:"@"},controller:["$http","UrlBuilder","$element",function(e,t,n){var o=this;this.$onInit=function(){this.inputName||(this.inputName="image"),this.callbackName="pickImageSingle"+(this.id||""),window[this.callbackName]=function(e){o.safeApply(function(){o.image=e[0].relativeUrl||e[0].url})}},this.browse=function(){CKEDITOR.editor.prototype.popup(t.get("file-manager?type=images&callback="+this.callbackName))},this.clear=function(){this.image=null},this.safeApply=function(e){var t=n.scope(),o=t.$$phase;"$apply"==o||"$digest"==o?e&&"function"==typeof e&&e():t.$apply(e)}}]}),e.component("mksCategorySelect",{templateUrl:["UrlBuilder",function(e){return e.get("templates/category-select.html")}],bindings:{url:"@",sectionField:"@",categoryField:"@",sectionId:"@",categoryId:"@",sectionEmpty:"@",categoryEmpty:"@"},controller:["$http",function(e){var t=this;this.items=[],this.section=null,this.category=null,this.$onInit=function(){return!!this.url&&(this.sectionField||(this.sectionField="section"),this.categoryField||(this.categoryField="category"),void e.get(this.url).then(function(e){e.data&&(t.items=e.data,(t.sectionId||t.categoryId)&&angular.forEach(t.items,function(e){t.sectionId&&e.id==t.sectionId&&(t.section=e),t.categoryId&&e.children&&angular.forEach(e.children,function(n){n.id==t.categoryId&&(t.category=n,t.section||(t.section=e))})}))}))}}]}),e.component("mksAssocInput",{templateUrl:["UrlBuilder",function(e){return e.get("templates/assoc-input.html")}],bindings:{value:"@value",name:"@",url:"@"},controller:["$http",function(e){var t=this;this.$onInit=function(){this.items=[];var n=this.value;if(n){if(angular.isString(n))try{n=angular.fromJson(n)}catch(e){}angular.isObject(n)?angular.forEach(n,function(e,n){t.items.push({id:n,value:e})}):angular.isArray(n)&&(this.items=n)}this.url&&e.get(this.url).then(function(e){t.items=e.data})},this.add=function(){this.items.push({id:null,value:""})},this.remove=function(e){var t=this.items.indexOf(e);t>=0&&this.items.splice(t,1)},this.nameValue=function(e){return e.id?this.name+"["+e.id+"]":""}}]})}(window.angular)},function(e,t){var n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};!function(){var e=angular.module("mks-link-select",[]);e.factory("mksLinkService",["$q","$http","UrlBuilder",function(e,t,n){this.getParamsData=function(o,i){var r=e.defer(),a=t({method:"get",url:i||n.get("route/params"),params:o,timeout:r.promise}),l=a.then(function(e){return e.data},function(t){return e.reject("Something went wrong")});return l.abort=function(){r.resolve()},l.finally(function(){l.abort=angular.noop,r=a=l=null}),l};var o=t.get(n.get("route")).then(function(e){return e.data});return this.getRoutes=function(){return o},this}]),e.directive("mksLinkSelect",["$http","mksLinkService","UrlBuilder",function(e,t,o){return{restrict:"E",scope:{model:"@model",rawEnabled:"@rawEnabled",emptyTitle:"@emptyTitle"},templateUrl:o.get("templates/link-select.html"),link:function(e,o,i){function r(t){angular.forEach(e.items,function(n){n.id==t&&(e.routeOption=n)})}function a(){e.routeOption&&(l&&l.abort&&l.abort(),e.modal.loading=!0,(l=t.getParamsData({name:e.routeOption.id,page:e.modal.current_page||null,q:e.modal.searchQuery||null},e.routeOption.selectUrl)).then(function(t){e.modal.data=t,u=e.routeOption.id,c=t.pagination?t.pagination.current_page:1,e.modal.current_page=c}).finally(function(){e.modal.loading=!1}))}e.field={route:i.fieldRoute||"route[name]",params:i.fieldParams||"route[params]",raw:i.fieldRaw||"route[raw]"},e.items=[],e.routeOption=null,e.routes={},e.model&&e.$watch(e.model,function(t){t&&"undefined"!=typeof t.id&&(e.route=t)}),e.$watch(function(){return e.route},function(t){t&&"undefined"!=typeof t.id&&"undefined"==typeof e.routes[t.id]&&(e.routes[t.id]=t,r(t.id))}),e.route={id:i.route,title:i.title,params:i.params,raw:i.rawValue},t.getRoutes().then(function(t){e.items=t,e.route.id&&r(e.route.id)}),e.modal={id:"modal-"+(new Date).getTime(),loading:!1};var l=null,u=null,c=1;e.modal.open=function(){e.routeOption&&(e.routeOption.extended?u!=e.routeOption.id&&(e.modal.searchQuery="",a()):"undefined"!=typeof e.routes[e.routeOption.id]&&(e.modal.form=e.routes[e.routeOption.id].params),angular.element("#"+e.modal.id).modal("show"))},e.modal.close=function(){angular.element("#"+e.modal.id).modal("hide")},e.modal.select=function(t){if(e.modal.data.params&&e.routeOption){var n={};angular.forEach(e.modal.data.params,function(e){n[e]=t[e]}),e.routes[e.routeOption.id]={title:t.title,params:n},e.modal.close()}},e.modal.save=function(){e.modal.form&&e.routeOption&&(e.routes[e.routeOption.id]={title:e.paramsEncoded(e.modal.form),params:e.modal.form}),e.modal.close()},e.modal.prevPage=function(){"undefined"!=typeof e.modal.current_page&&e.modal.current_page>1&&e.modal.current_page--},e.modal.nextPage=function(){"undefined"!=typeof e.modal.current_page&&"undefined"!=typeof e.modal.data.pagination.last_page&&e.modal.current_page<e.modal.data.pagination.last_page&&e.modal.current_page++},e.$watch("modal.current_page",function(e,t){e&&e>0&&e!=c&&a()}),e.modal.search=function(t){1!=e.modal.current_page?e.modal.current_page=1:a()},e.paramsEncoded=function(e){return e&&"object"==("undefined"==typeof e?"undefined":n(e))?angular.toJson(e):e},e.paramsVisible=function(t){return t?t.title?t.title:e.paramsEncoded(t.params):""}}}}]),e.directive("mksModalPaginator",["$http","mksLinkService",function(e,t,n){return{restrict:"E",require:["^","@paginator"],scope:{paginator:"@paginator"},link:function(e,t,n){}}}]),e.component("mksRoutesSelect",{templateUrl:["UrlBuilder",function(e){return e.get("templates/routes-select.html")}],bindings:{url:"@",showing:"@value",name:"@"},controller:["$http",function(e){var t=this;this.$onInit=function(){t.routes=[],t.route_params={},t.url&&e.get(t.url).then(function(e){t.routes=e.data,t.routes.length||t.addRoute()})},this.addRoute=function(){t.routes.push({id:null})},this.removeRoute=function(e){var n=t.routes.indexOf(e);n>=0&&t.routes.splice(n,1)}}]})}(window.angular)},,,,,,function(e,t){/**
 * @license Angular UI Tree v2.22.5
 * (c) 2010-2017. https://github.com/angular-ui-tree/angular-ui-tree
 * License: MIT
 */
!function(){"use strict";angular.module("ui.tree",[]).constant("treeConfig",{treeClass:"angular-ui-tree",emptyTreeClass:"angular-ui-tree-empty",hiddenClass:"angular-ui-tree-hidden",nodesClass:"angular-ui-tree-nodes",nodeClass:"angular-ui-tree-node",handleClass:"angular-ui-tree-handle",placeholderClass:"angular-ui-tree-placeholder",dragClass:"angular-ui-tree-drag",dragThreshold:3,defaultCollapsed:!1,appendChildOnHover:!0})}(),function(){"use strict";angular.module("ui.tree").controller("TreeHandleController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$nodeScope=null,e.$type="uiTreeHandle"}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeNodeController",["$scope","$element",function(e,t){function n(e){if(!e)return 0;var t,o,i,r=0,a=e.childNodes();if(!a||0===a.length)return 0;for(i=a.length-1;i>=0;i--)t=a[i],o=1+n(t),r=Math.max(r,o);return r}this.scope=e,e.$element=t,e.$modelValue=null,e.$parentNodeScope=null,e.$childNodesScope=null,e.$parentNodesScope=null,e.$treeScope=null,e.$handleScope=null,e.$type="uiTreeNode",e.$$allowNodeDrop=!1,e.collapsed=!1,e.expandOnHover=!1,e.init=function(n){var o=n[0];e.$treeScope=n[1]?n[1].scope:null,e.$parentNodeScope=o.scope.$nodeScope,e.$modelValue=o.scope.$modelValue[e.$index],e.$parentNodesScope=o.scope,o.scope.initSubNode(e),t.on("$destroy",function(){o.scope.destroySubNode(e)})},e.index=function(){return e.$parentNodesScope.$modelValue.indexOf(e.$modelValue)},e.dragEnabled=function(){return!(e.$treeScope&&!e.$treeScope.dragEnabled)},e.isSibling=function(t){return e.$parentNodesScope==t.$parentNodesScope},e.isChild=function(t){var n=e.childNodes();return n&&n.indexOf(t)>-1},e.prev=function(){var t=e.index();return t>0?e.siblings()[t-1]:null},e.siblings=function(){return e.$parentNodesScope.childNodes()},e.childNodesCount=function(){return e.childNodes()?e.childNodes().length:0},e.hasChild=function(){return e.childNodesCount()>0},e.childNodes=function(){return e.$childNodesScope&&e.$childNodesScope.$modelValue?e.$childNodesScope.childNodes():null},e.accept=function(t,n){return e.$childNodesScope&&e.$childNodesScope.$modelValue&&e.$childNodesScope.accept(t,n)},e.remove=function(){return e.$parentNodesScope.removeNode(e)},e.toggle=function(){e.collapsed=!e.collapsed,e.$treeScope.$callbacks.toggle(e.collapsed,e)},e.collapse=function(){e.collapsed=!0},e.expand=function(){e.collapsed=!1},e.depth=function(){var t=e.$parentNodeScope;return t?t.depth()+1:1},e.maxSubDepth=function(){return e.$childNodesScope?n(e.$childNodesScope):0}}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeNodesController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$modelValue=null,e.$nodeScope=null,e.$treeScope=null,e.$type="uiTreeNodes",e.$nodesMap={},e.nodropEnabled=!1,e.maxDepth=0,e.cloneEnabled=!1,e.initSubNode=function(t){return t.$modelValue?void(e.$nodesMap[t.$modelValue.$$hashKey]=t):null},e.destroySubNode=function(t){return t.$modelValue?void(e.$nodesMap[t.$modelValue.$$hashKey]=null):null},e.accept=function(t,n){return e.$treeScope.$callbacks.accept(t,e,n)},e.beforeDrag=function(t){return e.$treeScope.$callbacks.beforeDrag(t)},e.isParent=function(t){return t.$parentNodesScope==e},e.hasChild=function(){return e.$modelValue.length>0},e.safeApply=function(e){var t=this.$root.$$phase;"$apply"==t||"$digest"==t?e&&"function"==typeof e&&e():this.$apply(e)},e.removeNode=function(t){var n=e.$modelValue.indexOf(t.$modelValue);return n>-1?(e.safeApply(function(){e.$modelValue.splice(n,1)[0]}),e.$treeScope.$callbacks.removed(t)):null},e.insertNode=function(t,n){e.safeApply(function(){e.$modelValue.splice(t,0,n)})},e.childNodes=function(){var t,n=[];if(e.$modelValue)for(t=0;t<e.$modelValue.length;t++)n.push(e.$nodesMap[e.$modelValue[t].$$hashKey]);return n},e.depth=function(){return e.$nodeScope?e.$nodeScope.depth():0},e.outOfDepth=function(t){var n=e.maxDepth||e.$treeScope.maxDepth;return n>0&&e.depth()+t.maxSubDepth()+1>n}}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$nodesScope=null,e.$type="uiTree",e.$emptyElm=null,e.$callbacks=null,e.dragEnabled=!0,e.emptyPlaceholderEnabled=!0,e.maxDepth=0,e.dragDelay=0,e.cloneEnabled=!1,e.nodropEnabled=!1,e.isEmpty=function(){return e.$nodesScope&&e.$nodesScope.$modelValue&&0===e.$nodesScope.$modelValue.length},e.place=function(t){e.$nodesScope.$element.append(t),e.$emptyElm.remove()},this.resetEmptyElement=function(){e.$nodesScope.$modelValue&&0!==e.$nodesScope.$modelValue.length||!e.emptyPlaceholderEnabled?e.$emptyElm.remove():t.append(e.$emptyElm)},e.resetEmptyElement=this.resetEmptyElement}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTree",["treeConfig","$window",function(e,t){return{restrict:"A",scope:!0,controller:"TreeController",link:function(n,o,i,r){var a,l,u,c={accept:null,beforeDrag:null},d={};angular.extend(d,e),d.treeClass&&o.addClass(d.treeClass),"table"===o.prop("tagName").toLowerCase()?(n.$emptyElm=angular.element(t.document.createElement("tr")),l=o.find("tr"),u=l.length>0?angular.element(l).children().length:1e6,a=angular.element(t.document.createElement("td")).attr("colspan",u),n.$emptyElm.append(a)):n.$emptyElm=angular.element(t.document.createElement("div")),d.emptyTreeClass&&n.$emptyElm.addClass(d.emptyTreeClass),n.$watch("$nodesScope.$modelValue.length",function(e){angular.isNumber(e)&&r.resetEmptyElement()},!0),n.$watch(i.dragEnabled,function(e){"boolean"==typeof e&&(n.dragEnabled=e)}),n.$watch(i.emptyPlaceholderEnabled,function(e){"boolean"==typeof e&&(n.emptyPlaceholderEnabled=e,r.resetEmptyElement())}),n.$watch(i.nodropEnabled,function(e){"boolean"==typeof e&&(n.nodropEnabled=e)}),n.$watch(i.cloneEnabled,function(e){"boolean"==typeof e&&(n.cloneEnabled=e)}),n.$watch(i.maxDepth,function(e){"number"==typeof e&&(n.maxDepth=e)}),n.$watch(i.dragDelay,function(e){"number"==typeof e&&(n.dragDelay=e)}),c.accept=function(e,t,n){return!(t.nodropEnabled||t.$treeScope.nodropEnabled||t.outOfDepth(e))},c.beforeDrag=function(e){return!0},c.expandTimeoutStart=function(){},c.expandTimeoutCancel=function(){},c.expandTimeoutEnd=function(){},c.removed=function(e){},c.dropped=function(e){},c.dragStart=function(e){},c.dragMove=function(e){},c.dragStop=function(e){},c.beforeDrop=function(e){},c.toggle=function(e,t){},n.$watch(i.uiTree,function(e,t){angular.forEach(e,function(e,t){c[t]&&"function"==typeof e&&(c[t]=e)}),n.$callbacks=c},!0)}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeHandle",["treeConfig",function(e){return{require:"^uiTreeNode",restrict:"A",scope:!0,controller:"TreeHandleController",link:function(t,n,o,i){var r={};angular.extend(r,e),r.handleClass&&n.addClass(r.handleClass),t!=i.scope&&(t.$nodeScope=i.scope,i.scope.$handleScope=t)}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeNode",["treeConfig","UiTreeHelper","$window","$document","$timeout","$q",function(e,t,n,o,i,r){return{require:["^uiTreeNodes","^uiTree"],restrict:"A",controller:"TreeNodeController",link:function(a,l,u,c){var d,s,p,m,f,h,g,$,v,y,b,S,x,N,E,w,C,T,O,M,k,D,I,A,H,V,Y,X={},U="ontouchstart"in window,B=null,P=document.body,_=document.documentElement;angular.extend(X,e),X.nodeClass&&l.addClass(X.nodeClass),a.init(c),a.collapsed=!!t.getNodeAttribute(a,"collapsed")||e.defaultCollapsed,a.expandOnHover=!!t.getNodeAttribute(a,"expandOnHover"),a.scrollContainer=t.getNodeAttribute(a,"scrollContainer")||u.scrollContainer||null,a.sourceOnly=a.nodropEnabled||a.$treeScope.nodropEnabled,a.$watch(u.collapsed,function(e){"boolean"==typeof e&&(a.collapsed=e)}),a.$watch("collapsed",function(e){t.setNodeAttribute(a,"collapsed",e),u.$set("collapsed",e)}),a.$watch(u.expandOnHover,function(e){"boolean"!=typeof e&&"number"!=typeof e||(a.expandOnHover=e)}),a.$watch("expandOnHover",function(e){t.setNodeAttribute(a,"expandOnHover",e),u.$set("expandOnHover",e)}),u.$observe("scrollContainer",function(e){"string"==typeof e&&(a.scrollContainer=e)}),a.$watch("scrollContainer",function(e){t.setNodeAttribute(a,"scrollContainer",e),u.$set("scrollContainer",e),g=document.querySelector(e)}),a.$on("angular-ui-tree:collapse-all",function(){a.collapsed=!0}),a.$on("angular-ui-tree:expand-all",function(){a.collapsed=!1}),S=function(e){if((U||2!==e.button&&3!==e.which)&&!(e.uiTreeDragging||e.originalEvent&&e.originalEvent.uiTreeDragging)){var i,r,u,c,g,$,S,x,N,E=angular.element(e.target);if(i=t.treeNodeHandlerContainerOfElement(E),i&&(E=angular.element(i)),r=l.clone(),x=t.elementIsTreeNode(E),N=t.elementIsTreeNodeHandle(E),(x||N)&&!(x&&t.elementContainsTreeNodeHandler(E)||(u=E.prop("tagName").toLowerCase(),"input"==u||"textarea"==u||"button"==u||"select"==u))){for(H=angular.element(e.target),V=H[0].attributes["ui-tree"];H&&H[0]&&H[0]!==l&&!V;){if(H[0].attributes&&(V=H[0].attributes["ui-tree"]),t.nodrag(H))return;H=H.parent()}a.beforeDrag(a)&&(e.uiTreeDragging=!0,e.originalEvent&&(e.originalEvent.uiTreeDragging=!0),e.preventDefault(),g=t.eventObj(e),d=!0,s=t.dragInfo(a),Y=s.source.$treeScope.$id,c=l.prop("tagName"),"tr"===c.toLowerCase()?(m=angular.element(n.document.createElement(c)),$=angular.element(n.document.createElement("td")).addClass(X.placeholderClass).attr("colspan",l[0].children.length),m.append($)):m=angular.element(n.document.createElement(c)).addClass(X.placeholderClass),f=angular.element(n.document.createElement(c)),X.hiddenClass&&f.addClass(X.hiddenClass),p=t.positionStarted(g,l),m.css("height",l.prop("offsetHeight")+"px"),h=angular.element(n.document.createElement(a.$parentNodesScope.$element.prop("tagName"))).addClass(a.$parentNodesScope.$element.attr("class")).addClass(X.dragClass),h.css("width",t.width(l)+"px"),h.css("z-index",9999),S=(l[0].querySelector(".angular-ui-tree-handle")||l[0]).currentStyle,S&&(document.body.setAttribute("ui-tree-cursor",o.find("body").css("cursor")||""),o.find("body").css({cursor:S.cursor+"!important"})),a.sourceOnly&&m.css("display","none"),l.after(m),l.after(f),s.isClone()&&a.sourceOnly?h.append(r):h.append(l),o.find("body").append(h),h.css({left:g.pageX-p.offsetX+"px",top:g.pageY-p.offsetY+"px"}),v={placeholder:m,dragging:h},k(),a.$apply(function(){a.$treeScope.$callbacks.dragStart(s.eventArgs(v,p))}),y=Math.max(P.scrollHeight,P.offsetHeight,_.clientHeight,_.scrollHeight,_.offsetHeight),b=Math.max(P.scrollWidth,P.offsetWidth,_.clientWidth,_.scrollWidth,_.offsetWidth))}}},x=function(e){var o,r,l,u,c,f,S,x,N,E,w,C,T,O,M,k,D,I,H,V,U,P,_,L=t.eventObj(e);if(h){if(e.preventDefault(),n.getSelection?n.getSelection().removeAllRanges():n.document.selection&&n.document.selection.empty(),l=L.pageX-p.offsetX,u=L.pageY-p.offsetY,l<0&&(l=0),u<0&&(u=0),u+10>y&&(u=y-10),l+10>b&&(l=b-10),h.css({left:l+"px",top:u+"px"}),g?(S=g.getBoundingClientRect(),c=g.scrollTop,f=c+g.clientHeight,S.bottom<L.clientY&&f<g.scrollHeight&&(M=Math.min(g.scrollHeight-f,10),g.scrollTop+=M),S.top>L.clientY&&c>0&&(k=Math.min(c,10),g.scrollTop-=k)):(c=window.pageYOffset||n.document.documentElement.scrollTop,f=c+(window.innerHeight||n.document.clientHeight||n.document.clientHeight),f<L.pageY&&f<y&&(M=Math.min(y-f,10),window.scrollBy(0,M)),c>L.pageY&&(k=Math.min(c,10),window.scrollBy(0,-k))),t.positionMoved(e,p,d),d)return void(d=!1);if(N=L.pageX-(n.pageXOffset||n.document.body.scrollLeft||n.document.documentElement.scrollLeft)-(n.document.documentElement.clientLeft||0),E=L.pageY-(n.pageYOffset||n.document.body.scrollTop||n.document.documentElement.scrollTop)-(n.document.documentElement.clientTop||0),angular.isFunction(h.hide)?h.hide():(w=h[0].style.display,h[0].style.display="none"),n.document.elementFromPoint(N,E),T=angular.element(n.document.elementFromPoint(N,E)),A=t.treeNodeHandlerContainerOfElement(T),A&&(T=angular.element(A)),angular.isFunction(h.show)?h.show():h[0].style.display=w,t.elementIsTree(T)?C=T.controller("uiTree").scope:t.elementIsTreeNodeHandle(T)?C=T.controller("uiTreeHandle").scope:t.elementIsTreeNode(T)?C=T.controller("uiTreeNode").scope:t.elementIsTreeNodes(T)?C=T.controller("uiTreeNodes").scope:t.elementIsPlaceholder(T)?C=T.controller("uiTreeNodes").scope:T.controller("uiTreeNode")&&(C=T.controller("uiTreeNode").scope),H=C&&C.$treeScope&&C.$treeScope.$id&&C.$treeScope.$id===Y,H&&p.dirAx)p.distX>0&&(o=s.prev(),o&&!o.collapsed&&o.accept(a,o.childNodesCount())&&(o.$childNodesScope.$element.append(m),s.moveTo(o.$childNodesScope,o.childNodes(),o.childNodesCount()))),p.distX<0&&(r=s.next(),r||(x=s.parentNode(),x&&x.$parentNodesScope.accept(a,x.index()+1)&&(x.$element.after(m),s.moveTo(x.$parentNodesScope,x.siblings(),x.index()+1))));else{if(O=!1,!C)return;if(!C.$treeScope||C.$parent.nodropEnabled||C.$treeScope.nodropEnabled||m.css("display",""),"uiTree"===C.$type&&C.dragEnabled&&(O=C.isEmpty()),"uiTreeHandle"===C.$type&&(C=C.$nodeScope),"uiTreeNode"!==C.$type&&!O)return void(X.appendChildOnHover&&(r=s.next(),!r&&$&&(x=s.parentNode(),x.$element.after(m),s.moveTo(x.$parentNodesScope,x.siblings(),x.index()+1),$=!1)));B&&m.parent()[0]!=B.$element[0]&&(B.resetEmptyElement(),B=null),O?(B=C,C.$nodesScope.accept(a,0)&&(C.place(m),s.moveTo(C.$nodesScope,C.$nodesScope.childNodes(),0))):C.dragEnabled()&&(angular.isDefined(a.expandTimeoutOn)&&a.expandTimeoutOn!==C.id&&(i.cancel(a.expandTimeout),delete a.expandTimeout,delete a.expandTimeoutOn,a.$callbacks.expandTimeoutCancel()),C.collapsed&&(a.expandOnHover===!0||angular.isNumber(a.expandOnHover)&&0===a.expandOnHover?(C.collapsed=!1,C.$treeScope.$callbacks.toggle(!1,C)):a.expandOnHover!==!1&&angular.isNumber(a.expandOnHover)&&a.expandOnHover>0&&angular.isUndefined(a.expandTimeoutOn)&&(a.expandTimeoutOn=C.$id,a.$callbacks.expandTimeoutStart(),a.expandTimeout=i(function(){a.$callbacks.expandTimeoutEnd(),C.collapsed=!1,C.$treeScope.$callbacks.toggle(!1,C)},a.expandOnHover))),T=C.$element,D=t.offset(T),U=t.height(T),P=C.$childNodesScope?C.$childNodesScope.$element:null,_=P?t.height(P):0,U-=_,V=X.appendChildOnHover?.25*U:t.height(T)/2,I=L.pageY<D.top+V,C.$parentNodesScope.accept(a,C.index())?I?(T[0].parentNode.insertBefore(m[0],T[0]),s.moveTo(C.$parentNodesScope,C.siblings(),C.index())):X.appendChildOnHover&&C.accept(a,C.childNodesCount())?(C.$childNodesScope.$element.prepend(m),s.moveTo(C.$childNodesScope,C.childNodes(),0),$=!0):(T.after(m),s.moveTo(C.$parentNodesScope,C.siblings(),C.index()+1)):!I&&C.accept(a,C.childNodesCount())&&(C.$childNodesScope.$element.append(m),s.moveTo(C.$childNodesScope,C.childNodes(),C.childNodesCount())))}a.$apply(function(){a.$treeScope.$callbacks.dragMove(s.eventArgs(v,p))})}},N=function(e){var t=s.eventArgs(v,p);e.preventDefault(),D(),i.cancel(a.expandTimeout),a.$treeScope.$apply(function(){r.when(a.$treeScope.$callbacks.beforeDrop(t)).then(function(e){e!==!1&&a.$$allowNodeDrop?(s.apply(),a.$treeScope.$callbacks.dropped(t)):M()}).catch(function(){M()}).finally(function(){f.replaceWith(a.$element),m.remove(),h&&(h.remove(),h=null),a.$treeScope.$callbacks.dragStop(t),a.$$allowNodeDrop=!1,s=null;var e=document.body.getAttribute("ui-tree-cursor");null!==e&&(o.find("body").css({cursor:e}),document.body.removeAttribute("ui-tree-cursor"))})})},E=function(e){a.dragEnabled()&&S(e)},w=function(e){x(e)},C=function(e){a.$$allowNodeDrop=!0,N(e)},T=function(e){N(e)},O=function(){var e;return{exec:function(t,n){n||(n=0),this.cancel(),e=i(t,n)},cancel:function(){i.cancel(e)}}}(),I=function(e){27===e.keyCode&&C(e)},M=function(){l.bind("touchstart mousedown",function(e){a.dragDelay>0?O.exec(function(){E(e)},a.dragDelay):E(e)}),l.bind("touchend touchcancel mouseup",function(){a.dragDelay>0&&O.cancel()})},M(),k=function(){angular.element(o).bind("touchend",C),angular.element(o).bind("touchcancel",C),angular.element(o).bind("touchmove",w),angular.element(o).bind("mouseup",C),angular.element(o).bind("mousemove",w),angular.element(o).bind("mouseleave",T),angular.element(o).bind("keydown",I)},D=function(){angular.element(o).unbind("touchend",C),angular.element(o).unbind("touchcancel",C),angular.element(o).unbind("touchmove",w),angular.element(o).unbind("mouseup",C),angular.element(o).unbind("mousemove",w),angular.element(o).unbind("mouseleave",T),angular.element(o).unbind("keydown",I)}}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeNodes",["treeConfig","$window",function(e){return{require:["ngModel","?^uiTreeNode","^uiTree"],restrict:"A",scope:!0,controller:"TreeNodesController",link:function(t,n,o,i){var r={},a=i[0],l=i[1],u=i[2];angular.extend(r,e),r.nodesClass&&n.addClass(r.nodesClass),l?(l.scope.$childNodesScope=t,t.$nodeScope=l.scope):u.scope.$nodesScope=t,t.$treeScope=u.scope,a&&(a.$render=function(){t.$modelValue=a.$modelValue}),t.$watch(function(){return o.maxDepth},function(e){"number"==typeof e&&(t.maxDepth=e)}),t.$watch(function(){return o.nodropEnabled},function(e){"undefined"!=typeof e&&(t.nodropEnabled=!0)},!0)}}}])}(),function(){"use strict";function e(e,t){if(void 0===t)return null;for(var n=t.parentNode,o=1,i="function"==typeof n.setAttribute&&n.hasAttribute(e)?n:null;n&&"function"==typeof n.setAttribute&&!n.hasAttribute(e);){if(n=n.parentNode,i=n,n===document.documentElement){i=null;break}o++}return i}angular.module("ui.tree").factory("UiTreeHelper",["$document","$window","treeConfig",function(t,n,o){return{nodesData:{},setNodeAttribute:function(e,t,n){if(!e.$modelValue)return null;var o=this.nodesData[e.$modelValue.$$hashKey];o||(o={},this.nodesData[e.$modelValue.$$hashKey]=o),o[t]=n},getNodeAttribute:function(e,t){if(!e.$modelValue)return null;var n=this.nodesData[e.$modelValue.$$hashKey];return n?n[t]:null},nodrag:function(e){return"undefined"!=typeof e.attr("data-nodrag")&&"false"!==e.attr("data-nodrag")},eventObj:function(e){var t=e;return void 0!==e.targetTouches?t=e.targetTouches.item(0):void 0!==e.originalEvent&&void 0!==e.originalEvent.targetTouches&&(t=e.originalEvent.targetTouches.item(0)),t},dragInfo:function(e){return{source:e,sourceInfo:{cloneModel:e.$treeScope.cloneEnabled===!0?angular.copy(e.$modelValue):void 0,nodeScope:e,index:e.index(),nodesScope:e.$parentNodesScope},index:e.index(),siblings:e.siblings().slice(0),parent:e.$parentNodesScope,resetParent:function(){this.parent=e.$parentNodesScope},moveTo:function(e,t,n){this.parent=e,this.siblings=t.slice(0);var o=this.siblings.indexOf(this.source);o>-1&&(this.siblings.splice(o,1),this.source.index()<n&&n--),this.siblings.splice(n,0,this.source),this.index=n},parentNode:function(){return this.parent.$nodeScope},prev:function(){return this.index>0?this.siblings[this.index-1]:null},next:function(){return this.index<this.siblings.length-1?this.siblings[this.index+1]:null},isClone:function(){return this.source.$treeScope.cloneEnabled===!0},clonedNode:function(e){return angular.copy(e)},isDirty:function(){return this.source.$parentNodesScope!=this.parent||this.source.index()!=this.index},isForeign:function(){return this.source.$treeScope!==this.parent.$treeScope},eventArgs:function(e,t){return{source:this.sourceInfo,dest:{index:this.index,nodesScope:this.parent},elements:e,pos:t}},apply:function(){var e=this.source.$modelValue;this.parent.nodropEnabled||this.parent.$treeScope.nodropEnabled||this.isDirty()&&(this.isClone()&&this.isForeign()?this.parent.insertNode(this.index,this.sourceInfo.cloneModel):(this.source.remove(),this.parent.insertNode(this.index,e)))}}},height:function(e){return e.prop("scrollHeight")},width:function(e){return e.prop("scrollWidth")},offset:function(e){var o=e[0].getBoundingClientRect();return{width:e.prop("offsetWidth"),height:e.prop("offsetHeight"),top:o.top+(n.pageYOffset||t[0].body.scrollTop||t[0].documentElement.scrollTop),left:o.left+(n.pageXOffset||t[0].body.scrollLeft||t[0].documentElement.scrollLeft)}},positionStarted:function(e,t){var n={},o=e.pageX,i=e.pageY;return e.originalEvent&&e.originalEvent.touches&&e.originalEvent.touches.length>0&&(o=e.originalEvent.touches[0].pageX,i=e.originalEvent.touches[0].pageY),n.offsetX=o-this.offset(t).left,n.offsetY=i-this.offset(t).top,n.startX=n.lastX=o,n.startY=n.lastY=i,n.nowX=n.nowY=n.distX=n.distY=n.dirAx=0,n.dirX=n.dirY=n.lastDirX=n.lastDirY=n.distAxX=n.distAxY=0,n},positionMoved:function(e,t,n){var o,i=e.pageX,r=e.pageY;return e.originalEvent&&e.originalEvent.touches&&e.originalEvent.touches.length>0&&(i=e.originalEvent.touches[0].pageX,r=e.originalEvent.touches[0].pageY),t.lastX=t.nowX,t.lastY=t.nowY,t.nowX=i,t.nowY=r,t.distX=t.nowX-t.lastX,t.distY=t.nowY-t.lastY,t.lastDirX=t.dirX,t.lastDirY=t.dirY,t.dirX=0===t.distX?0:t.distX>0?1:-1,t.dirY=0===t.distY?0:t.distY>0?1:-1,o=Math.abs(t.distX)>Math.abs(t.distY)?1:0,n?(t.dirAx=o,void(t.moving=!0)):(t.dirAx!==o?(t.distAxX=0,t.distAxY=0):(t.distAxX+=Math.abs(t.distX),0!==t.dirX&&t.dirX!==t.lastDirX&&(t.distAxX=0),t.distAxY+=Math.abs(t.distY),0!==t.dirY&&t.dirY!==t.lastDirY&&(t.distAxY=0)),void(t.dirAx=o))},elementIsTreeNode:function(e){return"undefined"!=typeof e.attr("ui-tree-node")},elementIsTreeNodeHandle:function(e){return"undefined"!=typeof e.attr("ui-tree-handle")},elementIsTree:function(e){return"undefined"!=typeof e.attr("ui-tree")},elementIsTreeNodes:function(e){return"undefined"!=typeof e.attr("ui-tree-nodes")},elementIsPlaceholder:function(e){return e.hasClass(o.placeholderClass)},elementContainsTreeNodeHandler:function(e){return e[0].querySelectorAll("[ui-tree-handle]").length>=1},treeNodeHandlerContainerOfElement:function(t){return e("ui-tree-handle",t[0])}}}])}()},,,,function(e,t,n){n(17),n(18),n(13),n(15),n(14),e.exports=n(16)}]);