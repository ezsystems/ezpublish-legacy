/*
    eZ Core : tiny javascript library for ajax and stuff
    Created on: <28-Feb-2007 00:00:00 ar>
    
    Copyright (c) 2007-2008 eZ Systems AS & André Rømcke
    Licensed under the MIT License:
    http://www.opensource.org/licenses/mit-license.php

    Inspired / based on the work of:
    prototype.conio.net        simon.incutio.com    jquery.com
    moofx.mad4milk.net        dean.edwards.name    erik.eae.ne
    
*/


if ( window.ez === undefined || ez.version < 0.95 )
{

var ez = {
    version: 0.95,
    handlers: [],
    console: null,
    debug: function( type, caller, text )
    {
        if ( ez.console )
        ez.console.el.innerHTML += '<div class="debug debug_'+ type +'"><h6>'+ caller +'<\/h6><span>'+ text +'<\/span><\/div>';
    },
    string: {
        cssStyle: function( s )
        {
            // Transforms string to css style: marginRight -> margin-right
            return s.replace(/\w[A-Z]/g, function(m){
                return (m.charAt(0)+'-'+m.charAt(1).toLowerCase());
            });
        },
        htmlEntities: function ( s )
        {
           return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        },
        is: function( s )
        {
            return typeof s === 'string';
        },
        jsCase: function( s )
        {
            // Transforms string to jsCase: margin-right -> marginRight
            return s.replace(/-\D/g, function(m){
                return m.charAt(1).toUpperCase();
            });
        },
        normalize: function( s )
        {
            // Replaces multiple whitespace by one
            return s.replace(/\s+/g, ' ');
        },
        stripTags: function( s )
        {
            // Strip html tags
            return s.replace(/<\/?[^>]+>/gi, '');
        },
        trim: function( s )
        {
            // Trims leading and ending whitespace
            return s.replace(/^\s+|\s+$/g, '');
        },
        cssCompact: function( s )
        {
            return s.replace(/^\s+|\s+$/g, '').replace(/>/g, ' > ').replace(/\~(?!=)/g, ' ~ ').replace(/\+(?!=|\d)/g, ' + ').replace(/\s+/g, ' ');
        }
    },
    fn: {
        bind: function()
        {
            // Binds arguments to a function, so when you call the returned wrapper function
            // first argument is function, second is 'this' and the rest is arguments
            var args = ez.$c(arguments), __fn = args.shift(), __object = args.shift();
            return function(){ return __fn.apply(__object, args.concat( ez.$c(arguments) ))};
        },
        bindEvent: function()
        {
            // Same as above, but includes the arguments to the wrapper function first(ie events)
            var args = ez.$c(arguments), __fn = args.shift(), __object = args.shift();
            return function(){ return __fn.apply(__object, ez.$c(arguments).concat( args ))};
        },
        strip: function( fn )
        {
            // Strips anonymous wrapper functions
            var str = (fn || '').toString();
            if (str.indexOf('anonymous') === -1) return fn;
            else return str.match(/function anonymous\(\)\n\{\n(.*)\n\}/gi) ?  RegExp.$1 : str;
        }
    },
    array: {
        extend: function( arr )
        {
            // function for extending array with extensions defined in ez.array.nativExtensions and ez.array.ezExtensions
            if ( arr.eztype ) return arr;
            arr = ez.array.nativeExtend( arr );
            arr = ez.object.extend( arr, new ez.array.eZextensions );
            return arr;
        },
		filter: function( arr, fn, t )
		{
		    // javascript 1.6: filter return values of fn that evaluates to true, t optionally overrides 'this'
		    for (var i = 0, r = ez.$c(), l = arr.length; i < l; i++) if ( fn.call(t,arr[i],i,arr) ) r.push( arr[i] );
		    return r;
		},
        forEach: function( arr, fn, t )
        {
            // javascript 1.6: iterate true an array and calls fn on each iteration, t optionally overrides 'this'
            for (var i = 0, l = arr.length; i < l; i++) fn.call(t,arr[i],i,arr);
            return arr;
        },

		indexOf: function( arr, o, s )
		{
		    // javascript 1.6: finds the first index that is like object o, s is optional start index
		    for (var i=s || 0, l = arr.length; i < l; i++) if (arr[i]===o) return i;
		    return -1;
		},
        make: function( obj, s )
        {
            // Makes a array out of anything or nothing, split strings by s if present
            // and extends the array with native javascript 1.6 methods
            var r = [], el;
            if ( ez.val(obj) || obj === false )
            {
                if ( obj != '[object NodeList]' )
                {
                    if (typeof obj !== 'object' && obj.constructor != Object) obj = [obj];
                    else if ( obj && obj.tagName !== undefined ) obj = [obj];
                    for (var i = 0; ez.set(el = obj[i]); i++){
                        ( s !== undefined && typeof el === 'string' ) ? r = r.concat( el.split( s ) ) : r.push( el );
                    }
                    r = (r.length !== 0 || obj.length === 0) ? r : [obj];
                } else r = obj;
            }
            return ez.array.nativeExtend( r );
        },
        map: function( arr, fn, t )
        {
            // javascript 1.6: maps the return value of a callback function fn, t optionally overrides 'this'
            for(var i = 0, r = ez.$c(), l = arr.length; i < l; i++) r[i] = fn.call(t,arr[i],i,arr);
            return r;
        },
        nativeExtend: function( arr )
        {
            // function for extending array with native extensions defined in ez.array.nativExtensions
            arr = ez.object.extend( arr, ez.array.nativExtensions );
            return arr;
        },
        nativExtensions: {
            forEach: function(fn, t){
                return ez.array.forEach( this, fn, t );
            },
            filter: function(fn, t){
                return ez.array.filter( this, fn, t );
            },
            indexOf: function(o, s){
                return ez.array.indexOf( this, o, s );
            },
            map: function(fn, t){
                return ez.array.map( this, fn, t );
            }
        },
        eZextensions: function()
        {
            // Init function for array eZ extensions
            this.settings = {};
            return this;
        }
    },
    object: {
        extend: function( destObj, sourceObj, force, clean )
        {
                // extends destination object with methods from source object
                // set force to true to overwrite existing methods
                // object will be nulled if clean and force is true
                for ( key in sourceObj || {} )
                {
                    if (force || destObj[key] === undefined)
                        destObj[key] = (!clean) ? sourceObj[key] : null;
                }
                return destObj;
        }
    },
    cookie: {
        set: function( name, value, days, path )
        {
            // Set cookie value by name
            // Days will default to 'end of session' and path to '/'
            var date = new Date();
            date.setTime( date.getTime() + ( ( days || 0 ) * 86400000 ) );
            document.cookie = name + '=' + value + (ez.set(days) ? '; expires=' + date.toUTCString(): '') + '; path='+ (path || '/');
        },
        get: function ( name )
        {
            // Get cookie value by name, or empty string if not found
            var r = '', n = name + '=', cookArr = document.cookie.split('; '), t;
            for ( var i = 0, l = cookArr.length; i < l; i++ ){
                t = ez.string.trim( cookArr[i] );
                if ( t.indexOf( n ) === 0 ) r = t.substring( n.length, t.length );
            }
            return r;
        },
        remove: function( name )
        {
            // Blanks the cookie value and make sure it expired a longe time ago
            ez.cookie.set( name, '', -5000 );
        }
    },
    element: {
        addEvent: function( el, trigger, handler, t )
        {
            // Method for setting element event
            // Supports w3c, ie and dom0 event handling
            // Binds the element to the funtion as 'this' and
            // last argument by default, override 'this' with t
            trigger = trigger.replace(/^on/i,'');
            handler = ez.fn.bindEvent( handler, t || el, el, t || undefined );
            if ( el.addEventListener ) el.addEventListener( trigger, handler, false );
            else if ( el.attachEvent ) el.attachEvent( 'on' + trigger, handler );
            else
            {
                var c = el['on' + trigger];
                el['on' + trigger] = typeof c !== 'function' ? handler : function(){ handler(); c()};
            }
            ez.handlers.push( arguments );
        },
        clean: function( el )
        {
            // For deleting every function reference before deleting nodes to avoid possible memory leak
            if (!el || !el.attributes || el.nodeType !== 1 ) return;
            var a = el.attributes, n;
            for (var i = 0, l = a.length; i < l; i += 1)
            {
                n = a[i].name;
                if (el[n] && el[n].test && el[n].test(/^on/i)) el[n] = null;
            }
            ez.array.forEach( el.childNodes, ez.element.clean );
        },
        extend: function( el )
        {
            // Function for extending element with native extensions and ez extensions
            if ( el.eztype ) return el;
            el = ez.object.extend( el, ez.element.nativExtensions );
            return new ez.element.eZextensions( el );
        },
        getByCSS: function()
        {
            // CSS2 query function, returns a extended array of extended elements
            // Example: arr = ez.$$('div.my_class, input[type=text], img[alt~=went]');
            // does not support pseudo-class selectors like :hover and :first-child
            var args = ez.$c(arguments, ','), doc = (typeof args[args.length -1] === 'object' ? args.pop() : document), r = [], mode = '';
            if ( args.length === 1 && args[0].eztype && args[0].eztype === 'array' )
            {
                return args[0];
            }
            // Use querySelectorAll if browsers supports it and doc is a root element (fails in ie8 beta since it dosn't return a proper node list)
            if ( doc.querySelectorAll !== undefined && ( doc.parentNode === null || doc.parentNode === undefined ) )
            {
                try {
				    r = doc.querySelectorAll( args.join(',') );
				} catch(e) {
				    r = [];
				}
		    }
            else
            {            
	            args.forEach(function(arg)
	            {
	                if (typeof arg === 'string')
	                {
	                    var parent = [doc.eztype === 'element' ? doc.el : doc];
	                    ez.array.forEach( ez.string.cssCompact( arg ).split(/\s+/), function(str)
	                    {
	                        if (  str === '>' || str === '+' || str === '~' ) return mode = str;
	                        var temp = ez.$c(), tag = (str.match(/^(\w+)([.#:\[]?)/)) ? RegExp.$1.toUpperCase() : '*', id = 0, cn = 0, at = 0, pseudo = '';
	                        if (str.match(/([\#])([a-zA-Z0-9_\-]+)([.#:\[]?)/)) id = RegExp.$2;
	                        if (str.match(/([\.])([a-zA-Z0-9_\-]+)([.#:\[]?)/)) cn = RegExp.$2;
	                        if (str.match(/\[(\w+)([~\|\^\$\*]?)=?"?([^\]"]*)"?\]/)) at = [RegExp.$1 , RegExp.$2, RegExp.$3];
	                        if (str.match(/\:([a-z-]+)\(?([\w+-]+)?\)?([.#:\[]?)/)) pseudo = [RegExp.$1 ,  RegExp.$2];
	                        ez.array.forEach( parent, function( child )
	                        {
	                            var nodes = false;
	                            if ( pseudo !== '' || mode !== '' )
	                               nodes = ez.element.getChildren( child, tag, mode, pseudo )
	                            else if ( tag === '*' && cn && child.getElementsByClassName )
	                               nodes = child.getElementsByClassName( cn );
                                else if ( id && child.getElementById )
                                    nodes = [ child.getElementById( id ) ];
	                            else if ( child.getElementsByTagName )
				                    nodes = ez.array.filter( child.getElementsByTagName( tag ), function(n){ return n.nodeType === 1; });

	                            if ( !nodes ) return;

	                            ez.array.forEach( nodes, function(i)
	                            {
	                                if (id && (!i.getAttribute('id') || i.getAttribute('id') !== id)) return;
	                                if (tag !== '*' && i.nodeName !== tag ) return;
	                                if (at && !ez.element.hasAttribute( i, at[0], at[2], at[1] )) return;
	                                if (cn && (' '+i.className+' ').indexOf( ' ' + cn + ' ' ) === -1) return;
	                                temp.push( i ); 
	                            });
	                        });
	                        parent = temp;
	                        mode = '';
	                    });
	                    
	                    r = r.concat( parent );
	                }
	                else if ( arg.eztype && arg.eztype === 'array' )
	                    r = r.concat( arg );
	                else
	                    r.push( arg );
	            }, this );
            }
            r = r === undefined ? [] : ez.array.map( r, ez.element.extend );
            return ez.array.extend( r );
        },
        getById: function( a )
        {
            // Element id query function, returns a ez extended array of extended elements
            // returns only element if only one is found, and returns false if none
            if ( a.eztype ) return a;
            var r = [];
            ez.$c(arguments).forEach(function(el){
                el = typeof el === 'string' ? document.getElementById(el) :  el;
                if (el) r.push( el.eztype ? el : ez.element.extend( el ) );
            });
            return r.length > 1  ? ez.array.extend( r ) : r[0] || false;
        },
        getChildren: function( node, tag, mode, pseudo )
        {
            var list = [], filtered = [], sib = node;
            if (  mode === '+' || mode === '~' )
            {		        
		        while ( sib = sib.nextSibling )
		        {
                    if ( sib.nodeType === 1 )
                    {
                        if ( tag === '*' || sib.nodeName === tag )
                            filtered.push( sib );
                        if ( mode === '+' )
                            break;
                    }
		        }
            }
            else
            {
                filtered = ez.array.filter(  node.childNodes, function( c )
                {
                    return c.nodeType === 1;
                });
            }
            if ( pseudo && ez.element.pseudoFilters[pseudo[0]] )
            {
                filtered = ez.element.pseudoFilters[pseudo[0]]( filtered, pseudo[1] );
            }
            if ( mode ) return filtered;
            ez.array.forEach( node.childNodes, function( n )
            {
                if ( ez.array.indexOf( filtered, n ) !== -1 && ( tag === '*' || n.nodeName === tag ) )
                {
                    list.push( n );
                }
                list = list.concat( ez.element.getChildren( n, tag, mode, pseudo ) );
            });

            return list;
        },
        getScroll: function( side, el ) 
        {
            // Get element scroll, and fallback to document if el is not passed
            var r = 0, d = (el || document.documentElement || document.body), w = (el || window), left = side === 'left' || side === 'scrollLeft';
            if ( d.scrollTop ) r = left ? d.scrollLeft : d.scrollTop;
            else if ( typeof w.pageYOffset === 'number' ) r = left ? w.pageXOffset : w.pageYOffset;
            return r;
        },
        hasAttribute: function( element, attribute, value, comp )
        {
            // CSS 2 & 3 Attribute selectors
            var atr = attribute === 'class' ? element.className : ez.fn.strip( element.getAttribute( attribute ) );
            if ( atr )
            {
                var ati = atr.indexOf( value );
                if (value == '');
                else if (!comp && atr == value);
                else if (comp === '^' && ati === 0);
                else if (comp === '*' && ati !== -1);
                else if (comp === '|' && ('-'+atr+'-').indexOf('-'+ value +'-') !== -1);
                else if (comp === '~' && (' '+atr+' ').indexOf(' '+ value +' ') !== -1);
                else if (comp === '$' && ati === ( atr.length - value.length ) && ati !== -1);
                else return false;
            } else return false;
            return true;
        },
        pseudoFilters: {
            'last-child': function( arr )
            {
                return arr.length !== 0 ? [arr[ arr.length -1 ]] : [];
            },
            'first-child': function( arr )
            {
                return arr.length !== 0 ? [arr[ 0 ]] : [];
            },
            'nth-child': function( arr, arg )
            {
                if ( !arg.match(/^([+-]?\d*)?([a-z]+)?([+-]?\d*)?$/) ) return [];
                var str = RegExp.$2 || false, inta = RegExp.$1 === '-' ? -1 : parseInt(RegExp.$1);

                if ( str === 'odd' )
                    var a = 2,  b = 1;
                else if ( str === 'even' )
                    var a = 2, b = 0;
                else if ( !str && !RegExp.$3 )
                    var a = 0, b = (inta || inta === 0) ? inta : 1
                else
                    var a = (inta || inta === 0) ? inta : 1, b = parseInt(RegExp.$3) || 0;

                if (a !== 0)
                {
                    b--;
                    if ( a > 0 )
                    {
                        while (b < 1) b += a;
                        while (b >= a) b -= a;
                    }
                    return ez.array.filter( arr, function( el, i ){
                        return i % a === b;
                    });
                }
                b--;
                return ( -1 < b && b < arr.length ) ? [arr[ b ]] : [];
            }
        },
        removeEvent: function( el, trigger, handler )
        {
            // Method for removing element event if w3c or ie event model is supported
            trigger = trigger.replace(/^on/i,'');
            if ( el.removeEventListener ) el.removeEventListener( trigger, handler, false );
            else if ( el.detachEvent ) try {el.detachEvent( trigger, handler )} catch ($i){};
        },
        nativExtensions: {},
        eZextensions: function( el )
        {
            // Init function for element eZextensions
            this.step = 0;
            this.el = el;
            this.settings = { duration:20, fps:35, selectedClass:'selected', transition: function(p){return {def:p}}, target: {}, origin: {} };
            return this;
        }
    },
    activeX: function( arr, fallBack )
    {
       // Function for testing activeX objects and return the one that works or return fb (any) || null
       // Example: ieXhr = ez.activeX(['MSXML2.XMLHTTP.6.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP']);
       for (var i = 0, x, l = arr.length; i < l; i++)
       {
           try {
               x = new ActiveXObject(arr[i]);
               return x;
           } catch (ex){}
       }
       return fallBack || null;
    },
    ajax: function( o, uri, postBack )
    {
        // Init function for ez.ajax, if uri is specified the call will be done immediately
        if ( this === ez ) return new ez.ajax( o, uri, postBack );
        this.o = ez.object.extend({ 
            'accept': 'application/json,application/xml,application/xhtml+xml,text/javascript,text/xml,text/html,*/*',
            'requestedWith': 'XMLHttpRequest',
            'charset': 'iso-8859-1'
        }, o || {}, true);
        if ( uri ) this.load( uri, this.o.postBody || null, postBack );
        return this;
    },
    min: function()
    {
       // Returns the lowest number
       var min = null;
       for (var i = 0, a = arguments, l = a.length; i < l; i++)
               if (min === null || min > a[i]) min = a[i];
       return min;
    },
    max: function()
    {
       // Returns the highest number
       var max = null;
       for (var i = 0, a = arguments, l = a.length; i < l; i++)
               if (max === null || max < a[i]) max = a[i];
       return max;
    },
    num: function(value, fallBack, type)
    {
        // Checks if value is a number, if not fallBack or 0 is returned
        // type (string) [float|int] specifies if value should be parsed as int or float
        value = type === 'int' ? parseInt( value ) : parseFloat( value );
        return isNaN( value ) ? ( ez.set( fallBack ) ? fallBack : 0 ) : value;
    },
    pick: function()
    {
       // Returns the first defined argument
       for (var i = 0, a = arguments, l = a.length; i < l; i++)
               if (ez.set( a[i] )) return a[i];
       return null;
    },
    script: function( str, onComplete )
    {
        // Script loading function
        // str (string) handles both script url and script string
        // if str is url, onComplete (function) is added as onload event
        var scr = document.createElement('script');
        scr.type = 'text/javascript';
        if (str.indexOf('.js') !== -1)
        {
            scr.src = str;
            if (onComplete) ez.element.addEvent(scr, 'load', onComplete);
        } else scr.text = str;
        document.getElementsByTagName('head')[0].appendChild(scr);
    },
    set: function(o)
    {
       // Returns true if the object is defined
       return (o !== undefined);
    },
    val: function(o)
    {
       // Returns true if the value evaluates to true or is 0
       return !!(o || o === 0 );
    },
    var_dump: function( obj, hideValue )
    {
        var r = typeof obj + (hideValue === true ? '' : ':' + obj);
        for ( member in obj )
           r += ' ' + member + (hideValue === true ? '' : ':' + obj[member]);
        return r;
    },
    xpath: !!document.evaluate,
    ie56: false
};//ez


//Shortcuts:
ez.$$ = ez.element.getByCSS;
ez.$  = ez.element.getById;
ez.$c = ez.array.make;


//Properties for the ez.array.eZextensions constructor
ez.array.eZextensions.prototype = {
    eztype: 'array',
    callEach: function(){
        // Shortcut function to call functions on the array elements, takes unlimited number of arguments
        // Example: arr.callEach( 'addEvent', 'click', function(){ alert('Hi! Did you just click me?')} );
        // returns an array of the return values
        var args = ez.$c(arguments), __fn = args.shift();
        return this.map( function( el ){ return el[__fn].apply( el, args ) });
    },
    setSettings:  function( s )
    {
        // Settings function, specify settings in the s (object) parameter
        // Example: ez.$('my_el').setSettings( {duration: 200} );
        this.settings = ez.object.extend( this.settings, s || {}, true );
        return this.settings;
    }
};//ez.array.eZExtension.prototype


//Properties for the ez.element.eZextensions constructor
ez.element.eZextensions.prototype = {
    eztype: 'element',
    addClass: function( c )
    {
        // Add c (string) to element className list
        // removes the class first so it's not set twice
        c = c || '';
        this.removeClass( c );
        this.el.className += this.el.className.length > 0 ? ' ' + c : c;
        return this;
    },
    addEvent: function(trigger, handler, t)
    {
        ez.element.addEvent( this.el, trigger, handler, t || this );
        return this;
    },
    check: function( check )
    {
        // function for toggling check on checkboxes
        // set check to true or false to force value
        if ( ez.set( this.el.checked ) )
            this.el.checked = ez.set( check ) ? !!check : !this.el.checked;
        return this;
    
    },
    getPosition: function( s )
    {
        // Gets the element position for s (string) [top|left]
        // Example: ez.$('my_el').getPosition('top');
        var t = 0, l = 0, el = this.el;
        do{    t = t + el.offsetTop || 0;
            l = l + el.offsetLeft || 0;
            el = el.offsetParent;
        } while (el);
        return s === 'left' ? l : t;
    },
    getScroll: function( side )
    {
        return ez.element.getScroll( side, this.el );
    },
    getSize: function( s, offset )
    {
        // Gets the element size for s (string) [width|height]
        // offset (boolean) determines if you want offset or scroll size
        // Example: ez.$('my_el').getSize('width');
         if (s === 'width') s = (offset) ? this.el.offsetWidth : this.el.scrollWidth;
         else s = (offset) ? this.el.offsetHeight : this.el.scrollHeight;
        return s;
    },
    getStyle: function( s )
    {
       // Gets the element calculated style by string s
       // Has multiple fallbacks for various browser differences
       // Example: ez.$('my_el').getStyle('margin');
       s = s === 'float' ? 'cssFloat' : ez.string.jsCase( s );
       var el = this.el, r;
	   if ( el.currentStyle )
	       r = el.currentStyle[s];
	   else if ( window.getComputedStyle )
           r = document.defaultView.getComputedStyle( el, null ).getPropertyValue( s );
       if ( r === null )
           r = el.style[s];
       if ( !ez.val( r ) || r === 'auto' )
       {
          switch ( s )
          {
            case 'opacity':
                // IE uses filter for opacity, if it is not set it means that there is no opacity on the element
                if ( el.filters !== undefined ) r = el.filters['alpha'] ? el.filters['alpha'].opacity / 100 : 1;
                else r = 0;
                break;
            case 'display':
                r = 'none';
                break;
            case 'width':
            case 'height':
                r = this.getSize( s );
                break;
            case 'scrollLeft':
            case 'scrollTop':
                r = this.getScroll( s );
                break;
            case 'top':
            case 'left':
                r = this.getStyle('position') === 'relative' ? 0 : this.getPos( s );
          }
       }
       return r;
    },
    hasClass: function( c )
    {
        // Removes c (string) from className on this element
        return (' ' + this.el.className + ' ').indexOf(' '+ c +' ') !== -1;
    },
    remove: function()
    {
        // cleans up and removes a node and its children
        ez.element.clean( this.el );
        this.el.parentNode.removeChild( this.el );
        this.el = null;
    },
    removeClass: function( c )
    {
        // Removes c (string) from className on this element
        if (this.el.className)
            this.el.className = ez.$c(this.el.className, ' ').filter(function( cn ){ return (cn !== c) ? 1: 0; }).join(' ');
        return this;
    },
    removeEvent: function( trigger, handler )
    {
        // remove some event from the element
        ez.element.removeEvent( this.el, trigger, handler );
    },
    setSettings:  function( s )
    {
        // Settings function, specify settings in the s (object) parameter
        // Example: ez.$('my_el').setSettings( {duration: 200} );
        this.settings = ez.object.extend( this.settings, s || {}, true );
        return this.settings;
    },
    setStyles: function( styles )
    {
        // Shortcut to set multiple styles with object, will also fix case on style names
        // Example: ez.$('my_el').setStyles( {color: '#ff00ff', marginLeft: '10px', 'margin-top': '10px'} );
        var jsStyle = 0;
        for ( style in styles )
        {
            jsStyle = ez.string.jsCase( style );
            this.el.style[jsStyle] = styles[style];
        }
        return this;
    },
    postData: function( ommitName, delimiter )
    {
        // methode for generating form data in POST format
        var el = this.el, val = [], ty = el.type;
        if ( ty === undefined || !el.name ) return '';
        if ( delimiter === undefined ) delimiter = '&';
        if (ty === 'radio' || ty === 'checkbox')
            val.push( el.checked ? el.value : '' );
        else if (ty === 'select-one')
            val.push( ( el.selectedIndex != -1 ) ? el.options[el.selectedIndex].value : '' );
        else if (ty === 'select-multiple')
            ez.array.forEach( el.options, function(o){ if ( o.selected ) val.push( o.value ) });
        else if ( el.value !== undefined )
            val.push( el.value );
        return ( ommitName ) ? val.join(delimiter) : el.name + '=' + val.join(delimiter + el.name + '=');
    },
    show: function( s, t, onComplete )
    {
        // show display of element
        // arguments are for animation compatibility
        return this.toggle( s, t, onComplete, false );
    },
    hide: function( s, t, onComplete )
    {
        // hide display of element
        // arguments are for animation compatibility
        return this.toggle( s, t, onComplete, true );
    },
    toggle: function( s, t, onComplete, direction )
    {
        // toggle display of element
        // arguments are for animation compatibility
        if ( direction === undefined ) direction = this.getStyle('display') !== 'none';
        this.el.style.display = direction ? 'none' : 'block';
        if ( onComplete.call !== undefined ) onComplete(this, this.el, direction);
        return this;
    },
    isChildOfElement: function( parent )
    {
        // returns truer if this is a decendant of parent
        c = this.el.parentNode;
        do {
            if ( c === parent) return true;
            c = c.parentNode;
        } while ( c );
        return false;
    }
};//ez.element.eZExtension.prototype


// Properties for the ez.ajax constructor
ez.ajax.prototype = {
    load: function( uri, post, pB )
    {
        // Function for re calling same ajax object with different url (string) and post(string) values
        if ( !this.xhr ) this.xhr = new XMLHttpRequest();
        this.pb = pB || this.o.postBack;
        if ( this.running ) this.cancel();
        this.running = true;
        this.xhr.open( (ez.val( post ) ? 'POST' : 'GET'), uri, true);
        this.xhr.onreadystatechange = ez.fn.bind( this.onStateChange, this );
        this.onSend( post );
        this.xhr.send( ez.val( post ) ? post : null );
    },
    onSend: function( post )
    {
        if ( ez.val( post ) )
        {
            this.xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded; charset=' + this.o.charset );
            if ( this.xhr.overrideMimeType ) this.xhr.setRequestHeader( 'Connection', 'close' );
        }
        this.xhr.setRequestHeader( 'X-Requested-With', this.o.requestedWith );
        this.xhr.setRequestHeader( 'Accept', this.o.accept );
    },
    onStateChange: function()
    {
        if ( this.xhr.readyState != 4 || !this.running ) return;
        this.running = false;
        if ( this.xhr.status >= 200 && this.xhr.status < 300 ) this.done();
        else if ( this.o.onError ) this.o.onError.call( this, this.xhr.status, this.xhr.statusText );
        this.xhr.onreadystatechange = function(){};
    },
    cancel: function()
    {
        this.running = false;
        this.xhr.abort();
        this.xhr.onreadystatechange = function(){};
        this.xhr = new XMLHttpRequest();
    },
    done: function()
    {
        // Private function called when ajax call is done. Optional update element, preUpdate and onLoad callBacks.
        var r = this.xhr, o = this.o, el = (( o.update ) ? ez.$( o.update ) : 0);
        if ( el ) el.el.innerHTML = ( o.preUpdate ) ? o.preUpdate( r ) : r.responseText;
        if ( this.pb ) el ? this.pb( r, el ): this.pb( r );
    }
};//ez.ajax.prototype


// Some IE 5 / 6 specific functionality
if ( window.detachEvent && !window.opera && /MSIE [56]/.test( navigator.userAgent ) )
{
    ez.ie56 = true;
    window.attachEvent('onload',function(){
        // Adds png alpha transparency on images and inputs with 'pngfix' class
        // remember to put transparent.png in same folder as the image we are fixing!!
        ez.$$('img.pngfix','input[type=image].pngfix').forEach(function(o){
            if ( !o.el.src || !/.png$/i.test( o.el.src ) ) return;
            var el = o.el, w = el.width, h = el.height;
            //el.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + el.src + "', sizingMethod='scale')";
            el.style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + el.src + '\')'; //, sizingMethod=\'scale\')';
            el.src = (el.src).substring( 0, (el.src).match( /([^\/\\]*$)/ ).index ) + 'transparent.png';
        });
    });    
    window.attachEvent('onunload',function(){
        // Automatic cleaning of events to avoid some related memory leaks
        for (var i = 0, l = ez.handlers.length; i < l; i++) ez.handlers[i][0].detachEvent('on'+ez.handlers[i][1], ez.handlers[i][2]);
        ez.handlers = null;
    });
}

// XMLHttpRequest wrapper for IE 5 / 6
if (!window.XMLHttpRequest && window.ActiveXObject) var XMLHttpRequest = function(){
    // XMLHttpRequest wrapper for ie browsers that do not support XMLHttpRequest natively
    return ez.activeX(['MSXML2.XMLHTTP.6.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP']);
};

}//if ( window.ez === undefined )