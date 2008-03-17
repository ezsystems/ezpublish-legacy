{set scope=global persistent_variable=hash('title', 'Cell Properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var tinyMCEelement = false, ezTagName = '{$tag_name|wash}', cellClassList = {$cell_class_list}; 
{literal} 

tinyMCEPopup.onInit.add( function()
{
    // Initialize page with default values and tabs
    var ed = tinyMCEPopup.editor, el = ed.selection.getNode(), n;
    if ( el && el.nodeName )
    {
        if ( el.nodeName === ezXmlToXhtmlHash[ ezTagName ] )
            tinyMCEelement = el;
        else if ( ezXmlToXhtmlHash[ ezTagName ] && ( el = ed.dom.getParent(el, ezXmlToXhtmlHash[ ezTagName ] ) ) )
            tinyMCEelement = el;
        else if ( el = ed.dom.getParent(el, ezTagName ) )
            tinyMCEelement = el;
    }

    if ( tinyMCEelement )
    {
        initGeneralmAttributes( ezTagName + '_attributes', tinyMCEelement )
        initCustomAttributeValue( ezTagName + '_customattributes', tinyMCEelement.getAttribute('customattributes'))
    }
    ez.$(ezTagName + '_type_source').addEvent('change', toggleCellType);
});

function switchTagTypeIfNeeded( el, ed, targetTag )
{
    var currentTag = el.nodeName.toLowerCase();
    if ( targetTag !== currentTag )
    {
        // changing to a different node type
        var  doc = ed.getDoc(), newCell = doc.createElement( targetTag );

        for ( var c = 0; c < el.childNodes.length; c++ )
            newCell.appendChild(el.childNodes[c].cloneNode(1));

        for ( var a = 0; a < el.attributes.length; a++ )
            ed.dom.setAttrib(newCell, el.attributes[a].name, ed.dom.getAttrib(el, el.attributes[a].name));

        el.parentNode.replaceChild( newCell, el );
        return newCell;
    }
    return el;
}

function specificTagAttributeEditor( ed, el, args )
{
    var mode = ez.$('cell_args_apply_to').el.value, nodes, x = 0, target = ez.$(ezTagName + '_type_source').el.value;
    if ( mode === 'row' )
    {
        nodes = ez.$$('> *', el.parentNode );
    }
    else if ( mode === 'column' )
    {
	    for (var i = 0, c = el.parentNode.childNodes, l = c.length; i < l; i++ ) 
	    {
	        if ( c[i] === el ) x = i + 1;
	    };
        nodes = ez.$$('tr > *:nth-child(' + x + ')', el.parentNode.parentNode );
    }
    if ( !nodes )
    {
        el = switchTagTypeIfNeeded( el, ed, target );
        ed.dom.setAttribs(el, args);
    }
    else nodes.forEach(function( o )
    {
        o.el = switchTagTypeIfNeeded( o.el, ed, target );
        ed.dom.setAttribs( o.el, args );
    });
        
}

function toggleCellType( e, el )
{
    var node = ez.$(ezTagName + '_class_source').el;
    removeSelectOptions( node );
    addSelectOptions( node, cellClassList[el.value] );
    if ( tinyMCEelement && tinyMCEelement.className ) node.value = tinyMCEelement.className;
    ez.$$('table.custom_attributes').forEach(function(o){
        if ( o.el.id === el.value + '_customattributes' )
            o.show();
        else
            o.hide();
    }, this);
}

{/literal}

// -->
</script>


<div>

    <form onsubmit="return insertGeneralTag( this );" action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data"
    style="width: 360px;">
    

    <div class="slide" style="width: 360px;">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash}</h2>
        </div>
        {def $cell_tag_list = hash('td', 'td', 'th', 'th')}

        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name   = $tag_name
                 attributes = hash('type', $cell_tag_list,
                                   'width', '',
                                   'class', $class_list
                                 )
                 attribute_defaults = hash('type', $tag_name )
                 classes    = hash('type', 'mceItemSkip')
        }

        
		{foreach $cell_tag_list as $cell_tag => $text}
		    {include uri="design:ezoe/customattributes.tpl" hide=$tag_name|ne( $cell_tag ) tag_name=$cell_tag}
		{/foreach}

        <table class="properties">
        <tr>
            <td class="column1"><label for="cell_args_apply_to">{'Apply to'|i18n('design/standard/ezoe')}</label></td>
            <td><select id="cell_args_apply_to">
                        <option value="cell">{'Cell'|i18n('design/standard/ezoe')}</option>
                        <option value="column">{'Column'|i18n('design/standard/ezoe')}</option>
                        <option value="row">{'Row'|i18n('design/standard/ezoe')}</option>
            </select></td>
        </tr>
        </table>

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" onclick="cancelAction();" />

                     


                <!-- todo: upload new button / link / tab -->
            </div> 
        </div>

    </div>
    </form>

</div>