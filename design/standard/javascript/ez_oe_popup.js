/*
    eZ Online Editor MCE popup : common js code used in popups
    Created on: <06-Feb-2008 00:00:00 ar>
    
    Copyright (c) 2008 eZ Systems AS
    Licensed under the GPL 2.0 License:
    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt 
*/

var ezajaxObject = ez.ajax( { 'charset': 'UTF-8' } ), ezajaxLoadResponse = false;

function safeHtml( value )
{
    // removes some unwanted stuff from values
    value = value.replace(/&/g, '&amp;');
    value = value.replace(/\"/g, '&quot;');
    value = value.replace(/</g, '&lt;');
    value = value.replace(/>/g, '&gt;');
    return value;
}

var ezXmlToXhtmlHash = {
    'paragraph': 'P',
    'literal': 'PRE',
    'custom': 'SPAN',
    'anchor': 'A',
    'link': 'A'
};

function insertGeneralTag()
{
    var ed = tinyMCEPopup.editor, selectedTag = ezTagName || '', n, arr;
    
    if ( ezTagName === 'custom' && (n = ez.$('custom_class_source')) )
        selectedTag = n.el.value;
       
    if ( tinymce.isWebKit )
        ed.getWin().focus();
    
    var args = {
        'customattributes': getCustomAttributeValue( selectedTag + '_customattributes')
    };

    // set general attributes for tag
    if (n = ez.$( ezTagName + '_attributes'))
       ez.$$('input,select', n).forEach(function(o){
           if ( o.hasClass('mceItemSkip') ) return; 
           var name = o.el.id.split('_')[1];
           if ( window.specificAttributeGenerator !== undefined && specificAttributeGenerator[name] !== undefined )
               args[name] = specificAttributeGenerator[name]( o, args );
           else
               args[name] = o.postData(true);
       });

    ed.execCommand('mceBeginUndoLevel');
    if ( !tinyMCEelement  )
    {
	    if ( ezTagName === 'link' )
	    {
	        ed.execCommand('mceInsertLink', false, args, {skip_undo : 1} );
	    }
	    // create new node if none is defined and if tag type is defined in ezXmlToXhtmlHash
	    else if ( window.specificTagGenerator !== undefined )
	    {
	        ed.execCommand('mceInsertContent', false, specificTagGenerator( ezTagName ), {skip_undo : 1} );
	        tinyMCEelement = ed.dom.get('__mce_tmp');
	    }
	    else if ( ezXmlToXhtmlHash[ezTagName] )
	    {
	        ed.execCommand('mceInsertContent', false, '<' + ezXmlToXhtmlHash[ezTagName] + ' id="__mce_tmp">&nbsp;</' + ezXmlToXhtmlHash[ezTagName] + '>', {skip_undo : 1} );
	        tinyMCEelement = ed.dom.get('__mce_tmp');
	    }
	}
	else if ( window.specificTagEditor !== undefined ) specificTagEditor( tinyMCEelement, ed );

    if ( tinyMCEelement )
    {
        ed.dom.setAttribs(tinyMCEelement, args);
        if ( args['id'] === undefined ) ed.dom.setAttrib(tinyMCEelement, 'id', '');
    }
    ed.execCommand('mceEndUndoLevel');

    ed.execCommand('mceRepaint');
    tinyMCEPopup.close();
    return false;
}


function insertEmbedTag( )
{
    var ed = tinyMCEPopup.editor, selectedSize = ez.$('embed_size_source').postData(true), a;
    
    if ( tinymce.isWebKit )
        ed.getWin().focus();
    
    var args = {
        'class' : ez.$('embed_class_source').postData(true),
        'id' : 'eZObject_' + embedObject['contentobject_id'],
        'inline' : ez.$('embed_inline_source').el.checked ? 'true' : 'false'
    };
    
    ed.execCommand('mceBeginUndoLevel');
    if ( !tinyMCEelement )
    {
        ed.execCommand('mceInsertContent', false, specificTagGenerator( 'embed' ), {skip_undo : 1} );
        tinyMCEelement = ed.dom.get('__mce_tmp');
    }
    else if ( window.specificTagEditor !== undefined ) specificTagEditor( tinyMCEelement );
    
    if ( contentType === 'image' )
    {
        var sizeObj     = embedObject['data_map']['image']['content'][selectedSize];
        args['src']     = eZOeMCE['root'] + sizeObj['url'];
        args['mce_src'] = eZOeMCE['root'] + sizeObj['url'];
        args['alt']     = safeHtml( selectedSize );
        args['title']   = safeHtml( sizeObj['alternative_text'] || embedObject['name'] );
        args['border']  = 0;
        args['align']   = ez.$('embed_align_source').postData(true);
    }
    else
    {
        args['class'] = 'mceNonEditable ' + args['class'];
        a = ez.$('embed_align_source').postData(true);
        ed.dom.setStyle(tinyMCEelement, 'float', a === 'middle' ? '' : a)
    }
    args['customattributes'] = getCustomAttributeValue( args['inline'] === 'false' ? 'embed_customattributes' : 'embed-inline_customattributes');

    ed.dom.setAttribs( tinyMCEelement, args );
    
    ed.execCommand('mceEndUndoLevel');

    ed.execCommand('mceRepaint');
    tinyMCEPopup.close();
    return false;
}

function cancelAction()
{
    tinyMCEPopup.close();
}

function loadImageSize( size )
{
    // Dynamically loads image sizes as they are requested
    var attribObj = embedObject['data_map']['image']['content'];
    if ( !attribObj || !embedImageNode )
    {
        // Image attribute or node missing
    }
    else if ( attribObj[size] )
    {
        embedImageNode.src = eZOeMCE['root'] + attribObj[size]['url'];
        tinyMCEPopup.resizeToInnerSize();
    }
    else
    {
        var url = eZOeMCE['extension_url'] + '/load/' + embedObject['contentobject_id'];
        ezajaxObject.load( url, 'imagePreGenerateSizes='+size, function(r){
            ez.script( 'ezajaxLoadResponse=' + r.responseText );
            if ( ezajaxLoadResponse )
            {
                embedObject['data_map']['image']['content'][ selectedSize ] = ezajaxLoadResponse['data_map']['image']['content'][ selectedSize ];
                embedImageNode.src = eZOeMCE['root'] + embedObject['data_map']['image']['content'][ selectedSize ]['url'];
            }
        });
    }
}

function loadEmbedPreview()
{
        var url = eZOeMCE['extension_url'] + '/embed_view/' + embedObject['contentobject_id'];
        var postData = ez.$$('#embed_attributes input,#embed_attributes select').callEach('postData').join('&');
        ezajaxObject.load( url, postData, function(r)
        {
            ez.$('embed_preview').el.innerHTML = r.responseText;
        });
}

function removeSelectOptions( node )
{
    // removes all options in a selection
    if ( !node || node.nodeName !== 'SELECT'  ) return;
    while ( node.hasChildNodes() )
    {
        node.removeChild( node.firstChild );
    }
    node.disabled = true;
}

function addSelectOptions( node, o )
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
}

function getCustomAttributeValue( node )
{
    // creates attribure value from form values
    if (node = ez.$( node ))
        return ez.$$('input,select', node).map(function( o ){
            var name = o.el.id.split('_')[1];
            return name + '|' + o.postData( true );
        }).join('attribute_separation');
     return '';
}

function initCustomAttributeValue( node, valueString )
{
    // sets deafult values for based on attribute value
    if ( valueString === null || !(node = ez.$( node )) ) return;
    var arr = valueString.split('attribute_separation'), values = {}, t;
    for(var i = 0, l = arr.length; i < l; i++)
    {
        t = arr[i].split('|');
        values[t[0]] = t[1];
    }
    ez.$$('input,select', node).forEach(function( o ){
        var name = o.el.id.split('_')[1];
        if ( values[name] !== undefined ) o.el.value = values[name];
    });
    
}

function initGeneralmAttributes( node, valueElement )
{
    // init general attributes for tag
    if (node = ez.$( node ))
       ez.$$('input,select', node).forEach(function(o){
           if ( o.hasClass('mceItemSkip') ) return;
           var name = o.el.id.split('_')[1];
           var v = name === 'class' ?  ez.string.trim( valueElement.className.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|mceVisualAid|mceNonEditable)/g, '') ) : valueElement.getAttribute( name );
           if ( v !== null && v !== undefined ) o.el.value = v;
       });
    
}

function loadInlineDependatSelections( inline )
{
    // toogles data when the user clicks inline, since
    // embed and embed-inline have different settings
    var viewList = ez.$('embed_view_source'), classList = ez.$('embed_class_source');
    removeSelectOptions( viewList.el );
    removeSelectOptions( classList.el );
    addSelectOptions( viewList.el, inline ? viewListDataInline : viewListData );
    addSelectOptions( classList.el, inline ? classListDataInline : classListData );
    ez.$( inline ? 'embed_customattributes' : 'embed-inline_customattributes' ).hide();
    ez.$( !inline ? 'embed_customattributes' : 'embed-inline_customattributes' ).show();
}


function cancelAction()
{
    tinyMCEPopup.close();
}

function selectByEmbedId( searchId )
{
  if ( ez.val( searchId ) )
  {
      window.location = eZtinyMceRelationUrl + '/eZObject_' + searchId;
  }
}

function ezajaxSearchEnter( e, isButton )
{
    if ( isButton ) return ezajaxSearchPost();
    e = e || window.event;
    key = e.which || e.keyCode;
    if ( key == 13) return ezajaxSearchPost(); // enter key
    return true;
}

function ezajaxSearchPost( offset )
{
    var postData = ez.$$('#search_box input').callEach('postData').join('&'), o = ( offset || 0 ) * 10;    
    var url = eZOeMCE['extension_url'] + '/search//'+ o +'/10';
    if ( ez.string.trim( ez.$('SearchText').el.value ) ) ezajaxObject.load( url, postData, ezajaxSearchPostBack);
    return false;
}

function ezajaxSearchPostBack( r )
{
    ez.script( 'ezajaxLoadResponse=' + r.responseText );
    var div = ez.$('search_box_prev');
    div.el.innerHTML = "";
    if ( !ezajaxLoadResponse ) return false;
    var html, arr = ezajaxLoadResponse['list'], img;
    for(var i = 0, l = arr.length; i<l; i++ )
    {
        if ( contentType === 'image' && arr[i]['data_map']['image'] !== undefined && arr[i]['data_map']['image']['content']['small'] )
        {
            html = '<a href="JavaScript:selectByEmbedId(' + arr[i].contentobject_id + ');" class="contenttype_image">';
            html += '<img src="' + eZOeMCE['root'] + arr[i]['data_map']['image']['content']['small']['url'] + '" alt="' + arr[i].name +'" \/>';
        }
        else
        {
            html = '<a href="JavaScript:selectByEmbedId(' + arr[i].contentobject_id + ');">';
            html += arr[i].name + ' &nbsp; [ ' + arr[i].class_identifier + ' ]';
        }
        html += '<\/a>';
        div.el.innerHTML += html;
    }
    return false;
}
