/*
Copyright (c) 2009, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 3.0.0b1
build: 1163
*/
YUI.add("dd-plugin",function(B){var A=function(C){C.node=C.host;A.superclass.constructor.apply(this,arguments);};A.NAME="dd-plugin";A.NS="dd";B.extend(A,B.DD.Drag);B.namespace("Plugin");B.Plugin.Drag=A;},"3.0.0b1",{skinnable:false,requires:["dd-drag"],optional:["dd-constrain","dd-proxy"]});