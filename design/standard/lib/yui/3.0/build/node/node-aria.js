/*
Copyright (c) 2009, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 3.0.0b1
build: 1163
*/
YUI.add('node-aria', function(Y) {

/**
 * Aria support for Node
 * @module node
 * @submodule node-aria
 */

Y.Node.re_aria = /^(?:role$|aria-)/;

Y.Node.prototype._addAriaAttr = function(name) {
    this.addAttr(name, {
        getter: function() {
            return Y.Node.getDOMNode(this).getAttribute(name, 2); 
        },

        setter: function(val) {
            Y.Node.getDOMNode(this).setAttribute(name, val);
            return val; 
        }
    });
};


}, '3.0.0b1' ,{requires:['node-base']});
