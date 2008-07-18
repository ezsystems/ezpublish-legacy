{set scope=global persistent_variable=hash('title', 'Anchor properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {ldelim}
    tagName: '{$tag_name|wash}',
    form: 'EditForm',
    cancelButton: 'CancelButton',
    customAttributeStyleMap: {$custom_attribute_style_map},
    cssClass: 'mceItemAnchor',
{literal}
    onInit: function( el, tag, ed )
    {
        if ( el === false && this.settings.editorSelectedText !== false )
            ez.$('anchor_name_source').el.value = this.settings.editorSelectedText;
    },
    tagAttributeEditor: function( ed, el, args )
    {
        el.innerHTML = '';
        ed.dom.setAttribs( el, args );
    }
{/literal}
{rdelim} ) );

// -->
</script>


<div class="tag-view tag-type-{$tag_name}">

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">

        <div id="tabs" class="tabs">
        <ul>
            <li class="tab current"><span><a href="JavaScript:void(0);">{'Properties'|i18n('design/standard/ezoe')}</a></span></li>
        </ul>
        </div>

<div class="panel_wrapper">
    <div class="panel current">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash}</h2>
        </div>
        
        {include uri="design:ezoe/generalattributes.tpl" tag_name=$tag_name attributes=hash('name', '') attribute_defaults=hash('name', 'Anchor')}

        {include uri="design:ezoe/customattributes.tpl" tag_name=$tag_name}

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