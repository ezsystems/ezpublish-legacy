/*
Copyright (c) 2009, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 3.0.0b1
build: 1163
*/
YUI.add("datasource-function",function(B){var A=B.Lang,C=function(){C.superclass.constructor.apply(this,arguments);};B.mix(C,{NAME:"dataSourceFunction",ATTRS:{source:{validator:A.isFunction}}});B.extend(C,B.DataSource.Local,{_defRequestFn:function(F){var E=this.get("source"),D;if(E){D=E(F.request,this,F);this.fire("data",B.mix({data:D},F));}else{F.error=new Error("Function data failure");this.fire("error",F);}return F.tId;}});B.DataSource.Function=C;},"3.0.0b1",{requires:["datasource-local"]});