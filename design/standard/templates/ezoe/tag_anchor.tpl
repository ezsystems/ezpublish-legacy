{set scope=global persistent_variable=hash('title', 'Anchor properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ez_core.js',
                                                            'javascript/ez_core_animation.js',
                                                            'javascript/ez_core_accordion.js',
                                                            'javascript/ez_oe_popup.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var tinyMCEelement = false, ezTagName = '{$tag_name|wash}'; 
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
        initGeneralmAttributes( ezTagName + '_attributes', tinyMCEelement );
        initCustomAttributeValue( ezTagName + '_customattributes', tinyMCEelement.getAttribute('customattributes'))
    }
});

function specificTagGenerator( tag )
{
    return '<a id="__mce_tmp" class="mceItemAnchor">&nbsp;</a>';
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
        
        {include uri="design:ezoe/generalattributes.tpl" tag_name=$tag_name attributes=hash('name', '') attribute_defaults=hash('name', 'Anchor')}

        {include uri="design:ezoe/customattributes.tpl" tag_name=$tag_name}

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