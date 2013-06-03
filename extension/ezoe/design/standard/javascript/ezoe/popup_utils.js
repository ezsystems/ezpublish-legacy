/*
    eZ Online Editor MCE popup : common js code used in popups
    Created on: <06-Feb-2008 00:00:00 ar>
    
    Copyright (c) 1999-2013 eZ Systems AS
    Licensed under the GPL 2.0 License:
    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt 
*/


var eZOEPopupUtils = {
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
        // optional a function to do cleanup after tag has been created ( after create in save() )
        onTagGenerated: false,
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
        // event to call after init
        onInitDone : false,
        // events to call after init
        onInitDoneArray : [],
        // custom attribute to style map to be able to preview style changes
        customAttributeStyleMap: false,
        // set on init if no editorElement is present and selected text is without newlines
        editorSelectedText: false,
        // Same as above but with markup
        editorSelectedHtml: false,
        // the selected node in the editor, set on init
        editorSelectedNode: false,
        // generates class name for tr elements in browse / search / bookmark list
        browseClassGenerator: function(){ return ''; },
        // generates browse link for a specific mode
        browseLinkGenerator: false,
        // custom init function pr custom attribute
        customAttributeInitHandler: [],
        // custom save function pr custom attribute
        customAttributeSaveHandler: [],
        // Title text to set on tilte tag and h2#tag-edit-title tag in tag edit / create dialogs
        tagEditTitleText: '',
        // the default image alias to use while browsing
        browseImageAlias: 'small'
    },

    /**
     * Initialize page with values from current editor element or if not defined; default values and settings
     *
     * @param {Object} settings Hash with settings, is saved to eZOEPopupUtils.settings for the rest of the execution.
     */
    init: function( settings )
    {
        eZOEPopupUtils.settings = jQuery.extend( false, eZOEPopupUtils.settings, settings );

        var ed = tinyMCEPopup.editor, el = tinyMCEPopup.getWindowArg('selected_node'), s = eZOEPopupUtils.settings;

        if ( !s.selectedTag ) s.selectedTag = s.tagName;

        if ( s.form && (s.form = jQuery( '#' + s.form )) )
            s.form.submit( eZOEPopupUtils.save );

        if ( s.cancelButton && (s.cancelButton = jQuery( '#' + s.cancelButton )) )
            s.cancelButton.click( eZOEPopupUtils.cancel );

        if ( el && el.nodeName )
        {
            s.editorElement = el;
            if ( s.tagEditTitleText )
            {
                document.title = s.tagEditTitleText;
                jQuery( '#tag-edit-title').html( s.tagEditTitleText );
                if ( window.parent && window.parent.ez )
                {
                    // set title on inline popup if inlinepopup tinyMCE plugin is used
                    var tinyInlinePopupsTitle = window.parent.jQuery('div.clearlooks2');
                    if ( tinyInlinePopupsTitle && tinyInlinePopupsTitle.size() ) 
                        window.parent.document.getElementById( tinyInlinePopupsTitle[0].id + '_title').innerHTML = s.tagEditTitleText;
                }
            }
        }
        else
        {
            var selectedHtml = ed.selection.getContent( {format:'text'} );
            if ( !/\n/.test( selectedHtml ) && jQuery.trim( selectedHtml ) !== '' )
                s.editorSelectedText = selectedHtml;

            selectedHtml = ed.selection.getContent( {format:'html'} );
            if ( jQuery.trim( selectedHtml ) !== '' )
                s.editorSelectedHtml = selectedHtml;
        }
        s.editorSelectedNode = ed.selection.getNode();
        
        if ( s.onInit && s.onInit.call )
            s.onInit.call( eZOEPopupUtils, s.editorElement, s.tagName, ed );

        if ( s.tagSelector && ( s.tagSelector = jQuery( '#' + s.tagSelector ) ) && s.tagSelector.size() && s.tagSelector[0].value
        && ( s.tagSelector[0].checked === undefined || s.tagSelector[0].checked === true ) )
            s.selectedTag = s.tagSelector[0].value;

        if ( s.editorElement )
        {
            eZOEPopupUtils.initGeneralmAttributes( s.tagName + '_attributes', s.editorElement );
            eZOEPopupUtils.initCustomAttributeValue( s.selectedTag + '_customattributes', s.editorElement.getAttribute('customattributes'))
        }
        
        if ( s.tagSelector && s.tagSelector.size() )
        {
            // toggle custom attributes based on selected custom tag
            if ( s.tagSelectorCallBack && s.tagSelectorCallBack.call )
            {
                // custom function to call when tag selector change
                // 'this' is jQuery object of selector
                // first param is event/false and second is element of selector
                s.tagSelectorCallBack.call( s.tagSelector, false, s.tagSelector[0]  );
                s.tagSelector.change( s.tagSelectorCallBack );
            }
            else
            {
                // by default tag selector refreshes custom attribute values
                eZOEPopupUtils.toggleCustomAttributes.call( s.tagSelector );
                s.tagSelector.change( eZOEPopupUtils.toggleCustomAttributes );
            }
        }
        if ( s.onInitDone && s.onInitDone.call )
            s.onInitDone.call( eZOEPopupUtils, s.editorElement, s.tagName, ed );
        if ( s.onInitDoneArray && s.onInitDoneArray.length )
            jQuery.each( s.onInitDoneArray, function( i, fn ){
                if ( fn && fn.call )
                    fn.call( eZOEPopupUtils, s.editorElement, s.tagName, ed );
            } );
    },

    /**
     * Save changes from form values to editor element attributes but first:
     * - validate values according to class names and stop if not valid
     * - create editor element if it does not exist either using callbacks or defaults
     * - or call optional edit callback to edit/fixup existing element
     * - Set attributes from form values using callback if set or default TinyMCE handler
     */
    save: function()
    {
        var ed = tinyMCEPopup.editor, s = eZOEPopupUtils.settings, n, arr, tmp, f = document.forms[0];

        if ( s.tagSelector && s.tagSelector.size() && s.tagSelector[0].value )
        {
            if ( s.tagSelector[0].checked === undefined || s.tagSelector[0].checked === true )
                s.selectedTag = s.tagSelector[0].value;
            else if ( s.tagSelector[0].checked === false )
                s.selectedTag = s.tagName;
        }

        // validate the general attributes
        if ( (errorArray = AutoValidator.validate( jQuery( '#' + s.tagName + '_attributes' )[0] ) ) && errorArray.length )
        {
            tinyMCEPopup.alert(tinyMCEPopup.getLang('invalid_data') + "\n" + errorArray.join(", ") );
            return false;
        }

        // validate the custom attributes
        if ( (errorArray = AutoValidator.validate( jQuery( '#' + s.selectedTag + '_customattributes' )[0] ) ) && errorArray.length )
        {
            tinyMCEPopup.alert(tinyMCEPopup.getLang('invalid_data') + "\n" + errorArray.join(", ") );
            return false;
        }

        if ( tinymce.isWebKit )
            ed.getWin().focus();
    
        var args = jQuery.extend(
            false,
            eZOEPopupUtils.getCustomAttributeArgs( s.selectedTag + '_customattributes'),
            eZOEPopupUtils.getGeneralAttributeArgs( s.tagName + '_attributes')
        );

        if ( s.cssClass )
           args['class'] = s.cssClass + ( args['class'] ? ' ' + args['class'] : '');

        ed.execCommand('mceBeginUndoLevel');
        if ( !s.editorElement  )
        {
            // create new node if none is defined and if tag type is defined in ezXmlToXhtmlHash or tagGenerator is defined
            if ( s.tagGenerator )
            {
                s.editorElement = eZOEPopupUtils.insertHTMLCleanly( ed, s.tagGenerator.call( eZOEPopupUtils, s.tagName, s.selectedTag, s.editorSelectedHtml ), '__mce_tmp' );
            }
            else if ( s.tagCreator )
            {
                s.editorElement = s.tagCreator.call( eZOEPopupUtils, ed, s.tagName, s.selectedTag, s.editorSelectedHtml );
            }
            else if ( s.tagName === 'link' )
            {
                var tempid = args['id'];
                args['id'] = '__mce_tmp';
                ed.execCommand('mceInsertLink', false, args, {skip_undo : 1} );
                s.editorElement = ed.dom.get('__mce_tmp');
                // fixup if we are inside embed tag
                if ( tmp = eZOEPopupUtils.getParentByTag( s.editorElement, 'div,span', 'ezoeItemNonEditable' ) )
                {
                    var span = document.createElement("span");
                    span.innerHTML = s.editorElement.innerHTML;
                    s.editorElement.parentNode.replaceChild(span, s.editorElement);
                    s.editorElement.innerHTML = '';
                    tmp.parentNode.insertBefore(s.editorElement, tmp);
                    s.editorElement.appendChild( tmp );
                }
                args['id'] = tempid;
            }
            else if ( eZOEPopupUtils.xmlToXhtmlHash[s.tagName] )
            {
                s.editorElement = eZOEPopupUtils.insertTagCleanly( ed, eZOEPopupUtils.xmlToXhtmlHash[s.tagName], '&nbsp;' );
            }
            if ( s.onTagGenerated )
            {
                n = s.onTagGenerated.call( eZOEPopupUtils, s.editorElement, ed, args );
                if ( n && n.nodeName )
                    s.editorElement = n;
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
            {
                n = s.tagAttributeEditor.call( eZOEPopupUtils, ed, s.editorElement, args );
                if ( n && n.nodeName )
                    s.editorElement = n;
            }
            else
                ed.dom.setAttribs( s.editorElement, args );

            if ( args['id'] === undefined )
                ed.dom.setAttrib( s.editorElement, 'id', '' );

            if ( 'TABLE'.indexOf( s.editorElement.tagName ) === 0 )
                ed.selection.select( jQuery( s.editorElement ).find( "tr:first-child > *:first-child" ).get(0), true );
            else if ( 'DIV'.indexOf( s.editorElement.tagName ) === 0 )
                ed.selection.select( s.editorElement );
            else
                ed.selection.select( s.editorElement, true );
            ed.nodeChanged();
        }
        ed.execCommand('mceEndUndoLevel');
    
        ed.execCommand('mceRepaint');
        tinyMCEPopup.close();
        return false;
    },

    /**
     * Insert raw html and tries to cleanup any issues that might happen (related to paragraphs and block tags)
     * makes sure block nodes do not break the html structure they are inserted into
     *
     * @param ed TinyMCE editor instance
     * @param {String} html
     * @param {String} id
     * @return HtmlElement
     */
    insertHTMLCleanly: function( ed, html, id )
    {
        var paragraphCleanup = false, newElement;
        if ( html.indexOf( '<div' ) === 0 || html.indexOf( '<pre' ) === 0 )
        {
            paragraphCleanup = true;
        }

        ed.execCommand('mceInsertRawHTML', false, html, {skip_undo : 1} );

        newElement = ed.dom.get( id );
        if ( paragraphCleanup ) this.paragraphCleanup( ed, newElement );
        return newElement;
    },

    /**
     * Only for use for block tags ( appends or prepends tag relative to current tag )
     *
     * @param ed TinyMCE editor instance
     * @param {String} tag Tag Name
     * @param {String} content Inner content of tag, can be html, but only tested with plain text
     * @param {Array} args Optional parameter that is passed as second parameter to ed.dom.setAttribs() if set.
     * @return HtmlElement
     */
    insertTagCleanly: function( ed, tag, content, args )
    {
        var edCurrentNode = eZOEPopupUtils.settings.editorSelectedNode ? eZOEPopupUtils.settings.editorSelectedNode : ed.selection.getNode(),
            newElement = edCurrentNode.ownerDocument.createElement( tag );
        if ( tag !== 'img' ) newElement.innerHTML = content;

        if ( edCurrentNode.nodeName === 'TD' )
            edCurrentNode.appendChild( newElement );
        else if ( edCurrentNode.nextSibling )
            edCurrentNode.parentNode.insertBefore( newElement, edCurrentNode.nextSibling );
        else if ( edCurrentNode.nodeName === 'BODY' )// IE when editor is empty
            edCurrentNode.appendChild( newElement );
        else
            edCurrentNode.parentNode.appendChild( newElement );

        if ( (tag === 'div' || tag === 'pre') && edCurrentNode && edCurrentNode.nodeName.toLowerCase() === 'p' )
        {
            this.paragraphCleanup( ed, newElement );
        }

        if ( args ) ed.dom.setAttribs( newElement, args );

        return newElement;
    },

    /**
     * Cleanup broken paragraphs after inserting block tags into paragraphs
     *
     * @param ed TinyMCE editor instance
     * @param {HtmlElement} el
     */
    paragraphCleanup: function( ed, el )
    {
        var emptyContent = [ '', '<br>', '<BR>', '&nbsp;', ' ', " " ];
        if ( el.previousSibling
          && el.previousSibling.nodeName.toLowerCase() === 'p'
          && ( !el.previousSibling.hasChildNodes() || jQuery.inArray( el.previousSibling.innerHTML, emptyContent ) !== -1 ))
        {
            el.parentNode.removeChild( el.previousSibling );
        }
        if ( el.nextSibling
          && el.nextSibling.nodeName.toLowerCase() === 'p'
          && ( !el.nextSibling.hasChildNodes() || jQuery.inArray( el.nextSibling.innerHTML, emptyContent ) !== -1 ))
       {
            el.parentNode.removeChild( el.nextSibling );
       }
    },

    /**
     * Removes some unwanted stuff from attribute values
     *
     * @param {String} value
     * @return {String}
     */
    safeHtml: function( value )
    {
        value = value.replace(/&/g, '&amp;');
        value = value.replace(/\"/g, '&quot;');
        value = value.replace(/</g, '&lt;');
        value = value.replace(/>/g, '&gt;');
        return value;
    },

    xmlToXhtmlHash: {
        'paragraph': 'p',
        'literal': 'pre',
        'anchor': 'a',
        'link': 'a'
    },

    cancel: function()
    {
        tinyMCEPopup.close();
    },

    /**
     * Removes all children of a node safly (especially needed to clear select options and table data)
     * Also disables tag if it was an select
     *
     * @param {HtmlElement} node
     */
    removeChildren: function( node )
    {
        if ( !node  ) return;
        while ( node.hasChildNodes() )
        {
            node.removeChild( node.firstChild );
        }
        if ( node.nodeName === 'SELECT' ) node.disabled = true;
    },

    /**
     * Adds options to a selection based on object with name / value pairs or array
     * and disables select tag if no options where added.
     *
     * @param {HtmlElement} node
     * @param {Object|Array} o
     */
    addSelectOptions: function( node, o )
    {
        if ( !node || node.nodeName !== 'SELECT'  ) return;
        var opt, c = 0, i;
        if ( o.constructor.toString().indexOf('Array') === -1 )
        {
            for ( key in o )
            {
                opt = document.createElement("option");
                opt.value = key === '0' || key === '-0-' ? '' : key;
                opt.innerHTML = o[key]
                node.appendChild( opt );
                c++;
            }
        }
        else
        {
            for ( i = 0, c = o.length; i < c; i++ )
            {
                opt = document.createElement("option");
                opt.value = opt.innerHTML = o[i];
                node.appendChild( opt );
            }
        }
        node.disabled = c === 0;
    },

    /**
     * Get custom attribute value from form values and map them to style value as well
     *
     * @param {HtmlElement} node
     * @return {Object} Hash of attributes and their values for use on editor elements
     */
    getCustomAttributeArgs: function( node )
    {
        var args = {
            'customattributes': '',
            'style': ''
        }, s = eZOEPopupUtils.settings, handler = s.customAttributeSaveHandler;
        var customArr = [];
        jQuery( '#' + node + ' input,#' + node + ' select' ).each(function( i, el )
        {
            var o = jQuery( el ), name = o.attr("name"), value, style;
            if ( o.hasClass('mceItemSkip') || !name ) return;
            if ( o.attr("type") === 'checkbox' && !o.attr("checked") ) return;

            // see if there is a save hander that needs to do some work on the value
            if ( handler[el.id] !== undefined && handler[el.id].call !== undefined )
                value = handler[el.id].call( o, el, o.val() );
            else
                value = o.val()

            // add to styles if custom attibute is defined in customAttributeStyleMap
            if ( value !== '' && s.customAttributeStyleMap && s.customAttributeStyleMap[name] !== undefined  )
            {
                // filtered because the browser (ie,ff&opera) convert the tag to font tag in certain circumstances
                style = s.customAttributeStyleMap[name];
                if ( /[margin|border|padding|width|height]/.test( style ) )
                    args['style'] += style + ': ' + value + '; ';
            }
            customArr.push( name + '|' + value );
        });
        args['customattributes'] = customArr.join('attribute_separation');
        return args;
    },

    /**
     * Get general attributes for tag from form values
     *
     * @param {HtmlElement} node
     * @return {Object} Hash of attributes and their values for use on editor elements
     */
    getGeneralAttributeArgs: function( node )
    {
        var args = {}, handler = eZOEPopupUtils.settings.customAttributeSaveHandler;
        jQuery( '#' + node + ' input,#' + node + ' select' ).each(function( i, el )
        {
            var o = jQuery( el ), name = o.attr("name");
            if ( o.hasClass('mceItemSkip') || !name ) return;
            if ( o.attr("type") === 'checkbox' && !o.attr("checked") ) return;
            // see if there is a save hander that needs to do some work on the value
            if ( handler[el.id] !== undefined && handler[el.id].call !== undefined )
                args[name] = handler[el.id].call( o, el, o.val() );
            else
                args[name] = o.val()
       });
       return args;
    },

    /**
     * Get parent tag by tag name with optional class name and type check
     *
     * @param {HtmlElement} n
     * @param {String} tag
     * @param {String} className
     * @param {String} type
     * @param {Boolean} checkElement Checks n as well if true
     * @return {HtmlElement|False}
     */
    getParentByTag: function( n, tag, className, type, checkElement )
    {
        if ( className ) className = ' ' + className + ' ';
        tag = ',' + tag.toUpperCase() + ',';
        while ( n !== undefined && n.nodeName !== undefined && n.nodeName !== 'BODY' )
        {
            if ( checkElement && tag.indexOf( ',' + n.nodeName + ',' ) !== -1
            && ( !className || (' ' + n.className + ' ').indexOf( className ) !== -1 ) 
            && ( !type || n.getAttribute('type') === type ) )
            {
                return n;
            }
            n = n.parentNode;
            checkElement = true;
        }
        return false;
    },

    toggleCustomAttributes: function( e )
    {
        if ( this.each !== undefined )
            node = this;
        else
            node = jQuery( this );

        jQuery('table.custom_attributes').each(function( i, el ){
            el = jQuery( el );
            if ( el.attr('id') === node[0].value + '_customattributes' )
                el.show();
            else
                el.hide();
        });
    },

    /**
     * Sets deafult values for based on custom attribute value
     * global objects: ez
     *
     * @param string node Element id of parent node for custom attribute form
     * @param string valueString The raw customattributes string from attribute
     */
    initCustomAttributeValue: function( node, valueString )
    {
        if ( valueString === null || !document.getElementById( node ) )
            return;
        var arr = valueString.split('attribute_separation'), values = {}, t, handler = eZOEPopupUtils.settings.customAttributeInitHandler;
        for(var i = 0, l = arr.length; i < l; i++)
        {
            t = arr[i].split('|');
            var key = t.shift();
            values[key] = t.join('|');
        }
        jQuery( '#' + node + ' input,#' + node + ' select' ).each(function( i, el )
        {
            var o = jQuery( el ), name = el.name;
            if ( o.hasClass('mceItemSkip') || !name )
                return;
            if ( values[name] !== undefined )
            {
                if ( handler[el.id] !== undefined && handler[el.id].call !== undefined )
                    handler[el.id].call( o, el, values[name] );
                else if ( el.type === 'checkbox' )
                   el.checked = values[name] == el.value;
                else if ( el.type === 'select-one' )
                {
                    // Make sure selecion has value before we set it (#014986)
                    for( var i = 0, l = el.options.length; i < l; i++ )
                    {
                        if ( el.options[i].value == values[name] ) el.value = values[name];
                    }
                }
                else
                   el.value = values[name];

                try {
                    el.onchange();
                } catch (ex) {
                    // Try fire event, ignore errors
                }
            }
        });
    },

    initGeneralmAttributes: function( node, editorElement )
    {
        // init general attributes form values from tinymce element values
        var handler = eZOEPopupUtils.settings.customAttributeInitHandler, cssReplace = function( s ){
            s = s.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|ezoeItem\w+|mceVisualAid)/g, '');
            if ( !eZOEPopupUtils.settings.cssClass )
                return s;
            jQuery.each(eZOEPopupUtils.settings.cssClass.split(' '), function(index, value){
                s = s.replace( value, '' );
            });
            return s;
        };
        jQuery( '#' + node + ' input,#' + node + ' select' ).each(function( i, el )
        {
            var o = jQuery( el ), name = el.name;
            if ( o.hasClass('mceItemSkip') ) return;
            if ( name === 'class' )
                var v = jQuery.trim( cssReplace( editorElement.className ) );
            else 
                var v = tinyMCEPopup.editor.dom.getAttrib( editorElement, name );//editorElement.getAttribute( name );
            if ( v !== false && v !== null && v !== undefined )
            {
                if ( handler[el.id] !== undefined && handler[el.id].call !== undefined )
                    handler[el.id].call( o, el, v );
                else if ( el.type === 'checkbox' )
                    el.checked = v == el.value;
                else if ( el.type === 'select-one' )
                {
                    // Make sure selection has value before we set it (#014986)
                    for( var i = 0, l = el.options.length; i < l; i++ )
                    {
                        if ( el.options[i].value == v ) el.value = v;
                    }
                }
                else
                    el.value = v;

                try {
                    el.onchange();
                } catch (ex) {
                    // Try fire event, ignore errors
                }
            }
        });
    },

    switchTagTypeIfNeeded: function ( currentNode, targetTag )
    {
        if ( currentNode && currentNode.nodeName && targetTag !== currentNode.nodeName.toLowerCase() )
        {
            // changing to a different node type
            var ed = tinyMCEPopup.editor, doc = ed.getDoc(), newNode = doc.createElement( targetTag );

            // copy children
            if ( newNode.nodeName !== 'IMG' )
            {
               for ( var c = 0; c < currentNode.childNodes.length; c++ )
                   newNode.appendChild( currentNode.childNodes[c].cloneNode(1) );
            }

            // copy attributes
            for ( var a = 0; a < currentNode.attributes.length; a++ )
                ed.dom.setAttrib(newNode, currentNode.attributes[a].name, ed.dom.getAttrib( currentNode, currentNode.attributes[a].name ) );

             if ( currentNode.parentNode.nodeName === 'BODY'
               && ( newNode.nodeName === 'SPAN' || newNode.nodeName === 'IMG' )
                )
             {
                 // replace node but wrap inside a paragraph first
                 var p = doc.createElement('p');
                 p.appendChild( newNode );
                 currentNode.parentNode.replaceChild( p, currentNode );
             }
             else
             {
                // replace node
                currentNode.parentNode.replaceChild( newNode, currentNode ); 
             }

            return newNode;
        }
        return currentNode;
    },

    selectByEmbedId: function( id, node_id, name, useNode )
    {
        // redirects to embed window of a specific object id
        // global objects: ez 
        if ( id !== undefined )
        {
            var s = tinyMCEPopup.editor.settings, type = useNode === true && node_id > 0 ? 'eZNode_' + node_id : 'eZObject_' + id ;
            window.location = s.ez_extension_url + '/relations/' + s.ez_contentobject_id + '/' + s.ez_contentobject_version + '/auto/' + type;
        }
    },

    BIND: function()
    {
        // Binds arguments to a function, so when you call the returned wrapper function,
        // arguments are intact and arguments passed to the wrapper function is appended.
        // first argument is function, second is 'this' and the rest is arguments
        var __args = Array.prototype.slice.call( arguments ), __fn = __args.shift(), __obj = __args.shift();
        return function(){return __fn.apply( __obj, __args.concat( Array.prototype.slice.call( arguments ) ) )};
    },

    searchEnter: function( e, isButton )
    {
        // post search form if enter key is pressed or isButton = true
        if ( isButton )
        {
            eZOEPopupUtils.search();
            return false;
        }
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
        var postData = eZOEPopupUtils.jqSafeSerilizer('browse_box'), o = offset ? offset : 0;
        jQuery.ez('ezoe::browse::' + nodeId + '::' + o, postData, function( data ){ eZOEPopupUtils.browseCallBack( data, 'browse' ) } );
        jQuery('#browse_progress' ).show();
    },

    search: function( offset )
    {
        // serach for nodes with input and select form elements inside a 'search_box' container element
        if ( jQuery.trim( jQuery('#SearchText').val() ) )
        {
            var postData = eZOEPopupUtils.jqSafeSerilizer('search_box'), o = offset ? offset : 0;
            jQuery.ez('ezjsc::search::x::' + o, postData, eZOEPopupUtils.searchCallBack );
            jQuery('#search_progress' ).show();
        }
    },

    jqSafeSerilizer: function( id )
    {
        // jQuery encodes form names incl [] if you pass an object / array to it, avoid by using string
        var postData = '', val;
        jQuery.each( jQuery('#' + id + ' input, #' + id + ' select').serializeArray(), function(i, o){
            if ( o.value )
                postData += ( postData ? '&' : '') + o.name + '=' + o.value;
        });
        return postData;
    },

    browseCallBack: function( data, mode, emptyCallBack )
    {
        // call back function for the browse() ajax call, generates the html markup with paging and path header (if defined)
        mode = mode ? mode : 'browse';
        jQuery('#' + mode + '_progress' ).hide();
        var ed = tinyMCEPopup.editor, tbody = jQuery('#' + mode + '_box_prev tbody')[0], thead = jQuery('#' + mode + '_box_prev thead')[0], tfoot = jQuery('#' + mode + '_box_prev tfoot')[0], tr, td, tag, hasImage, emptyList = true;
        eZOEPopupUtils.removeChildren( tbody );
        eZOEPopupUtils.removeChildren( thead );
        eZOEPopupUtils.removeChildren( tfoot );
        if ( data && data.content !== ''  )
        {
            var fn = mode + ( mode === 'browse' ? '('+ data.content['node']['node_id'] + ',' : '(' );
            var classGenerator = eZOEPopupUtils.settings.browseClassGenerator, linkGenerator = eZOEPopupUtils.settings.browseLinkGenerator;
            if ( data.content['node'] && data.content['node']['name'] )
            {
                tr = document.createElement("tr"), td = document.createElement("td");
                tr.className = 'browse-path-list';
                td.className = 'thight';
                tr.appendChild( td );
                td = document.createElement("td")
                td.setAttribute('colspan', '3');
                if ( data.content['node']['path'] !== false && data.content['node']['node_id'] != 1 )
                {
                    // Prepend root node so you can browse to the root of the installation
                    data.content['node']['path'].splice(0,0,{'node_id':1, 'name': ed.getLang('ez.root_node_name'), 'class_name': 'Folder'});
                    jQuery.each( data.content['node']['path'], function( i, n )
                    {
                        tag = document.createElement("a");
                        tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.' + mode + '(' + n.node_id + ');');
                        tag.setAttribute('title', ed.getLang('advanced.type') + ': ' + n.class_name );
                        tag.innerHTML = n.name;
                        td.appendChild( tag );
                        tag = document.createElement("span");
                        tag.innerHTML = ' / ';
                        td.appendChild( tag );
                    });
                }

                tag = document.createElement("span");
                tag.innerHTML = data.content['node']['name'];
                td.appendChild( tag );

                tr.appendChild( td );
                thead.appendChild( tr );
            }

            if ( data.content['list'] )
            {
               jQuery.each( data.content['list'], function( i, n )
               {
                   tr = document.createElement("tr"), td = document.createElement("td"), tag = document.createElement("input"), isImage = false;
                   tag.setAttribute('type', 'radio');
                   tag.setAttribute('name', 'selectembedobject');
                   tag.className = 'input_noborder';
                   tag.setAttribute('value', n.contentobject_id);
                   tag.setAttribute('title', ed.getLang('advanced.select') );
                   tag.onclick = eZOEPopupUtils.BIND( eZOEPopupUtils.selectByEmbedId, eZOEPopupUtils, n.contentobject_id, n.node_id, n.name );
                   td.appendChild( tag );
                   td.className = 'thight';
                   tr.appendChild( td );

                   td = document.createElement("td");
                   if ( linkGenerator.call !== undefined )
                   {
                       tag = linkGenerator.call( this, n, mode, ed );
                   }
                   else if ( n.children_count )
                   {
                       tag = document.createElement("a");
                       tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.' + mode + '(' + n.node_id + ');');
                       tag.setAttribute('title', ed.getLang('browse') + ': ' + n.url_alias );
                   }
                   else
                   {
                       tag = document.createElement("span");
                       tag.setAttribute('title', n.url_alias );
                   }
                   tag.innerHTML = n.name;
                   td.appendChild( tag );
                   tr.appendChild( td );

                   td = document.createElement("td");
                   tag = document.createElement("span");
                   tag.innerHTML = n.class_name;
                   td.appendChild( tag );
                   tr.appendChild( td );
                   
                   td = document.createElement("td");
                   var imageIndex = eZOEPopupUtils.indexOfImage( n, eZOEPopupUtils.settings.browseImageAlias );
                   if ( imageIndex !== -1 )
                   {
                       tag = document.createElement("span");
                       tag.className = 'image_preview';
                       tag.innerHTML += ' <a href="#">' + ed.getLang('preview.preview_desc')  + '<img src="' + ed.settings.ez_root_url + n.data_map[ n.image_attributes[imageIndex] ].content[eZOEPopupUtils.settings.browseImageAlias].url + '" /></a>';
                       td.appendChild( tag );
                       hasImage = true;
                   }
                   tr.appendChild( td );
                   tr.className = classGenerator.call( this, n, hasImage, ed );

                   tbody.appendChild( tr );
                   emptyList = false;
                } );
            }

            // Make sure int params that needs to be subtracted/added are native int's
            var offset = eZOEPopupUtils.Int( data.content['offset'] ), limit = eZOEPopupUtils.Int( data.content['limit'] );

            tr = document.createElement("tr"), td = document.createElement("td");
            tr.appendChild( document.createElement("td") );
            if ( offset > 0 )
            {
                tag = document.createElement("a");
                tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.' + fn + (offset - limit) + ');');
                tag.innerHTML = '&lt;&lt; ' + ed.getLang('advanced.previous');
                td.appendChild( tag );
            }
            tr.appendChild( td );
            td = document.createElement("td");
            td.setAttribute('colspan', '2');
            if ( (offset + limit) < data.content['total_count'] )
            {
                tag = document.createElement("a");
                tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.' + fn + (offset + limit) + ');');
                tag.innerHTML = ed.getLang('advanced.next') + ' &gt;&gt;';
                td.appendChild( tag );
            }
            tr.appendChild( td );
            tfoot.appendChild( tr );
        }
        if ( emptyList && emptyCallBack !== undefined && emptyCallBack.call !== undefined )
        {
            emptyCallBack.call( this, tbody, mode, ed );
        }
        return false;
    },

    searchCallBack : function( searchData )
    {
        // wrapper function for browseCallBack, called by ajax call in search()
        var data = { 'content': '' };
        if ( searchData && searchData.content !== '' )
        {
            data['content'] = {
                    'limit': searchData.content.SearchLimit,
                    'offset': searchData.content.SearchOffset,
                    'total_count': searchData.content.SearchCount,
                    'list': searchData.content.SearchResult
            };
        }
        return eZOEPopupUtils.browseCallBack( data, 'search', function( tbody, mode, ed ){
            // callback for use when result is empty
            var tr = document.createElement("tr"), td = document.createElement("td"), tag = document.createElement("span");
            tr.appendChild( document.createElement("td") );
            tr.className = 'search-result-empty';
            td.setAttribute('colspan', '3');
            tag.innerHTML = ed.getLang('ez.empty_search_result').replace('<search_string>', jQuery('#SearchText').val() );
            td.appendChild( tag );
            tr.appendChild( td );
            tbody.appendChild( tr );
        } );
    },

    indexOfImage: function( jsonNode, alias )
    {
        if ( !alias ) alias = eZOEPopupUtils.settings.browseImageAlias;
        var index = -1;
        jQuery.each( jsonNode.image_attributes, function( i, attr )
        {
            if ( index === -1 && jsonNode.data_map[ attr ] && jsonNode.data_map[ attr ].content[ alias ] )
                index = i;
        });
        return index;
    },

    // some reusable functions from ezcore
    ie65: /MSIE [56]/.test( navigator.userAgent ),
    Int: function(value, fallBack)
    {
        // Checks if value is a int, if not fallBack or 0 is returned
        value = parseInt( value );
        return isNaN( value ) ? ( fallBack !== undefined ? fallBack : 0 ) : value;
    },
    Float: function(value, fallBack)
    {
        // Checks if value is float, if not fallBack or 0 is returned
        value = parseFloat( value );
        return isNaN( value ) ? ( fallBack !== undefined ? fallBack : 0 ) : value;
    },
    min: function()
    {
       // Returns the lowest number, or null if none
       var min = null;
       for (var i = 0, a = arguments, l = a.length; i < l; i++)
               if (min === null || min > a[i]) min = a[i];
       return min;
    }
};
