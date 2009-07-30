/*
Copyright (c) 2009, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 3.0.0b1
build: 1163
*/
YUI.add("node-style",function(A){(function(C){var B=["getStyle","getComputedStyle","setStyle","setStyles"];C.Node.importMethod(C.DOM,B);"getComputedStyle","setStyle","setStyles";C.NodeList.importMethod(C.Node.prototype,B);})(A);},"3.0.0b1",{requires:["dom-style","node-base"]});