/*
    eZ Online Editor MCE popup : common js code used in popups
    Created on: <06-Feb-2008 00:00:00 ar>
    
    Copyright (c) 2008 eZ Systems AS
    Licensed under the GPL 2.0 License:
    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt 
*/


var eZOEPopupUtils = {
    ajax: ez.ajax( { 'charset': 'UTF-8' } ),
    ajaxLoadResponse: '',
    embedObject: {},

    settings: {
        // optional css class to add to tag when we save ( does not replace any class selectors you have)
        cssClass: '',
        // the ez xml name of the tag
        tagName: '',
        // optional a checkbox / dropdown that selects the tag type
        tagSelector: false,
        // optional custom call back funtion if tagSelector change
        tagSelectorCallBack: false,
        // optional a function to generate the html for the tag ( on create in save() )
        tagGenerator: false,
        // optional  function to edit the tag ( on edit in save() )
        tagEditor: false,
        // optional function to generate attribute
        attributeGenerator: {},
        // optional custom function to handle setting attributes on element edit and create
        tagAttributeEditor: false,
        // form id that save() is attached to
        form: '',
        // optional canel button that cancel function is attached to
        cancelButton: '',
        // content type switch for embed tags
        contentType: '',
        // internal element in editor ( false on create ), set by init()
        editorElement: false,
        // event to call on init
        onInit: false,
        // set on init if no editorElement is present and selected text is without newlines
        editorSelectedText: false
    },
    
	init: function( settings )
	{
	    // Initialize page with default values and settings
	    ez.object.extend( eZOEPopupUtils.settings, settings, true );

	    var ed = tinyMCEPopup.editor, el = tinyMCEPopup.getWindowArg('selected_node'), s = eZOEPopupUtils.settings;

        if ( !s.selectedTag ) s.selectedTag = s.tagName;

	    if ( s.form && (s.form = ez.$( s.form )) )
	        s.form.addEvent('submit', eZOEPopupUtils.save );

        if ( s.cancelButton && (s.cancelButton = ez.$( s.cancelButton )) )
            s.cancelButton.addEvent('click', eZOEPopupUtils.cancel );

	    if ( el && el.nodeName )
	        s.editorElement = el;
	    else
	    {
	        var selectedText = ed.selection.getContent( {format:'text'} );
	        if ( !/\n/.test( selectedText ) && ez.string.trim( selectedText ) !== '' )
	            s.editorSelectedText = selectedText;
	    }
	    
        if ( s.onInit && s.onInit.call )
            s.onInit.call( eZOEPopupUtils, s.editorElement, s.tagName, ed );
	    
	    if ( s.tagSelector && ( s.tagSelector = ez.$( s.tagSelector ) ) && s.tagSelector.el.value
	    && ( s.tagSelector.el.checked === undefined || s.tagSelector.el.checked === true ) )
	        s.selectedTag = s.tagSelector.el.value;
	    
	    if ( s.editorElement )
        {
            eZOEPopupUtils.initGeneralmAttributes( s.tagName + '_attributes', s.editorElement );
            eZOEPopupUtils.initCustomAttributeValue( s.selectedTag + '_customattributes', s.editorElement.getAttribute('customattributes'))
        }
	    
	    if ( s.tagSelector )
	    {
	        // toggle custom attributes based on selected custom tag
	        if ( s.tagSelectorCallBack && s.tagSelectorCallBack.call )
	        {
	            // custom function to call when tag selector change
	            // 'this' is ez.element object of selector
	            // first param is event/false and second is element of selector
	            s.tagSelectorCallBack.call( s.tagSelector, false, s.tagSelector.el  );
                s.tagSelector.addEvent('change', s.tagSelectorCallBack );
	        }
	        else
	        {
		        // by default tag selector refreshes custom attribute values
		        eZOEPopupUtils.toggleCustomAttributes.call( s.tagSelector );
		        s.tagSelector.addEvent('change', eZOEPopupUtils.toggleCustomAttributes);
		    }
        }
	},

    save: function()
	{
	    // save changes from form values to element attributes
	    var ed = tinyMCEPopup.editor, s = eZOEPopupUtils.settings, n, arr, tmp;

        if ( s.tagSelector && s.tagSelector.el.value )
        {
            if ( s.tagSelector.el.checked === undefined || s.tagSelector.el.checked === true )
                s.selectedTag = s.tagSelector.el.value;
            else if ( s.tagSelector.el.checked === false )
                s.selectedTag = s.tagName;
        }

	    if ( tinymce.isWebKit )
	        ed.getWin().focus();
	
	    var args = {
	        'customattributes': eZOEPopupUtils.getCustomAttributeValue( s.selectedTag + '_customattributes')
	    };
	
	    // set general attributes for tag
	    if (n = ez.$( s.tagName + '_attributes'))
	       ez.$$('input,select', n).forEach(function(o){
	           if ( o.hasClass('mceItemSkip') ) return;
	           var name = o.el.name;
	           if ( s.attributeGenerator && s.attributeGenerator[name] !== undefined )
	               args = s.attributeGenerator[name].call( eZOEPopupUtils, o, args, name );
	           else
	               args[name] = o.postData( true );
	       });

	    if ( s.cssClass )
	       args['class'] = s.cssClass + ( args['class'] ? ' ' + args['class'] : '');

	    ed.execCommand('mceBeginUndoLevel');
	    if ( !s.editorElement  )
	    {
		    // create new node if none is defined and if tag type is defined in ezXmlToXhtmlHash or tagGenerator is defined
		    if ( s.tagGenerator )
		    {
		        ed.execCommand('mceInsertContent', false, s.tagGenerator.call( eZOEPopupUtils, s.tagName, s.selectedTag, s.editorSelectedText ), {skip_undo : 1} );
		        s.editorElement = ed.dom.get('__mce_tmp');
		    }
            else if ( s.tagName === 'link' )
            {
                tmp = args;
                tmp['id'] = '__mce_tmp';
                ed.execCommand('mceInsertLink', false, tmp, {skip_undo : 1} );
                s.editorElement = ed.dom.get('__mce_tmp');
            }
		    else if ( eZOEPopupUtils.xmlToXhtmlHash[s.tagName] )
		    {
		        ed.execCommand('mceInsertContent', false, '<' + eZOEPopupUtils.xmlToXhtmlHash[s.tagName] + ' id="__mce_tmp">' + ( s.editorSelectedText ? s.editorSelectedText : '&nbsp;' ) + '</' + eZOEPopupUtils.xmlToXhtmlHash[s.tagName] + '>', {skip_undo : 1} );
		        s.editorElement = ed.dom.get('__mce_tmp');
		    }
		}
		else if ( s.tagEditor )
		{
		    // we already have a element, if custom tagEditor function is defined it can edit it
		    n = s.tagEditor.call( eZOEPopupUtils, s.editorElement, ed, s.selectedTag, args );
		    if ( n && n.nodeName )
		        s.editorElement = n;
		}
	
	    if ( s.editorElement )
	    {
	        if ( s.tagAttributeEditor )
	            s.tagAttributeEditor.call( eZOEPopupUtils, ed, s.editorElement, args );
	        else
	            ed.dom.setAttribs( s.editorElement, args );

	        if ( args['id'] === undefined )
	            ed.dom.setAttrib( s.editorElement, 'id', '' );
	    }
	    ed.execCommand('mceEndUndoLevel');
	
	    ed.execCommand('mceRepaint');
	    tinyMCEPopup.close();
	    return false;
	},

    safeHtml: function( value )
    {
        // removes some unwanted stuff from attribute values
        value = value.replace(/&/g, '&amp;');
        value = value.replace(/\"/g, '&quot;');
        value = value.replace(/</g, '&lt;');
        value = value.replace(/>/g, '&gt;');
        return value;
    },

    xmlToXhtmlHash: {
        'paragraph': 'P',
        'literal': 'PRE',
        'anchor': 'A',
        'link': 'A'
    },

	cancel: function()
	{
	    tinyMCEPopup.close();
	},

	removeSelectOptions: function( node )
	{
	    // removes all options in a selection
	    if ( !node || node.nodeName !== 'SELECT'  ) return;
	    while ( node.hasChildNodes() )
	    {
	        node.removeChild( node.firstChild );
	    }
	    node.disabled = true;
	},

	addSelectOptions: function( node, o )
	{
	    // ads options to a selection based on object with name / value pairs or array
	    if ( !node || node.nodeName !== 'SELECT'  ) return;
	    var opt, c = 0, i;
	    if (  o.constructor.toString().indexOf('Array') === -1 )
	    {
		    for ( key in o )
		    {
		        opt = document.createElement("option");
		        opt.value = key === '0' ? '' : key;
		        opt.innerHTML = o[key]
		        node.appendChild( opt );
		        c++;
		    }
		}
		else
		{
			for ( i = 0, c = o.length; i<c; i++ )
			{
			    opt = document.createElement("option");
	            opt.value = opt.innerHTML = o[i];
	            node.appendChild( opt );
			}
		}
	    node.disabled = c === 0;
	},

	getCustomAttributeValue: function( node )
	{
	    // creates custom attribute value from form values
	    // global objects: ez   
	    if (node = ez.$( node ))
	    {
	        return ez.$$('input,select', node).map(function( o ){
	            var name = o.el.name;
	            return name + '|' + o.postData( true );
	        }).join('attribute_separation');
	     }
	     return '';
	},

	initCustomAttributeValue: function( node, valueString )
	{
	    // sets deafult values for based on custom attribute value
	    // global objects: ez     
	    if ( valueString === null || !(node = ez.$( node )) )
	        return;
	    var arr = valueString.split('attribute_separation'), values = {}, t;
	    for(var i = 0, l = arr.length; i < l; i++)
	    {
	        t = arr[i].split('|');
	        values[t[0]] = t[1];
	    }
	    ez.$$('input,select', node).forEach(function( o ){
	        if ( o.hasClass('mceItemSkip') )
	            return;
	        var name = o.el.name;
	        if ( values[name] !== undefined )
	            o.el.value = values[name];
	    });
	},

	toggleCustomAttributes: function( node )
	{
	    if ( this.eztype && this.eztype === 'element' )
	        node = this;
	    else
	        node = ez.$( node );

	    ez.$$('table.custom_attributes').forEach(function(o){
	        if ( o.el.id === node.el.value + '_customattributes' )
	            o.show();
	        else
	            o.hide();
	    });
	},

	initGeneralmAttributes: function( parentNode, editorElement )
	{
	    // init general attributes form values from tinymce element values
	    // global objects: ez    
	    if (parentNode = ez.$( parentNode ))
	    {
	        ez.$$('input,select', parentNode).forEach(function(o){
	            if ( o.hasClass('mceItemSkip') ) return;
	            var name = o.el.name;
	            if ( name === 'class' )
	                var v = ez.string.trim( editorElement.className.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|mceVisualAid|mceNonEditable)/g, '').replace( eZOEPopupUtils.settings.cssClass, '' ) );
	            else
	                var v = editorElement.getAttribute( name );
	            if ( v !== null && v !== undefined ) o.el.value = v;
	        });
	    }
	},

	switchTagTypeIfNeeded: function ( currentNode, targetTag )
	{
	    var s = eZOEPopupUtils.settings;

	    if ( currentNode && currentNode.nodeName && targetTag !== currentNode.nodeName.toLowerCase() )
	    {
	        // changing to a different node type
	        var ed = tinyMCEPopup.editor, doc = ed.getDoc(), newNode = doc.createElement( targetTag );

	        // copy children
	        for ( var c = 0; c < currentNode.childNodes.length; c++ )
	            newNode.appendChild( currentNode.childNodes[c].cloneNode(1) );

	        // copy attributes
	        for ( var a = 0; a < currentNode.attributes.length; a++ )
	            ed.dom.setAttrib(newNode, currentNode.attributes[a].name, ed.dom.getAttrib( currentNode, currentNode.attributes[a].name ) );

	        // replace node
	        currentNode.parentNode.replaceChild( newNode, currentNode );
	        return newNode;
	    }
	    return currentNode;
	},

    selectByEmbedId: function( id )
    {
        // redirects to embed window of a specific object id
        // global objects: ez, eZOeMCE    
        if ( ez.val( id ) )
        {
            window.location = eZOeMCE['relation_url'] + '/eZObject_' + id;
        }
    },

	searchEnter: function( e, isButton )
	{
	    // post search form if enter key is pressed or isButton = true
	    if ( isButton )
	        return eZOEPopupUtils.search();
	    e = e || window.event;
	    key = e.which || e.keyCode;
        if ( key == 13)
        {
            eZOEPopupUtils.search(); // enter key
            return false;
        }
	    return true;
	},

	browse: function( nodeId, offset )
	{
	    // browse for a specific node id and a offset on the child elements
	    // global objects: eZOeMCE   
	    eZOEPopupUtils.ajax.load( eZOeMCE['extension_url'] + '/expand/' + nodeId + '/' + (offset || 0), '', eZOEPopupUtils.browseCallBack  );
	},

	search: function( offset )
	{
	    // serach for nodes with input and select form elements inside a 'search_box' container element
	    // global objects: eZOeMCE, ez
	    var postData = ez.$$('#search_box input, #search_box select').callEach('postData').join('&'), o = offset || 0;    
	    var url = eZOeMCE['extension_url'] + '/search/x/'+ o +'/10';
	    if ( ez.string.trim( ez.$('SearchText').el.value ) )
	        eZOEPopupUtils.ajax.load( url, postData, eZOEPopupUtils.searchCallBack );
	},

	browseCallBack: function( r, mode )
	{
	    // call back function for the browse() ajax call, generates the html markup with paging and path header (if defined)
	    mode = mode || 'browse';
	    ez.script( 'eZOEPopupUtils.ajaxLoadResponse=' + r.responseText );
	    var ed = tinyMCEPopup.editor, tbody = ez.$$('#' + mode + '_box_prev tbody')[0], thead = ez.$$('#' + mode + '_box_prev thead')[0], tfoot = ez.$$('#' + mode + '_box_prev tfoot')[0];
	    tbody.el.innerHTML = thead.el.innerHTML = tfoot.el.innerHTML = '';
	    if ( eZOEPopupUtils.ajaxLoadResponse )
	    {
	        var data = eZOEPopupUtils.ajaxLoadResponse, foot = '<tr><td colspan="2">';
	        if ( data['node'] )
	        {
		        if ( data['node']['path'].length )
		            thead.el.innerHTML = '<tr><td><\/td><td colspan="2">' + ez.$c( data['node']['path'] ).map( function( n ){
		               return '<a href="JavaScript:eZOEPopupUtils.' + mode + '(' + n.node_id + ');" title="' + ed.getLang('advanced.type') + ': ' + n.class_name + '">' + n.name + '<\/a>';
		            } ).join(' / ') + ' / ' + data['node']['name'] + '<\/td><\/tr>';
		        else if ( data['node']['name'] )
		            thead.el.innerHTML = '<tr><td><\/td><td colspan="2">' + data['node']['name'] + '<\/td><\/tr>';
	        }

	        if ( data['list'] )
	        {
	           // TODO: image preview if image popup
	           tbody.el.innerHTML = '<tr>' + ez.$c( data['list'] ).map( function( n ){
	               var html = '<td><input type="radio" name="selectembedobject" value="' + n.contentobject_id + '" onclick="eZOEPopupUtils.selectByEmbedId(' + n.contentobject_id + ',' + n.node_id + ',\'' + n.name + '\')" title="' + ed.getLang('advanced.select') + '" \/><\/td>';
	               if ( n.children_count )
	                   return html +'<td><a href="JavaScript:eZOEPopupUtils.' + mode + '(' + n.node_id + ');">' + n.name + '<\/a><\/td><td>' + n.class_name + '<\/td>';
	               else
	                   return html +'<td>' + n.name + '<\/td><td>' + n.class_name + '<\/td>';
	            } ).join('<\/tr><tr>') + '<\/tr>';
	        }

            var fn = mode === 'browse' ? 'browse('+ data['node']['node_id'] + ',' : mode + '(';
	        if ( data['offset'] !== 0 )
	            foot += '<a href="JavaScript:eZOEPopupUtils.' + fn + (data['offset'] - data['limit']) + ')">&lt;&lt; ' + ed.getLang('advanced.previous') + '<\/a>';

	        foot += '<\/td><td>';
	        if ( (data['offset'] + data['limit']) < data['total_count'] )
	            foot += '<a href="JavaScript:eZOEPopupUtils.' + fn + (data['offset'] + data['limit']) + ')">' + ed.getLang('advanced.next') + ' &gt;&gt;<\/a>';

	        tfoot.el.innerHTML = foot + '<\/td><\/tr>';	        
	    }
	    return false;
	},

	searchCallBack : function( r )
	{
	    // wrapper function for browseCallBack, called by ajax call in search()
	    return eZOEPopupUtils.browseCallBack( r, 'search' );
	}
};
