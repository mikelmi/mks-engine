!function(){"use strict";angular.module("ui.tree",[]).constant("treeConfig",{treeClass:"angular-ui-tree",emptyTreeClass:"angular-ui-tree-empty",hiddenClass:"angular-ui-tree-hidden",nodesClass:"angular-ui-tree-nodes",nodeClass:"angular-ui-tree-node",handleClass:"angular-ui-tree-handle",placeholderClass:"angular-ui-tree-placeholder",dragClass:"angular-ui-tree-drag",dragThreshold:3,levelThreshold:30,defaultCollapsed:!1})}(),function(){"use strict";angular.module("ui.tree").controller("TreeHandleController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$nodeScope=null,e.$type="uiTreeHandle"}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeNodeController",["$scope","$element",function(e,t){function n(e){var t,o,i,r=0,l=e.childNodes();if(!l||0===l.length)return 0;for(i=l.length-1;i>=0;i--)t=l[i],o=1+n(t),r=Math.max(r,o);return r}this.scope=e,e.$element=t,e.$modelValue=null,e.$parentNodeScope=null,e.$childNodesScope=null,e.$parentNodesScope=null,e.$treeScope=null,e.$handleScope=null,e.$type="uiTreeNode",e.$$allowNodeDrop=!1,e.collapsed=!1,e.expandOnHover=!1,e.init=function(n){var o=n[0];e.$treeScope=n[1]?n[1].scope:null,e.$parentNodeScope=o.scope.$nodeScope,e.$modelValue=o.scope.$modelValue[e.$index],e.$parentNodesScope=o.scope,o.scope.initSubNode(e),t.on("$destroy",function(){o.scope.destroySubNode(e)})},e.index=function(){return e.$parentNodesScope.$modelValue.indexOf(e.$modelValue)},e.dragEnabled=function(){return!(e.$treeScope&&!e.$treeScope.dragEnabled)},e.isSibling=function(t){return e.$parentNodesScope==t.$parentNodesScope},e.isChild=function(t){var n=e.childNodes();return n&&n.indexOf(t)>-1},e.prev=function(){var t=e.index();return t>0?e.siblings()[t-1]:null},e.siblings=function(){return e.$parentNodesScope.childNodes()},e.childNodesCount=function(){return e.childNodes()?e.childNodes().length:0},e.hasChild=function(){return e.childNodesCount()>0},e.childNodes=function(){return e.$childNodesScope&&e.$childNodesScope.$modelValue?e.$childNodesScope.childNodes():null},e.accept=function(t,n){return e.$childNodesScope&&e.$childNodesScope.$modelValue&&e.$childNodesScope.accept(t,n)},e.remove=function(){return e.$parentNodesScope.removeNode(e)},e.toggle=function(){e.collapsed=!e.collapsed,e.$treeScope.$callbacks.toggle(e.collapsed,e)},e.collapse=function(){e.collapsed=!0},e.expand=function(){e.collapsed=!1},e.depth=function(){var t=e.$parentNodeScope;return t?t.depth()+1:1},e.maxSubDepth=function(){return e.$childNodesScope?n(e.$childNodesScope):0}}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeNodesController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$modelValue=null,e.$nodeScope=null,e.$treeScope=null,e.$type="uiTreeNodes",e.$nodesMap={},e.nodropEnabled=!1,e.maxDepth=0,e.cloneEnabled=!1,e.initSubNode=function(t){return t.$modelValue?void(e.$nodesMap[t.$modelValue.$$hashKey]=t):null},e.destroySubNode=function(t){return t.$modelValue?void(e.$nodesMap[t.$modelValue.$$hashKey]=null):null},e.accept=function(t,n){return e.$treeScope.$callbacks.accept(t,e,n)},e.beforeDrag=function(t){return e.$treeScope.$callbacks.beforeDrag(t)},e.isParent=function(t){return t.$parentNodesScope==e},e.hasChild=function(){return e.$modelValue.length>0},e.safeApply=function(e){var t=this.$root.$$phase;"$apply"==t||"$digest"==t?e&&"function"==typeof e&&e():this.$apply(e)},e.removeNode=function(t){var n=e.$modelValue.indexOf(t.$modelValue);return n>-1?(e.safeApply(function(){e.$modelValue.splice(n,1)[0]}),e.$treeScope.$callbacks.removed(t)):null},e.insertNode=function(t,n){e.safeApply(function(){e.$modelValue.splice(t,0,n)})},e.childNodes=function(){var t,n=[];if(e.$modelValue)for(t=0;t<e.$modelValue.length;t++)n.push(e.$nodesMap[e.$modelValue[t].$$hashKey]);return n},e.depth=function(){return e.$nodeScope?e.$nodeScope.depth():0},e.outOfDepth=function(t){var n=e.maxDepth||e.$treeScope.maxDepth;return n>0&&e.depth()+t.maxSubDepth()+1>n}}])}(),function(){"use strict";angular.module("ui.tree").controller("TreeController",["$scope","$element",function(e,t){this.scope=e,e.$element=t,e.$nodesScope=null,e.$type="uiTree",e.$emptyElm=null,e.$callbacks=null,e.dragEnabled=!0,e.emptyPlaceholderEnabled=!0,e.maxDepth=0,e.dragDelay=0,e.cloneEnabled=!1,e.nodropEnabled=!1,e.isEmpty=function(){return e.$nodesScope&&e.$nodesScope.$modelValue&&0===e.$nodesScope.$modelValue.length},e.place=function(t){e.$nodesScope.$element.append(t),e.$emptyElm.remove()},this.resetEmptyElement=function(){e.$nodesScope.$modelValue&&0!==e.$nodesScope.$modelValue.length||!e.emptyPlaceholderEnabled?e.$emptyElm.remove():t.append(e.$emptyElm)},e.resetEmptyElement=this.resetEmptyElement}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTree",["treeConfig","$window",function(e,t){return{restrict:"A",scope:!0,controller:"TreeController",link:function(n,o,i,r){var l,a,d,u={accept:null,beforeDrag:null},c={};angular.extend(c,e),c.treeClass&&o.addClass(c.treeClass),"table"===o.prop("tagName").toLowerCase()?(n.$emptyElm=angular.element(t.document.createElement("tr")),a=o.find("tr"),d=a.length>0?angular.element(a).children().length:1e6,l=angular.element(t.document.createElement("td")).attr("colspan",d),n.$emptyElm.append(l)):n.$emptyElm=angular.element(t.document.createElement("div")),c.emptyTreeClass&&n.$emptyElm.addClass(c.emptyTreeClass),n.$watch("$nodesScope.$modelValue.length",function(e){angular.isNumber(e)&&r.resetEmptyElement()},!0),n.$watch(i.dragEnabled,function(e){"boolean"==typeof e&&(n.dragEnabled=e)}),n.$watch(i.emptyPlaceholderEnabled,function(e){"boolean"==typeof e&&(n.emptyPlaceholderEnabled=e,r.resetEmptyElement())}),n.$watch(i.nodropEnabled,function(e){"boolean"==typeof e&&(n.nodropEnabled=e)}),n.$watch(i.cloneEnabled,function(e){"boolean"==typeof e&&(n.cloneEnabled=e)}),n.$watch(i.maxDepth,function(e){"number"==typeof e&&(n.maxDepth=e)}),n.$watch(i.dragDelay,function(e){"number"==typeof e&&(n.dragDelay=e)}),u.accept=function(e,t,n){return!(t.nodropEnabled||t.$treeScope.nodropEnabled||t.outOfDepth(e))},u.beforeDrag=function(e){return!0},u.expandTimeoutStart=function(){},u.expandTimeoutCancel=function(){},u.expandTimeoutEnd=function(){},u.removed=function(e){},u.dropped=function(e){},u.dragStart=function(e){},u.dragMove=function(e){},u.dragStop=function(e){},u.beforeDrop=function(e){},u.toggle=function(e,t){},n.$watch(i.uiTree,function(e,t){angular.forEach(e,function(e,t){u[t]&&"function"==typeof e&&(u[t]=e)}),n.$callbacks=u},!0)}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeHandle",["treeConfig",function(e){return{require:"^uiTreeNode",restrict:"A",scope:!0,controller:"TreeHandleController",link:function(t,n,o,i){var r={};angular.extend(r,e),r.handleClass&&n.addClass(r.handleClass),t!=i.scope&&(t.$nodeScope=i.scope,i.scope.$handleScope=t)}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeNode",["treeConfig","UiTreeHelper","$window","$document","$timeout","$q",function(e,t,n,o,i,r){return{require:["^uiTreeNodes","^uiTree"],restrict:"A",controller:"TreeNodeController",link:function(l,a,d,u){var c,s,p,m,f,g,h,$,v,y,b,S,x,N,E,w,T,C,M,O,k,I,D,A,V={},H="ontouchstart"in window,X=null,Y=document.body,U=document.documentElement;angular.extend(V,e),V.nodeClass&&a.addClass(V.nodeClass),l.init(u),l.collapsed=!!t.getNodeAttribute(l,"collapsed")||e.defaultCollapsed,l.expandOnHover=!!t.getNodeAttribute(l,"expandOnHover"),l.sourceOnly=l.nodropEnabled||l.$treeScope.nodropEnabled,l.$watch(d.collapsed,function(e){"boolean"==typeof e&&(l.collapsed=e)}),l.$watch("collapsed",function(e){t.setNodeAttribute(l,"collapsed",e),d.$set("collapsed",e)}),l.$watch(d.expandOnHover,function(e){"boolean"!=typeof e&&"number"!=typeof e||(l.expandOnHover=e)}),l.$watch("expandOnHover",function(e){t.setNodeAttribute(l,"expandOnHover",e),d.$set("expandOnHover",e)}),l.$on("angular-ui-tree:collapse-all",function(){l.collapsed=!0}),l.$on("angular-ui-tree:expand-all",function(){l.collapsed=!1}),y=function(e){if((H||2!==e.button&&3!==e.which)&&!(e.uiTreeDragging||e.originalEvent&&e.originalEvent.uiTreeDragging)){var i,r,d,u,y,b,S,x,N,E=angular.element(e.target);if(i=t.treeNodeHandlerContainerOfElement(E),i&&(E=angular.element(i)),r=a.clone(),x=t.elementIsTreeNode(E),N=t.elementIsTreeNodeHandle(E),(x||N)&&!(x&&t.elementContainsTreeNodeHandler(E)||(d=E.prop("tagName").toLowerCase(),"input"==d||"textarea"==d||"button"==d||"select"==d))){for(A=angular.element(e.target);A&&A[0]&&A[0]!==a;){if(t.nodrag(A))return;A=A.parent()}l.beforeDrag(l)&&(e.uiTreeDragging=!0,e.originalEvent&&(e.originalEvent.uiTreeDragging=!0),e.preventDefault(),y=t.eventObj(e),c=!0,s=t.dragInfo(l),u=a.prop("tagName"),"tr"===u.toLowerCase()?(m=angular.element(n.document.createElement(u)),b=angular.element(n.document.createElement("td")).addClass(V.placeholderClass).attr("colspan",a[0].children.length),m.append(b)):m=angular.element(n.document.createElement(u)).addClass(V.placeholderClass),f=angular.element(n.document.createElement(u)),V.hiddenClass&&f.addClass(V.hiddenClass),p=t.positionStarted(y,a),m.css("height",t.height(a)+"px"),g=angular.element(n.document.createElement(l.$parentNodesScope.$element.prop("tagName"))).addClass(l.$parentNodesScope.$element.attr("class")).addClass(V.dragClass),g.css("width",t.width(a)+"px"),g.css("z-index",9999),S=(a[0].querySelector(".angular-ui-tree-handle")||a[0]).currentStyle,S&&(document.body.setAttribute("ui-tree-cursor",o.find("body").css("cursor")||""),o.find("body").css({cursor:S.cursor+"!important"})),l.sourceOnly&&m.css("display","none"),a.after(m),a.after(f),s.isClone()&&l.sourceOnly?g.append(r):g.append(a),o.find("body").append(g),g.css({left:y.pageX-p.offsetX+"px",top:y.pageY-p.offsetY+"px"}),h={placeholder:m,dragging:g},M(),l.$apply(function(){l.$treeScope.$callbacks.dragStart(s.eventArgs(h,p))}),$=Math.max(Y.scrollHeight,Y.offsetHeight,U.clientHeight,U.scrollHeight,U.offsetHeight),v=Math.max(Y.scrollWidth,Y.offsetWidth,U.clientWidth,U.scrollWidth,U.offsetWidth))}}},b=function(e){var o,r,a,d,u,f,y,b,S,x,N,E,w,T,C,M,O,k=t.eventObj(e);if(g){if(e.preventDefault(),n.getSelection?n.getSelection().removeAllRanges():n.document.selection&&n.document.selection.empty(),a=k.pageX-p.offsetX,d=k.pageY-p.offsetY,a<0&&(a=0),d<0&&(d=0),d+10>$&&(d=$-10),a+10>v&&(a=v-10),g.css({left:a+"px",top:d+"px"}),u=window.pageYOffset||n.document.documentElement.scrollTop,f=u+(window.innerHeight||n.document.clientHeight||n.document.clientHeight),f<k.pageY&&f<$&&(C=Math.min($-f,10),window.scrollBy(0,C)),u>k.pageY&&window.scrollBy(0,-10),t.positionMoved(e,p,c),c)return void(c=!1);if(b=t.offset(g).left-t.offset(m).left>=V.threshold,S=k.pageX-(n.pageXOffset||n.document.body.scrollLeft||n.document.documentElement.scrollLeft)-(n.document.documentElement.clientLeft||0),x=k.pageY-(n.pageYOffset||n.document.body.scrollTop||n.document.documentElement.scrollTop)-(n.document.documentElement.clientTop||0),angular.isFunction(g.hide)?g.hide():(N=g[0].style.display,g[0].style.display="none"),n.document.elementFromPoint(S,x),w=angular.element(n.document.elementFromPoint(S,x)),D=t.treeNodeHandlerContainerOfElement(w),D&&(w=angular.element(D)),angular.isFunction(g.show)?g.show():g[0].style.display=N,I=!(t.elementIsTreeNodeHandle(w)||t.elementIsTreeNode(w)||t.elementIsTreeNodes(w)||t.elementIsTree(w)||t.elementIsPlaceholder(w)),I&&(m.remove(),X&&(X.resetEmptyElement(),X=null)),p.dirAx&&p.distAxX>=V.levelThreshold&&(p.distAxX=0,p.distX>0&&(o=s.prev(),o&&!o.collapsed&&o.accept(l,o.childNodesCount())&&(o.$childNodesScope.$element.append(m),s.moveTo(o.$childNodesScope,o.childNodes(),o.childNodesCount()))),p.distX<0&&(r=s.next(),r||(y=s.parentNode(),y&&y.$parentNodesScope.accept(l,y.index()+1)&&(y.$element.after(m),s.moveTo(y.$parentNodesScope,y.siblings(),y.index()+1))))),!p.dirAx){if(t.elementIsTree(w)?E=w.controller("uiTree").scope:t.elementIsTreeNodeHandle(w)?E=w.controller("uiTreeHandle").scope:t.elementIsTreeNode(w)?E=w.controller("uiTreeNode").scope:t.elementIsTreeNodes(w)?E=w.controller("uiTreeNodes").scope:t.elementIsPlaceholder(w)?E=w.controller("uiTreeNodes").scope:w.controller("uiTreeNode")&&(E=w.controller("uiTreeNode").scope),T=!1,!E)return;if(!E.$treeScope||E.$parent.nodropEnabled||E.$treeScope.nodropEnabled||m.css("display",""),"uiTree"==E.$type&&E.dragEnabled&&(T=E.isEmpty()),"uiTreeHandle"==E.$type&&(E=E.$nodeScope),"uiTreeNode"!=E.$type&&!T)return;X&&m.parent()[0]!=X.$element[0]&&(X.resetEmptyElement(),X=null),T?(X=E,E.$nodesScope.accept(l,0)&&(E.place(m),s.moveTo(E.$nodesScope,E.$nodesScope.childNodes(),0))):E.dragEnabled()&&(angular.isDefined(l.expandTimeoutOn)&&l.expandTimeoutOn!==E.id&&(i.cancel(l.expandTimeout),delete l.expandTimeout,delete l.expandTimeoutOn,l.$callbacks.expandTimeoutCancel()),E.collapsed&&(l.expandOnHover===!0||angular.isNumber(l.expandOnHover)&&0===l.expandOnHover?E.collapsed=!1:l.expandOnHover!==!1&&angular.isNumber(l.expandOnHover)&&l.expandOnHover>0&&angular.isUndefined(l.expandTimeoutOn)&&(l.expandTimeoutOn=E.$id,l.$callbacks.expandTimeoutStart(),l.expandTimeout=i(function(){l.$callbacks.expandTimeoutEnd(),E.collapsed=!1},l.expandOnHover))),w=E.$element,M=t.offset(w),O=E.horizontal?k.pageX<M.left+t.width(w)/2:k.pageY<M.top+t.height(w)/2,E.$parentNodesScope.accept(l,E.index())?O?(w[0].parentNode.insertBefore(m[0],w[0]),s.moveTo(E.$parentNodesScope,E.siblings(),E.index())):(w.after(m),s.moveTo(E.$parentNodesScope,E.siblings(),E.index()+1)):!O&&E.accept(l,E.childNodesCount())?(E.$childNodesScope.$element.append(m),s.moveTo(E.$childNodesScope,E.childNodes(),E.childNodesCount())):I=!0)}l.$apply(function(){l.$treeScope.$callbacks.dragMove(s.eventArgs(h,p))})}},S=function(e){var t=s.eventArgs(h,p);e.preventDefault(),O(),i.cancel(l.expandTimeout),l.$treeScope.$apply(function(){r.when(l.$treeScope.$callbacks.beforeDrop(t)).then(function(e){e!==!1&&l.$$allowNodeDrop&&!I?(s.apply(),l.$treeScope.$callbacks.dropped(t)):C()})["catch"](function(){C()})["finally"](function(){f.replaceWith(l.$element),m.remove(),g&&(g.remove(),g=null),l.$treeScope.$callbacks.dragStop(t),l.$$allowNodeDrop=!1,s=null;var e=document.body.getAttribute("ui-tree-cursor");null!==e&&(o.find("body").css({cursor:e}),document.body.removeAttribute("ui-tree-cursor"))})})},x=function(e){l.dragEnabled()&&y(e)},N=function(e){b(e)},E=function(e){l.$$allowNodeDrop=!0,S(e)},w=function(e){S(e)},T=function(){var e;return{exec:function(t,n){n||(n=0),this.cancel(),e=i(t,n)},cancel:function(){i.cancel(e)}}}(),C=function(){a.bind("touchstart mousedown",function(e){T.exec(function(){x(e)},l.dragDelay||0)}),a.bind("touchend touchcancel mouseup",function(){T.cancel()})},C(),M=function(){angular.element(o).bind("touchend",E),angular.element(o).bind("touchcancel",E),angular.element(o).bind("touchmove",N),angular.element(o).bind("mouseup",E),angular.element(o).bind("mousemove",N),angular.element(o).bind("mouseleave",w)},O=function(){angular.element(o).unbind("touchend",E),angular.element(o).unbind("touchcancel",E),angular.element(o).unbind("touchmove",N),angular.element(o).unbind("mouseup",E),angular.element(o).unbind("mousemove",N),angular.element(o).unbind("mouseleave",w)},k=function(e){27==e.keyCode&&(l.$$allowNodeDrop=!1,S(e))},angular.element(n.document).bind("keydown",k),l.$on("$destroy",function(){angular.element(n.document).unbind("keydown",k)})}}}])}(),function(){"use strict";angular.module("ui.tree").directive("uiTreeNodes",["treeConfig","$window",function(e){return{require:["ngModel","?^uiTreeNode","^uiTree"],restrict:"A",scope:!0,controller:"TreeNodesController",link:function(t,n,o,i){var r={},l=i[0],a=i[1],d=i[2];angular.extend(r,e),r.nodesClass&&n.addClass(r.nodesClass),a?(a.scope.$childNodesScope=t,t.$nodeScope=a.scope):d.scope.$nodesScope=t,t.$treeScope=d.scope,l&&(l.$render=function(){t.$modelValue=l.$modelValue}),t.$watch(function(){return o.maxDepth},function(e){"number"==typeof e&&(t.maxDepth=e)}),t.$watch(function(){return o.nodropEnabled},function(e){"undefined"!=typeof e&&(t.nodropEnabled=!0)},!0),o.$observe("horizontal",function(e){t.horizontal="undefined"!=typeof e})}}}])}(),function(){"use strict";function e(e,t){if(void 0===t)return null;for(var n=t.parentNode,o=1,i="function"==typeof n.setAttribute&&n.hasAttribute(e)?n:null;n&&"function"==typeof n.setAttribute&&!n.hasAttribute(e);){if(n=n.parentNode,i=n,n===document.documentElement){i=null;break}o++}return i}angular.module("ui.tree").factory("UiTreeHelper",["$document","$window","treeConfig",function(t,n,o){return{nodesData:{},setNodeAttribute:function(e,t,n){if(!e.$modelValue)return null;var o=this.nodesData[e.$modelValue.$$hashKey];o||(o={},this.nodesData[e.$modelValue.$$hashKey]=o),o[t]=n},getNodeAttribute:function(e,t){if(!e.$modelValue)return null;var n=this.nodesData[e.$modelValue.$$hashKey];return n?n[t]:null},nodrag:function(e){return"undefined"!=typeof e.attr("data-nodrag")&&"false"!==e.attr("data-nodrag")},eventObj:function(e){var t=e;return void 0!==e.targetTouches?t=e.targetTouches.item(0):void 0!==e.originalEvent&&void 0!==e.originalEvent.targetTouches&&(t=e.originalEvent.targetTouches.item(0)),t},dragInfo:function(e){return{source:e,sourceInfo:{cloneModel:e.$treeScope.cloneEnabled===!0?angular.copy(e.$modelValue):void 0,nodeScope:e,index:e.index(),nodesScope:e.$parentNodesScope},index:e.index(),siblings:e.siblings().slice(0),parent:e.$parentNodesScope,moveTo:function(e,t,n){this.parent=e,this.siblings=t.slice(0);var o=this.siblings.indexOf(this.source);o>-1&&(this.siblings.splice(o,1),this.source.index()<n&&n--),this.siblings.splice(n,0,this.source),this.index=n},parentNode:function(){return this.parent.$nodeScope},prev:function(){return this.index>0?this.siblings[this.index-1]:null},next:function(){return this.index<this.siblings.length-1?this.siblings[this.index+1]:null},isClone:function(){return this.source.$treeScope.cloneEnabled===!0},clonedNode:function(e){return angular.copy(e)},isDirty:function(){return this.source.$parentNodesScope!=this.parent||this.source.index()!=this.index},isForeign:function(){return this.source.$treeScope!==this.parent.$treeScope},eventArgs:function(e,t){return{source:this.sourceInfo,dest:{index:this.index,nodesScope:this.parent},elements:e,pos:t}},apply:function(){var e=this.source.$modelValue;this.parent.nodropEnabled||this.parent.$treeScope.nodropEnabled||this.isDirty()&&(this.isClone()&&this.isForeign()?this.parent.insertNode(this.index,this.sourceInfo.cloneModel):(this.source.remove(),this.parent.insertNode(this.index,e)))}}},height:function(e){return e.prop("scrollHeight")},width:function(e){return e.prop("scrollWidth")},offset:function(e){var o=e[0].getBoundingClientRect();return{width:e.prop("offsetWidth"),height:e.prop("offsetHeight"),top:o.top+(n.pageYOffset||t[0].body.scrollTop||t[0].documentElement.scrollTop),left:o.left+(n.pageXOffset||t[0].body.scrollLeft||t[0].documentElement.scrollLeft)}},positionStarted:function(e,t){var n={},o=e.pageX,i=e.pageY;return e.originalEvent&&e.originalEvent.touches&&e.originalEvent.touches.length>0&&(o=e.originalEvent.touches[0].pageX,i=e.originalEvent.touches[0].pageY),n.offsetX=o-this.offset(t).left,n.offsetY=i-this.offset(t).top,n.startX=n.lastX=o,n.startY=n.lastY=i,n.nowX=n.nowY=n.distX=n.distY=n.dirAx=0,n.dirX=n.dirY=n.lastDirX=n.lastDirY=n.distAxX=n.distAxY=0,n},positionMoved:function(e,t,n){var o,i=e.pageX,r=e.pageY;return e.originalEvent&&e.originalEvent.touches&&e.originalEvent.touches.length>0&&(i=e.originalEvent.touches[0].pageX,r=e.originalEvent.touches[0].pageY),t.lastX=t.nowX,t.lastY=t.nowY,t.nowX=i,t.nowY=r,t.distX=t.nowX-t.lastX,t.distY=t.nowY-t.lastY,t.lastDirX=t.dirX,t.lastDirY=t.dirY,t.dirX=0===t.distX?0:t.distX>0?1:-1,t.dirY=0===t.distY?0:t.distY>0?1:-1,o=Math.abs(t.distX)>Math.abs(t.distY)?1:0,n?(t.dirAx=o,void(t.moving=!0)):(t.dirAx!==o?(t.distAxX=0,t.distAxY=0):(t.distAxX+=Math.abs(t.distX),0!==t.dirX&&t.dirX!==t.lastDirX&&(t.distAxX=0),t.distAxY+=Math.abs(t.distY),0!==t.dirY&&t.dirY!==t.lastDirY&&(t.distAxY=0)),void(t.dirAx=o))},elementIsTreeNode:function(e){return"undefined"!=typeof e.attr("ui-tree-node")},elementIsTreeNodeHandle:function(e){return"undefined"!=typeof e.attr("ui-tree-handle")},elementIsTree:function(e){return"undefined"!=typeof e.attr("ui-tree")},elementIsTreeNodes:function(e){return"undefined"!=typeof e.attr("ui-tree-nodes")},elementIsPlaceholder:function(e){return e.hasClass(o.placeholderClass)},elementContainsTreeNodeHandler:function(e){return e[0].querySelectorAll("[ui-tree-handle]").length>=1},treeNodeHandlerContainerOfElement:function(t){return e("ui-tree-handle",t[0])}}}])}(),function(){var e=angular.module("mks-category-manager",["ui.tree"]);e.controller("CategoryController",["$scope","$http","UrlBuilder","$location",function(e,t,n,o){e.sections=[],e.currentSection=null,e.sectionModel=null,e.prevSection=null,e.categories={};var i=this;e.init=function(o){t.get(n.get("category/sections")).then(function(t){e.sections=t.data,e.sections.length&&(o?e.selectSection(i.getSection(o)):e.selectSection(e.sections[0]))})},this.getSection=function(t){for(var n=0;n<e.sections.length;n++)if(e.sections[n].id==t)return e.sections[n];return!1},this.getSectionIndex=function(t){for(var n=0;n<e.sections.length;n++)if(e.sections[n].id==t)return n;return!1},this.loadCategories=function(o){e.categories[o]||t.get(n.get("category/categories/"+o)).then(function(t){e.categories[o]=t.data})},e.selectSection=function(t){e.currentSection=t,e.prevSection=t,e.sectionModel=null,i.loadCategories(t.id)},e.addSection=function(){e.currentSection=null,e.sectionModel={active:!0}},e.editSection=function(t){e.currentSection=null,e.sectionModel=angular.copy(t)},e.saveSection=function(){return!!e.sectionModel&&void t.post(n.get("category/save-section"),e.sectionModel).then(function(t){if(!t.data.id)return!1;if(e.sectionModel.id){var n=i.getSectionIndex(t.data.id);n>=0&&(e.sections[n]=t.data,e.selectSection(e.sections[n]))}else e.sections.push(t.data),e.selectSection(t.data)})},e.cancel=function(){return!(!e.sectionModel||!e.prevSection)&&void e.selectSection(e.prevSection)},e.deleteSection=function(r,l){r.id&&confirm(l||"Delete?")&&t.post(n.get("category/delete-section"),{id:r.id}).then(function(t){e.sections.splice(i.getSectionIndex(r.id),1),o.path()=="/category/"+r.id?o.path("/category"):e.sections.length&&e.selectSection(e.sections[0])})}}]),e.controller("CategoryTreeController",["$http","UrlBuilder",function(e,t){this.category=null;this.toggle=function(e){e.toggle()},this.remove=function(n,o){if(n.$nodeScope&&confirm(o||"Delete?")){var i=n.$nodeScope.$modelValue.id;e.post(t.get("category/delete/"+i)).then(function(e){n.remove()})}},this.treeOptions={beforeDrop:function(n){var o={old:{index:n.source.index,parent:null},"new":{index:n.dest.index,parent:null}};if(n.source.nodeScope.$parentNodeScope&&(o.old.parent=n.source.nodeScope.$parentNodeScope.$modelValue.id),n.dest.nodesScope.$nodeScope&&(o["new"].parent=n.dest.nodesScope.$nodeScope.$modelValue.id),o.old.index==o["new"].index&&o.old.parent==o["new"].parent)return!1;var i=n.source.nodeScope.$modelValue.section_id,r=n.source.nodeScope.$modelValue.id;return e.post(t.get("category/move/"+i+"/"+r),o).then(function(e){return!0})}}}])}(window.angular),function(){var e=angular.module("mks-dashboard",["ngSanitize"]);e.component("dashboardNotifications",{templateUrl:["UrlBuilder",function(e){return e.get("templates/dashboard-notifications.html")}],bindings:{url:"@"},controller:["$http","UrlBuilder","$sce",function(e,t,n){function o(){var e=0;angular.forEach(r.items,function(t){t.read_at||e++}),r.unreadCount=e}function i(t,n){return!(n&&!confirm(n))&&void e.post(t).then(function(e){r.refresh()})}this.items=[],this.nextUrl=null,this.totalCount=0,this.unreadCount=0,this.currentItem=null,this.detailsHtml=null;var r=this;this.load=function(){e.get(this.nextUrl||this.url).then(function(e){e.data&&(r.items=r.items.concat(e.data.data),r.nextUrl=e.data.next_page_url,r.totalCount=e.data.total,o())})},this.details=function(i){e.get(t.get("dashboard/notification-details/"+i.id)).then(function(e){e.data&&(r.detailsHtml=n.trustAsHtml(e.data.details),i.read_at=e.data.read_at,o(),r.currentItem=i)})},this["delete"]=function(n,i){return!(i&&!confirm(i))&&void e.post(t.get("dashboard/notification-delete/"+n.id)).then(function(e){var t=r.items.indexOf(n);t>=0&&(r.items.splice(t,1),r.totalCount--,o())})},this.refresh=function(){this.items=[],this.nextUrl=null,this.load()},this.deleteRead=function(e){i(t.get("dashboard/notifications-delete"),e)},this.deleteAll=function(e){i(t.get("dashboard/notifications-delete/all"),e)},this.load()}]}),e.component("dashboardStatistics",{templateUrl:["UrlBuilder",function(e){return e.get("templates/dashboard-statistics.html")}],bindings:{url:"@"},controller:["$http","UrlBuilder",function(e,t){var n=this;this.items=[],this.load=function(){e.get(this.url).then(function(e){e.data&&(n.items=e.data)})},this.load()}]})}(window.angular),function(){var e=angular.module("mks-menu-manager",["ui.tree"]);e.controller("MenuController",["$scope","$http","UrlBuilder","$location",function(e,t,n,o){e.menu=[],e.currentMenu=null,e.menuModel=null,e.prevMenu=null,e.menuItems={};var i=this;e.init=function(o){t.get(n.get("menuman/list")).then(function(t){e.menu=t.data,e.menu.length&&(o?e.selectMenu(i.getMenu(o)):e.selectMenu(e.menu[0]))})},this.getMenu=function(t){for(var n=0;n<e.menu.length;n++)if(e.menu[n].id==t)return e.menu[n];return!1},this.getMenuIndex=function(t){for(var n=0;n<e.menu.length;n++)if(e.menu[n].id==t)return n;return!1},this.loadMenuItems=function(o){e.menuItems[o]||t.get(n.get("menuman/items/"+o)).then(function(t){e.menuItems[o]=t.data})},e.selectMenu=function(t){e.currentMenu=t,e.prevMenu=t,e.menuModel=null,i.loadMenuItems(t.id)},e.addMenu=function(){e.currentMenu=null,e.menuModel={active:!0}},e.editMenu=function(t){e.currentMenu=null,e.menuModel=angular.copy(t)},e.saveMenu=function(){return!!e.menuModel&&void t.post(n.get("menuman/save"),e.menuModel).then(function(t){if(!t.data.id)return!1;if(e.menuModel.id){var n=i.getMenuIndex(t.data.id);n>=0&&(e.menu[n]=t.data,e.selectMenu(e.menu[n]))}else e.menu.push(t.data),e.selectMenu(t.data)})},e.cancel=function(){return!(!e.menuModel||!e.prevMenu)&&void e.selectMenu(e.prevMenu)},e.deleteMenu=function(r,l){r.id&&confirm(l||"Delete?")&&t.post(n.get("menuman/delete"),{id:r.id}).then(function(t){e.menu.splice(i.getMenuIndex(r.id),1),o.path()=="/menuman/"+r.id?o.path("/menuman"):e.menu.length&&e.selectMenu(e.menu[0])})}}]),e.controller("MenuTreeController",["$http","UrlBuilder",function(e,t){this.menuItem=null;this.toggle=function(e){e.toggle()},this.remove=function(n,o){if(n.$nodeScope&&confirm(o||"Delete?")){var i=n.$nodeScope.$modelValue.id;e.post(t.get("menuman/items/delete/"+i)).then(function(e){n.remove()})}},this.treeOptions={beforeDrop:function(n){var o={old:{index:n.source.index,parent:null},"new":{index:n.dest.index,parent:null}};if(n.source.nodeScope.$parentNodeScope&&(o.old.parent=n.source.nodeScope.$parentNodeScope.$modelValue.id),n.dest.nodesScope.$nodeScope&&(o["new"].parent=n.dest.nodesScope.$nodeScope.$modelValue.id),o.old.index==o["new"].index&&o.old.parent==o["new"].parent)return!1;var i=n.source.nodeScope.$modelValue.menu_id,r=n.source.nodeScope.$modelValue.id;return e.post(t.get("menuman/items/"+i+"/move/"+r),o).then(function(e){return!0})}}}])}(window.angular),function(){var e=angular.module("mks-admin-ext",[]);e.factory("mksLinkService",["$q","$http","UrlBuilder",function(e,t,n){this.getParamsData=function(o){var i=e.defer(),r=t({method:"get",url:n.get("route/params"),params:o,timeout:i.promise}),l=r.then(function(e){return e.data},function(t){return e.reject("Something went wrong")});return l.abort=function(){i.resolve()},l["finally"](function(){l.abort=angular.noop,i=r=l=null}),l};var o=t.get(n.get("route")).then(function(e){return e.data});return this.getRoutes=function(){return o},this}]),e.directive("mksLinkSelect",["$http","mksLinkService","UrlBuilder",function(e,t,n){return{restrict:"E",scope:{model:"@model",rawEnabled:"@rawEnabled",emptyTitle:"@emptyTitle"},templateUrl:n.get("templates/link-selector.html"),link:function(e,n,o){function i(t){angular.forEach(e.items,function(n){n.id==t&&(e.routeOption=n)})}function r(){e.routeOption&&(l&&l.abort&&l.abort(),e.modal.loading=!0,(l=t.getParamsData({name:e.routeOption.id,page:e.modal.current_page||null,q:e.modal.searchQuery||null})).then(function(t){e.modal.data=t,a=e.routeOption.id,d=t.pagination?t.pagination.current_page:1,e.modal.current_page=d})["finally"](function(){e.modal.loading=!1}))}e.field={route:o.fieldRoute||"route[name]",params:o.fieldParams||"route[params]",raw:o.fieldRaw||"route[raw]"},e.items=[],e.routeOption=null,e.routes={},e.model&&e.$watch(e.model,function(t){t&&"undefined"!=typeof t.id&&(e.route=t)}),e.$watch(function(){return e.route},function(t){t&&"undefined"!=typeof t.id&&"undefined"==typeof e.routes[t.id]&&(e.routes[t.id]=t,i(t.id))}),e.route={id:o.route,title:o.title,params:o.params,raw:o.rawValue},t.getRoutes().then(function(t){e.items=t,e.route.id&&i(e.route.id)}),e.modal={id:"modal-"+(new Date).getTime(),loading:!1};var l=null,a=null,d=1;e.modal.open=function(){e.routeOption&&(e.routeOption.extended?a!=e.routeOption.id&&(e.modal.searchQuery="",r()):"undefined"!=typeof e.routes[e.routeOption.id]&&(e.modal.form=e.routes[e.routeOption.id].params),$("#"+e.modal.id).modal("show"))},e.modal.close=function(){$("#"+e.modal.id).modal("hide")},e.modal.select=function(t){if(e.modal.data.params&&e.routeOption){var n={};angular.forEach(e.modal.data.params,function(e){n[e]=t[e]}),e.routes[e.routeOption.id]={title:t.title,params:n},e.modal.close()}},e.modal.save=function(){e.modal.form&&e.routeOption&&(e.routes[e.routeOption.id]={title:e.paramsEncoded(e.modal.form),params:e.modal.form}),e.modal.close()},e.modal.prevPage=function(){"undefined"!=typeof e.modal.current_page&&e.modal.current_page>1&&e.modal.current_page--},e.modal.nextPage=function(){"undefined"!=typeof e.modal.current_page&&"undefined"!=typeof e.modal.data.pagination.last_page&&e.modal.current_page<e.modal.data.pagination.last_page&&e.modal.current_page++},e.$watch("modal.current_page",function(e,t){e&&e>0&&e!=d&&r()}),e.modal.search=function(t){1!=e.modal.current_page?e.modal.current_page=1:r()},e.paramsEncoded=function(e){return e&&"object"==typeof e?angular.toJson(e):e},e.paramsVisible=function(t){return t?t.title?t.title:e.paramsEncoded(t.params):""}}}}]),e.directive("mksModalPaginator",["$http","mksLinkService",function(e,t,n){return{restrict:"E",require:["^","@paginator"],scope:{paginator:"@paginator"},link:function(e,t,n){}}}]),e.directive("mksEditor",["AppConfig","UrlBuilder",function(e,t){return{restrict:"A",link:function(n,o,i){if("undefined"!=typeof CKEDITOR){var r={removePlugins:"forms,audio,Audio,allmedias,base64image,markdown,googledocs",extraPlugins:"wpmore",language:e.getLang("en"),filebrowserBrowseUrl:t.get("file-manager"),filebrowserImageBrowseUrl:t.get("file-manager?type=images"),filebrowserFlashBrowseUrl:t.get("file-manager?type=flash"),filebrowserWindowWidth:"85%"};if(i.mksEditor)try{angular.extend(r,n.$eval(i.mksEditor))}catch(l){}CKEDITOR.replace(o[0],r)}}}}]),e.directive("mksPageIframe",["$window",function(e){return{restrict:"A",link:function(t,n,o){var i=angular.element("#sidebar");i.length&&(n.height(i.height()),angular.element(e).on("resize.pageIframe",function(){n.height(i.height())}),t.$on("$destroy",function(){angular.element(e).off("resize.pageIframe")})),n.parent().css({"padding-left":0,"padding-right":0})}}}]),e.directive("mksSelect",[function(){return{restrict:"A",priority:-1,link:function(e,t,n){var o=t.data("langIcon");if(o){var i=function(e){if(!e.id)return e.text;var t=$('<span><img src="'+o+"/"+e.element.value.toLowerCase()+'" class="img-flag" /> '+e.text+"</span>");return t};t.data("templateResult",i),t.data("templateSelection",i)}}}}]),e.directive("stSearchSelect2",[function(){return{restrict:"A",require:"^stTable",link:function(e,t,n,o){
t.on("change",function(){o.search(this.value,n.stSearchSelect2)})}}}]),e.component("mksImagesPicker",{templateUrl:["UrlBuilder",function(e){return e.get("templates/images-picker.html")}],bindings:{url:"@",items:"=?",inputName:"@name",pickMain:"@"},controller:["$http","UrlBuilder","$element",function(e,t,n){var o=this;this.$onInit=function(){this.inputName||(this.inputName="images"),this.items||(this.items=[],this.url&&e.get(this.url).then(function(e){e.data&&(o.items=e.data)})),window.pickImageMultiple=function(e){o.safeApply(function(){angular.forEach(e,function(e){o.items.push({url:e.relativeUrl||e.url})})})}},this.add=function(){CKEDITOR.editor.prototype.popup(t.get("file-manager?type=images&multiple=1&callback=pickImageMultiple"))},this["delete"]=function(e){var t=this.items.indexOf(e);t>-1&&this.items.splice(t,1)},this.safeApply=function(e){var t=n.scope(),o=t.$$phase;"$apply"==o||"$digest"==o?e&&"function"==typeof e&&e():t.$apply(e)},this.itemsValue=function(){return angular.toJson(this.items)},this.setMain=function(e){var t=this.items.indexOf(e);if(t>-1)for(var n=0;n<this.items.length;n++)this.items[n].main=n==t}}]}),e.component("mksImageSelect",{templateUrl:["UrlBuilder",function(e){return e.get("templates/image-select.html")}],bindings:{image:"=?",inputName:"@name",pickMain:"@"},controller:["$http","UrlBuilder","$element",function(e,t,n){var o=this;this.$onInit=function(){this.inputName||(this.inputName="image"),window.pickImageSingle=function(e){o.safeApply(function(){o.image=e[0].relativeUrl||e[0].url})}},this.browse=function(){CKEDITOR.editor.prototype.popup(t.get("file-manager?type=images&callback=pickImageSingle"))},this.clear=function(){this.image=null},this.safeApply=function(e){var t=n.scope(),o=t.$$phase;"$apply"==o||"$digest"==o?e&&"function"==typeof e&&e():t.$apply(e)}}]}),e.component("mksCategorySelect",{templateUrl:["UrlBuilder",function(e){return e.get("templates/category-select.html")}],bindings:{url:"@",sectionField:"@",categoryField:"@",sectionId:"@",categoryId:"@",sectionEmpty:"@",categoryEmpty:"@"},controller:["$http","$element",function(e,t){var n=this;this.items=[],this.section=null,this.category=null,this.$onInit=function(){return!!this.url&&(this.sectionField||(this.sectionField="section"),this.categoryField||(this.categoryField="category"),void e.get(this.url).then(function(e){e.data&&(n.items=e.data,(n.sectionId||n.categoryId)&&angular.forEach(n.items,function(e){n.sectionId&&e.id==n.sectionId&&(n.section=e),n.categoryId&&e.children&&angular.forEach(e.children,function(t){t.id==n.categoryId&&(n.category=t,n.section||(n.section=e))})}))}))}}]})}(window.angular),function(){var e=angular.module("mks-admin-ext");e.controller("WidgetRoutesCtrl",["$scope","$http","UrlBuilder",function(e,t,n){e.routes=[];e.init=function(o){t.get(n.get("widget/routes"+(o?"/"+o:""))).then(function(t){e.routes=t.data,e.routes.length||e.addChoice()})},e.addChoice=function(){e.routes.push({id:null})},e.removeChoice=function(t){var n=e.routes.indexOf(t);n>=0&&e.routes.splice(n,1)}}])}(window.angular);