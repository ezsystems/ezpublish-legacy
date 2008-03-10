{set scope=global persistent_variable=hash('title', 'Custom tag properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ez_core.js',
                                                            'javascript/ez_core_animation.js',
                                                            'javascript/ez_core_accordion.js',
                                                            'javascript/ez_oe_popup.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var tinyMCEelement = false, ezTagName = '{$tag_name|wash}', customAttributeTables = false; 
{literal} 

tinyMCEPopup.onInit.add( function()
{
    // Initialize page with default values and tabs
    var ed = tinyMCEPopup.editor, el = ed.selection.getNode(), n = ez.$('custom_class_source'), arr, selectedTag = n.el.value;
    if ( el && el.nodeName )
    {
        // see if selected tag or one of its parent tags is a custom tag
        if ( selectedTag === 'underline' && el.nodeName === 'U' )
        {
            tinyMCEelement = el;
            // if custom tag is underline, we disable selector to avoid problems (different tag used)
            n.el.disabled = true;
        }
        else if ( el.nodeName === ezXmlToXhtmlHash[ ezTagName ] )
            tinyMCEelement = el;
        else if ( ezXmlToXhtmlHash[ ezTagName ] && ( el = ed.dom.getParent(el, ezXmlToXhtmlHash[ ezTagName ] ) ) )
            tinyMCEelement = el;
        else if ( el = ed.dom.getParent(el, ezTagName ) )
            tinyMCEelement = el;
    }

    if ( tinyMCEelement )
    {
        if ( tinyMCEelement.className )
            n.el.value = selectedTag = tinyMCEelement.className;
        initCustomAttributeValue( selectedTag + '_customattributes', tinyMCEelement.getAttribute('customattributes'));
    }
    // toggle custom attributes based on selected custom tag
    toggleCustomAttributes.call( n );
    n.addEvent('change', toggleCustomAttributes);
});

function specificTagGenerator()
{
    return '<span id="__mce_tmp" type="custom"><p></p></span>';
}


function toggleCustomAttributes( )
{
    arr = ez.$$('table.custom_attributes');
    arr.forEach(function(o){
        if ( o.el.id === this.el.value + '_customattributes' )
            o.show();
        else
            o.hide();
    }, this);
}

{/literal}

// -->
</script>


<div>

    <form onsubmit="return insertGeneralTag( this );" action="JavaScript:void(0)" method="POST" name="EditForm" id="EditForm" enctype="multipart/form-data"
    style="width: 360px;">
    

    <div class="slide" style="width: 360px;">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash}</h2>
        </div>

        {* custom tag name is defined as class internally in the editor even though the xml attribute name is 'name' *}
        {include uri="design:ezoe/generalattributes.tpl" tag_name=$tag_name attributes=hash('class', $class_list ) description=hash('class', 'Tag') attribute_defaults=hash('class', $custom_tag_name)}

{def $tag_is_inline = false()}
{foreach $class_list as $custom_tag => $text}
        {set $tag_is_inline = and( is_set($custom_inline_tags[$custom_tag]), $custom_inline_tags[$custom_tag]|eq('true'))}
        {include uri="design:ezoe/customattributes.tpl" tag_name=$custom_tag hide=$custom_tag_name|ne( $custom_tag ) extra_attribute=array('inline', 'true', $tag_is_inline)}
{/foreach}

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