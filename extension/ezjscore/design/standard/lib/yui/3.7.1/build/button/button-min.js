/*
YUI 3.7.1 (build 5627)
Copyright 2012 Yahoo! Inc. All rights reserved.
Licensed under the BSD License.
http://yuilibrary.com/license/
*/
YUI.add("button",function(e,t){function s(e){s.superclass.constructor.apply(this,arguments)}function o(e){s.superclass.constructor.apply(this,arguments)}var n=e.ButtonCore.CLASS_NAMES,r=e.ButtonCore.ARIA_STATES,i=e.ButtonCore.ARIA_ROLES;e.extend(s,e.Widget,{BOUNDING_TEMPLATE:e.ButtonCore.prototype.TEMPLATE,CONTENT_TEMPLATE:null,initializer:function(e){this._host=this.get("boundingBox")},bindUI:function(){var e=this;e.after("labelChange",e._afterLabelChange),e.after("disabledChange",e._afterDisabledChange)},syncUI:function(){var e=this;e._uiSetLabel(e.get("label")),e._uiSetDisabled(e.get("disabled"))},_afterLabelChange:function(e){this._uiSetLabel(e.newVal)},_afterDisabledChange:function(e){this._uiSetDisabled(e.newVal)}},{NAME:"button",ATTRS:{label:{value:e.ButtonCore.ATTRS.label.value},disabled:{value:!1}},HTML_PARSER:{label:function(e){return this._host=e,this._getLabel()},disabled:function(e){return e.getDOMNode().disabled}},CLASS_NAMES:n}),e.mix(s.prototype,e.ButtonCore.prototype),e.extend(o,s,{trigger:"click",selectedAttrName:"",initializer:function(e){var t=this,n=t.get("type"),r=n==="checkbox"?"checked":"pressed",i=e[r]||!1;t.addAttr(r,{value:i}),t.selectedAttrName=r},destructor:function(){delete this.selectedAttrName},bindUI:function(){var e=this,t=e.get("contentBox");o.superclass.bindUI.call(e),t.on(e.trigger,e.toggle,e),e.after(e.selectedAttrName+"Change",e._afterSelectedChange)},syncUI:function(){var e=this,t=e.get("contentBox"),n=e.get("type"),r=o.ARIA_ROLES,i=n==="checkbox"?r.CHECKBOX:r.TOGGLE,s=e.selectedAttrName;o.superclass.syncUI.call(e),t.set("role",i),e._uiSetSelected(e.get(s))},_afterSelectedChange:function(e){this._uiSetSelected(e.newVal)},_uiSetSelected:function(e){var t=this,n=t.get("contentBox"),r=o.ARIA_STATES,i=t.get("type"),u=i==="checkbox"?r.CHECKED:r.PRESSED;n.toggleClass(s.CLASS_NAMES.SELECTED,e),n.set(u,e)},toggle:function(){var e=this;e._set(e.selectedAttrName,!e.get(e.selectedAttrName))}},{NAME:"toggleButton",ATTRS:{type:{value:"toggle",writeOnce:"initOnly"}},HTML_PARSER:{checked:function(e){return e.hasClass(n.SELECTED)},pressed:function(e){return e.hasClass(n.SELECTED)}},ARIA_STATES:r,ARIA_ROLES:i,CLASS_NAMES:n}),e.Button=s,e.ToggleButton=o},"3.7.1",{requires:["button-core","cssbutton","widget"]});
