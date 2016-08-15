!function(e,t){"use strict";e.module("smart-table",[]).run(["$templateCache",function(e){e.put("template/smart-table/pagination.html",'<nav ng-if="numPages && pages.length >= 2"><ul class="pagination"><li ng-repeat="page in pages" ng-class="{active: page==currentPage}"><a href="javascript: void(0);" ng-click="selectPage(page)">{{page}}</a></li></ul></nav>')}]),e.module("smart-table").constant("stConfig",{pagination:{template:"template/smart-table/pagination.html",itemsByPage:10,displayedPages:5},search:{delay:400,inputEvent:"input"},select:{mode:"single",selectedClass:"st-selected"},sort:{ascentClass:"st-sort-ascent",descentClass:"st-sort-descent",descendingFirst:!1,skipNatural:!1,delay:300},pipe:{delay:100}}),e.module("smart-table").controller("stTableController",["$scope","$parse","$filter","$attrs",function(a,n,s,i){function r(e){return e?[].concat(e):[]}function l(){v=r(o(a)),b===!0&&P.pipe()}function c(e,t){if(t.indexOf(".")!=-1){var a=t.split("."),s=a.pop(),i=a.join("."),r=n(i)(e);delete r[s],0==Object.keys(r).length&&c(e,i)}else delete e[t]}var o,u,d,g=i.stTable,p=n(g),f=p.assign,h=s("orderBy"),m=s("filter"),v=r(p(a)),S={sort:{},search:{},pagination:{start:0,totalItemCount:0}},b=!0,P=this;i.stSafeSrc&&(o=n(i.stSafeSrc),a.$watch(function(){var e=o(a);return e&&e.length?e[0]:t},function(e,t){e!==t&&l()}),a.$watch(function(){var e=o(a);return e?e.length:0},function(e,t){e!==v.length&&l()}),a.$watch(function(){return o(a)},function(e,t){e!==t&&(S.pagination.start=0,l())})),this.sortBy=function(t,a){return S.sort.predicate=t,S.sort.reverse=a===!0,e.isFunction(t)?S.sort.functionName=t.name:delete S.sort.functionName,S.pagination.start=0,this.pipe()},this.search=function(t,a){var s=S.search.predicateObject||{},i=a?a:"$";return t=e.isString(t)?t.trim():t,n(i).assign(s,t),t||c(s,i),S.search.predicateObject=s,S.pagination.start=0,this.pipe()},this.pipe=function(){var e,n=S.pagination;u=S.search.predicateObject?m(v,S.search.predicateObject):v,S.sort.predicate&&(u=h(u,S.sort.predicate,S.sort.reverse)),n.totalItemCount=u.length,n.number!==t&&(n.numberOfPages=u.length>0?Math.ceil(u.length/n.number):1,n.start=n.start>=u.length?(n.numberOfPages-1)*n.number:n.start,e=u.slice(n.start,n.start+parseInt(n.number))),f(a,e||u)},this.select=function(e,n){var s=r(p(a)),i=s.indexOf(e);i!==-1&&("single"===n?(e.isSelected=e.isSelected!==!0,d&&(d.isSelected=!1),d=e.isSelected===!0?e:t):s[i].isSelected=!s[i].isSelected)},this.slice=function(e,t){return S.pagination.start=e,S.pagination.number=t,this.pipe()},this.tableState=function(){return S},this.getFilteredCollection=function(){return u||v},this.setFilterFunction=function(e){m=s(e)},this.setSortFunction=function(e){h=s(e)},this.preventPipeOnWatch=function(){b=!1}}]).directive("stTable",function(){return{restrict:"A",controller:"stTableController",link:function(e,t,a,n){a.stSetFilter&&n.setFilterFunction(a.stSetFilter),a.stSetSort&&n.setSortFunction(a.stSetSort)}}}),e.module("smart-table").directive("stSearch",["stConfig","$timeout","$parse",function(e,t,a){return{require:"^stTable",link:function(n,s,i,r){var l=r,c=null,o=i.stDelay||e.search.delay,u=i.stInputEvent||e.search.inputEvent;i.$observe("stSearch",function(e,t){var a=s[0].value;e!==t&&a&&(r.tableState().search={},l.search(a,e))}),n.$watch(function(){return r.tableState().search},function(e,t){var n=i.stSearch||"$";e.predicateObject&&a(n)(e.predicateObject)!==s[0].value&&(s[0].value=a(n)(e.predicateObject)||"")},!0),s.bind(u,function(e){e=e.originalEvent||e,null!==c&&t.cancel(c),c=t(function(){l.search(e.target.value,i.stSearch||""),c=null},o)})}}}]),e.module("smart-table").directive("stSelectRow",["stConfig",function(e){return{restrict:"A",require:"^stTable",scope:{row:"=stSelectRow"},link:function(t,a,n,s){var i=n.stSelectMode||e.select.mode;a.bind("click",function(){t.$apply(function(){s.select(t.row,i)})}),t.$watch("row.isSelected",function(t){t===!0?a.addClass(e.select.selectedClass):a.removeClass(e.select.selectedClass)})}}}]),e.module("smart-table").directive("stSort",["stConfig","$parse","$timeout",function(a,n,s){return{restrict:"A",require:"^stTable",link:function(i,r,l,c){function o(){S?p=0===p?2:p-1:p++;var t;d=e.isFunction(g(i))||e.isArray(g(i))?g(i):l.stSort,p%3===0&&!!v!=!0?(p=0,c.tableState().sort={},c.tableState().pagination.start=0,t=c.pipe.bind(c)):t=c.sortBy.bind(c,d,p%2===0),null!==b&&s.cancel(b),P<0?t():b=s(t,P)}var u,d=l.stSort,g=n(d),p=0,f=l.stClassAscent||a.sort.ascentClass,h=l.stClassDescent||a.sort.descentClass,m=[f,h],v=l.stSkipNatural!==t?l.stSkipNatural:a.sort.skipNatural,S=l.stDescendingFirst!==t?l.stDescendingFirst:a.sort.descendingFirst,b=null,P=l.stDelay||a.sort.delay;l.stSortDefault&&(u=i.$eval(l.stSortDefault)!==t?i.$eval(l.stSortDefault):l.stSortDefault),r.bind("click",function(){d&&i.$apply(o)}),u&&(p="reverse"===u?1:0,o()),i.$watch(function(){return c.tableState().sort},function(e){e.predicate!==d?(p=0,r.removeClass(f).removeClass(h)):(p=e.reverse===!0?2:1,r.removeClass(m[p%2]).addClass(m[p-1]))},!0)}}}]),e.module("smart-table").directive("stPagination",["stConfig",function(e){return{restrict:"EA",require:"^stTable",scope:{stItemsByPage:"=?",stDisplayedPages:"=?",stPageChange:"&"},templateUrl:function(t,a){return a.stTemplate?a.stTemplate:e.pagination.template},link:function(t,a,n,s){function i(){var e,a,n=s.tableState().pagination,i=1,r=t.currentPage;for(t.totalItemCount=n.totalItemCount,t.currentPage=Math.floor(n.start/n.number)+1,i=Math.max(i,t.currentPage-Math.abs(Math.floor(t.stDisplayedPages/2))),e=i+t.stDisplayedPages,e>n.numberOfPages&&(e=n.numberOfPages+1,i=Math.max(1,e-t.stDisplayedPages)),t.pages=[],t.numPages=n.numberOfPages,a=i;a<e;a++)t.pages.push(a);r!==t.currentPage&&t.stPageChange({newPage:t.currentPage})}t.stItemsByPage=t.stItemsByPage?+t.stItemsByPage:e.pagination.itemsByPage,t.stDisplayedPages=t.stDisplayedPages?+t.stDisplayedPages:e.pagination.displayedPages,t.currentPage=1,t.pages=[],t.$watch(function(){return s.tableState().pagination},i,!0),t.$watch("stItemsByPage",function(e,a){e!==a&&t.selectPage(1)}),t.$watch("stDisplayedPages",i),t.selectPage=function(e){e>0&&e<=t.numPages&&s.slice((e-1)*t.stItemsByPage,t.stItemsByPage)},s.tableState().pagination.number||s.slice(0,t.stItemsByPage)}}}]),e.module("smart-table").directive("stPipe",["stConfig","$timeout",function(t,a){return{require:"stTable",scope:{stPipe:"="},link:{pre:function(n,s,i,r){var l=null;e.isFunction(n.stPipe)&&(r.preventPipeOnWatch(),r.pipe=function(){return null!==l&&a.cancel(l),l=a(function(){n.stPipe(r.tableState(),r)},t.pipe.delay)})},post:function(e,t,a,n){n.pipe()}}}}])}(angular),function(){var e=angular.module("mks-smart-table",["smart-table"]);e.run(["$templateCache",function(e){e.put("template/smart-table/pagination.html",'<nav ng-if="numPages && pages.length >= 2" aria-label="Page navigation"><ul class="pagination"><li class="page-item"><a class="page-link" href="javascript: void(0);" ng-click="selectPage(1)"><span>&laquo;</span></a></li><li class="page-item" ng-repeat="page in pages" ng-class="{active: page==currentPage}"><a class="page-link" href="javascript: void(0);" ng-click="selectPage(page)">{{page}}</a></li><li class="page-item"><a class="page-link" href="javascript: void(0);" ng-click="selectPage(numPages)"><span>&raquo;</span></a></li></ul></nav>')}]),e.controller("TableCtrl",["$http","$q","$scope","$filter",function(e,t,a,n){function s(a,n,s,i){c&&c.resolve("cancel"),c=t.defer();var r={start:n,number:s};i.sort&&(r.sort=i.sort),i.search&&i.search.predicateObject&&(r.search=i.search.predicateObject);var l={method:"GET",url:a+"?"+jQuery.param(r),headers:{"X-Requested-With":"XMLHttpRequest"},timeout:c.promise};return e(l)}function i(e){var t=l.rows.indexOf(e);t!==-1&&(l.rows.splice(t,1),l.total>0&&l.total--,e.isSelected&&l.hasSelected>0&&l.hasSelected--)}function r(e){return jQuery.map(e,function(e){return e[l.idKey]})}this.rows=[],this.url=null,this.start=0,this.end=0,this.total=0,this.hasSelected=0,this.idKey="id";var l=this,c=null;this.init=function(e,t){this.url=e,t&&(this.idKey=t)},this.pipeServer=function(e){if(l.url){l.isLoading=!0;var t=e.pagination,a=t.start||0,n=t.number||10;s(l.url,a,n,e).success(function(t){l.rows=t.data,e.pagination.numberOfPages=t.pages;var a=e.pagination.start;l.end=a+l.rows.length,l.rows.length>0&&a++,l.start=a,l.total=t.total,l.hasSelected=0,l.isLoading=!1}).error(function(){l.isLoading=!1})}},this.removeRow=function(t,a,n){return!(n&&!confirm(n))&&(a?(t.isLoading=!0,e.post(a).then(function(){t.isLoading=!1,i(t)},function(){t.isLoading=!1}),!1):(i(t),!1))},this.getSelected=function(){return n("filter")(this.rows,{isSelected:!0})},this.removeSelected=function(t,a){if(a&&!confirm(a))return!1;var n=this.getSelected();return!!n.length&&(t?(l.isLoading=!0,void e.post(t,{id:r(n)}).then(function(){angular.forEach(n,function(e){i(e)}),l.isLoading=!1},function(){l.isLoading=!1})):(angular.forEach(n,function(e){i(e)}),!1))},a.$on("row-selected",function(e,t){t?l.hasSelected++:l.hasSelected>0&&l.hasSelected--}),this.updateRow=function(t,a,n){return!(n&&!confirm(n))&&(t.isLoading=!0,e.post(a).then(function(e){t.isLoading=!1;var a=e.data;a&&a.model&&angular.extend(t,a.model)},function(){t.isLoading=!1}),!1)},this.updateSelected=function(t,a){if(a&&!confirm(a))return!1;var n=this.getSelected();return!!n.length&&(l.isLoading=!0,void e.post(t,{id:r(n)}).then(function(e){var t=e.data;t&&t.models&&angular.forEach(n,function(e){"undefined"!=typeof t.models[e[l.idKey]]&&angular.extend(e,t.models[e[l.idKey]])}),l.isLoading=!1},function(){l.isLoading=!1}))}}]),e.directive("mstWatchQuery",[function(){return{restrict:"A",require:"^stTable",scope:{mstWatchQuery:"="},link:function(e,t,a,n){e.$watch("mstWatchQuery",function(e){n.search(e)})}}}]),e.directive("mstSelectRow",[function(){return{restrict:"EA",template:'<i class="text-muted fa fa-square-o st-chk"></i>',scope:{row:"=mstSelectRow"},link:function(e,t){return!e.row.non_selectable&&(t.on("click",function(t){t.preventDefault(),e.$apply(function(){e.row.isSelected=!e.row.isSelected})}),void e.$watch("row.isSelected",function(a,n){t.parent().toggleClass("st-selected table-info",1==a),t.children().toggleClass("text-muted fa-square-o",a!==!0).toggleClass("fa-check-square st-checked",1==a),e.$emit("row-selected",a)}))}}}]),e.directive("mstSelectAllRows",[function(){return{restrict:"EA",template:'<i class="text-muted fa fa-square-o st-chk"></i>',scope:{all:"=mstSelectAllRows"},link:function(e,t){t.on("click",function(t){t.preventDefault(),e.$apply(function(){e.isAllSelected=!e.isAllSelected||!1})}),e.$watch("isAllSelected",function(){e.all&&(e.all.forEach(function(t){t.non_selectable||(t.isSelected=e.isAllSelected||!1)}),t.children().toggleClass("text-muted fa-square-o",e.isAllSelected!==!0).toggleClass("fa-check-square st-checked",1==e.isAllSelected))}),e.$watch("all",function(t,a){e.isAllSelected=!1})}}}])}(window.angular);