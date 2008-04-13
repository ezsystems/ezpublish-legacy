{set scope=global persistent_variable=hash('title', 'Custom tag properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', customTagName = '{$custom_tag_name}'; 
{literal} 


tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    cssClass: 'mceItemCustomTag',
    tagSelector: 'custom_class_source',
    onInit: function( el, tag, ed )
    {        
        if ( customTagName === 'underline' && el && el.nodeName === 'U' )
        {
            // if custom tag is underline, we disable selector to avoid problems (different tag used)
            this.settings.tagSelector.el.disabled = true;
        }

        // custom block tags are not allowed inside custom inline tags
        if ( el )
        {
            if ( customGetParentTag( el, 'span', 'mceItemCustomTag', 'custom' ) )
                filterOutCustomBlockTags( );
        }
        else
        {
	        var currentNode = ed.selection.getNode(), parentSpan = customGetParentTag( el, 'span', 'mceItemCustomTag', 'custom' );
	        if ( currentNode && currentNode.nodeName === 'SPAN' && tinymce.DOM.getAttrib( currentNode, 'type' ) === 'custom' )
	            filterOutCustomBlockTags( );
	        else if ( parentSpan )
	            filterOutCustomBlockTags( );
        }
    },
    tagGenerator: function( tag, customTag, text )
    {
	    if ( customTag === 'underline' )
            return '<u id="__mce_tmp" type="custom"><p class="mceItemHidden">' + (text ? text : customTag) + '<\/p><\/u>';
	    else if ( ez.$( customTag + '_inline_source' ).el.checked )
	        return '<span id="__mce_tmp" type="custom"><p class="mceItemHidden">' + (text ? text : customTag) + '<\/p><\/span>';
	    else
	        return '<div id="__mce_tmp" type="custom"><p>' + (text ? text : customTag) + '<\/p><\/div>';
    },
    tagEditor: function( el, ed, customTag, args )
    {
        var target = customTag === 'underline' ? 'u' : ( ez.$( customTag + '_inline_source' ).el.checked ? 'span' : 'div'), origin = el.nodeName;
        el = eZOEPopupUtils.switchTagTypeIfNeeded( el, target );
        if ( el.nodeName !== 'DIV' && origin === 'DIV' )
        {
            // hide inline paragraph
            if ( el.hasChildNodes() && el.childNodes.length === 1 && el.childNodes[0].nodeName === 'P')
                el.childNodes[0].className = 'mceItemHidden';
        }
        else if ( el.nodeName === 'DIV' && origin !== 'DIV' )
        {
            // show inline paragraph           
            if ( el.hasChildNodes() && el.childNodes.length === 1 && el.childNodes[0].nodeName === 'P'  )
                el.childNodes[0].className = '';

        }
        return el;
    }
}));


function filterOutCustomBlockTags( n )
{
    var inlineTags = ez.$c();
    ez.$$('input[id*=_inline_source]').forEach(function( o ){
        if ( o.el.checked ) inlineTags.push( o.el.id.split('_inline_')[0] );
    });
    ez.$$('#custom_class_source option').forEach(function( o ){
        if ( inlineTags.indexOf( o.el.value ) === -1 ) o.el.parentNode.removeChild( o.el );
    });
}

function customGetParentTag( el, tag, class, type )
{
    class = ' ' + class + ' ';
    tag = tag.toUpperCase();
    while ( el && el.nodeName !== 'BODY' )
    {
        el = el.parentNode;
        if ( el && el.nodeName === tag && ( class === undefined || (' ' + el.className + ' ').indexOf( class ) !== -1 ) &&  ( type === undefined || el.getAttribute('type') === type ) )
            return el;
    }
    return false;
}

{/literal}

// -->
</script>


<div>

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">

        <div id="tabs" class="tabs">
        <ul>
            <li class="tab current"><span><a href="JavaScript:void(0);">{'Properties'|i18n('design/standard/ezoe')}</a></span></li>
        </ul>
        </div>

<div class="panel_wrapper">
    <div class="panel current">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash} {$custom_tag_name|upfirst|wash}</h2>
        </div>

        {* custom tag name is defined as class internally in the editor even though the xml attribute name is 'name' *}
        {include uri="design:ezoe/generalattributes.tpl" tag_name=$tag_name attributes=hash('class', $class_list ) i18n=hash('class', 'Tag'|i18n('design/standard/ezoe')) attribute_defaults=hash('class', $custom_tag_name)}

{def $tag_is_inline = false()}
{foreach $class_list as $custom_tag => $text}
        {set $tag_is_inline = and( is_set($custom_inline_tags[$custom_tag]), $custom_inline_tags[$custom_tag]|eq('true'))}
        {include uri="design:ezoe/customattributes.tpl" tag_name=$custom_tag hide=$custom_tag_name|ne( $custom_tag ) extra_attribute=array('inline', 'true', $tag_is_inline)}
{/foreach}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div> 
        </div>

    </div>
</div>
    </form>

</div>