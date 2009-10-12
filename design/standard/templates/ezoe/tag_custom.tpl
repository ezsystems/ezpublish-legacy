{set scope=global persistent_variable=hash('title', 'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') )),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', customTagName = '{$custom_tag_name}', imageIcon = {"tango/image-x-generic22.png"|ezimage};
eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
eZOEPopupUtils.settings.tagEditTitleText = "{'Edit %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '.', $custom_tag_name, '&gt;') ))|wash('javascript')}";
{literal} 


tinyMCEPopup.onInit.add( eZOEPopupUtils.BIND( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    cssClass: 'mceItemCustomTag',
    tagSelector: 'custom_name_source',
    onInit: function( el, tag, ed )
    {
        // custom block tags are not allowed inside custom inline tags
        if ( el )
        {
            if ( eZOEPopupUtils.getParentByTag( el, 'span', 'mceItemCustomTag', 'custom' ) )
                filterOutCustomBlockTags( );
            else if ( eZOEPopupUtils.getParentByTag( el, 'h1,h2,h3,h4,h5,h6' ) )
                filterOutCustomBlockTags( );
        }
        else
        {
            var currentNode = ed.selection.getNode();
            if ( currentNode && currentNode.nodeName !== 'DIV' && tinymce.DOM.getAttrib( currentNode, 'type' ) === 'custom' )
                filterOutCustomBlockTags( );
            else if ( eZOEPopupUtils.getParentByTag( currentNode, 'span', 'mceItemCustomTag', 'custom', true ) )
                filterOutCustomBlockTags( );
            else if ( eZOEPopupUtils.getParentByTag( currentNode, 'h1,h2,h3,h4,h5,h6', false, false, true ) )
                filterOutCustomBlockTags( );
        }
    },
    tagGenerator: function( tag, customTag, selectedHtml )
    {
        if ( !selectedHtml ) selectedHtml = customTag;

        if ( customTag === 'underline' )
        {
            return '<u id="__mce_tmp" type="custom">' + selectedHtml + '<\/u>';
        }
        else if ( customTag === 'sub' || customTag === 'sup' )
        {
            return '<' + customTag + ' id="__mce_tmp" type="custom">' + selectedHtml + '<\/' + customTag + '>';
        }
        else if ( document.getElementById( customTag + '_inline_source' ).checked )
        {
            if ( document.getElementById( customTag + '_inline_source' ).value === 'image' )
            {
                var customImgUrl = document.getElementById( customTag + '_image_url_source' ), imageSrc = imageIcon;
                if ( customImgUrl && customImgUrl.value )
                    imageSrc = customImgUrl.value;
                return '<img id="__mce_tmp" type="custom" src="' + imageSrc + '" />';
            }
            else
                return '<span id="__mce_tmp" type="custom">' + selectedHtml + '<\/span>';
        }
        else
        {
            return '<div id="__mce_tmp" type="custom"><p>' + selectedHtml + '<\/p><\/div>';
        }
    },
    onTagGenerated:  function( el, ed, args )
    {
        // append a paragraph if user just inserted a custom tag in editor and it's the last tag
        var edBody = el.parentNode, doc = ed.getDoc(), temp = el;
        if ( edBody.nodeName !== 'BODY' )
        {
            temp = edBody;
            edBody = edBody.parentNode
        }
        if ( edBody.nodeName === 'BODY'
        && edBody.childNodes.length <= (jQuery.inArray( temp, edBody.childNodes ) +1) )
        {
            var p = doc.createElement('p');
            p.innerHTML = ed.isIE ? '&nbsp;' : '<br />';
            edBody.appendChild( p );
        }
    },
    tagEditor: function( el, ed, customTag, args )
    {
        var inline = document.getElementById( customTag + '_inline_source' ), target = ( inline.checked ? 'span' : 'div'), origin = el.nodeName;

        if ( customTag === 'underline' )
            target = 'u';
        else if ( customTag === 'sub' || customTag === 'sup' )
            target = customTag;
        else if ( inline.value === 'image' )
            target = 'img';

        el = eZOEPopupUtils.switchTagTypeIfNeeded( el, target );
        if ( el.nodeName !== 'DIV' && origin === 'DIV' )
        {
            // remove p tag if inline tag
            var childs = jQuery('> *', el);
            if ( childs.size() === 1 && childs[0].nodeName === 'P' )
                el.innerHTML = childs[0].innerHTML;
        }
        else if ( el.nodeName === 'DIV' && origin !== 'DIV' )
        {
            // add p tag if block tag and no child tags
            var childs = jQuery('> *', el);
            if ( childs.size() === 0 || childs[0].nodeName !== 'P' )
                el.innerHTML = '<p>' + el.innerHTML + '</p>';
        }
        ed.dom.setAttrib( el, 'type', 'custom' );

        if ( target === 'img' )
        {
            var customImgUrl = document.getElementById( customTag + '_image_url_source' );
            if ( customImgUrl && customImgUrl.value )
                ed.dom.setAttrib( el, 'src', customImgUrl.value );
            else
                ed.dom.setAttrib( el, 'src', imageIcon );
        }

        return el;
    }
}));


function filterOutCustomBlockTags( n )
{
    var inlineTags = [];
    jQuery('input[id*=_inline_source]').each(function( i, node ){
        if ( node.checked ) inlineTags.push( node.id.split('_inline_')[0] );
    });
    jQuery('#custom_name_source option').each(function( i, node ){
        if ( jQuery.inArray( node.value, inlineTags ) === -1 ) node.parentNode.removeChild( node );
    });
}

{/literal}

// -->
</script>


<div class="tag-view tag-type-{$tag_name} custom-tag-type-{$custom_tag_name}">

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">

        <div id="tabs" class="tabs">
        <ul>
            <li class="tab current"><span><a href="JavaScript:void(0);">{'Properties'|i18n('design/standard/ezoe')}</a></span></li>
        </ul>
        </div>

<div class="panel_wrapper">
    <div class="panel current">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;" id="tag-edit-title">{'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))}</h2>
        </div>

        {* custom tag name is defined as class internally in the editor even though the xml attribute name is 'name' *}
        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name=$tag_name
                 attributes=hash('class', $class_list )
                 attribute_mapping=hash('class', 'name')
                 i18n=hash('name', 'Tag'|i18n('design/standard/ezoe'))
                 attribute_defaults=hash('name', $custom_tag_name)}

{def $tag_is_inline = false()}
{foreach $class_list as $custom_tag => $text}
        {set $tag_is_inline = cond( is_set($custom_inline_tags[$custom_tag]), $custom_inline_tags[$custom_tag], 'false' )}
        {include uri="design:ezoe/customattributes.tpl" tag_name=$custom_tag hide=$custom_tag_name|ne( $custom_tag ) extra_attribute=array('inline', $tag_is_inline, array('image', 'true')|contains( $tag_is_inline ))}
{/foreach}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div> 
        </div>

    </div>

{if is_set( $attribute_panel_output )}
{foreach $attribute_panel_output as $attribute_panel_output_item}
    {$attribute_panel_output_item}
{/foreach}
{/if}

</div>
    </form>

</div>