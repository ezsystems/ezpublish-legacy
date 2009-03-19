{set scope=global persistent_variable=hash('title', 'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') )),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', cellClassList = {$cell_class_list};
eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
eZOEPopupUtils.settings.tagEditTitleText = "{'Edit %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))|wash('javascript')}";
{literal} 

tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    tagAttributeEditor:    function( ed, el, args )
    {
        var mode = ez.$('cell_args_apply_to').el.value, nodes, x = 0, target = this.settings.tagSelector.el.value;

        if ( mode === 'row' )
        {
            // get nodes (cells) in this row
            nodes = ez.$$('> *', el.parentNode );
        }
        else if ( mode === 'column' )
        {
            // figgure out what column we are in
            for (var i = 0, c = el.parentNode.childNodes, l = c.length; i < l; i++ ) 
            {
                if ( c[i] === el ) x = i + 1;
            };
            // get nodes (cells) in this column
            nodes = ez.$$('tr > *:nth-child(' + x + ')', el.parentNode.parentNode );
        }

        if ( !nodes )
        {
            el = eZOEPopupUtils.switchTagTypeIfNeeded( el, target );
            ed.dom.setAttribs(el, args);
        }
        else nodes.forEach(function( o )
        {
            o.el = eZOEPopupUtils.switchTagTypeIfNeeded( o.el, target );
            ed.dom.setAttribs( o.el, args );
        });
    },
    tagSelector: ezTagName + '_tag_source',
    tagSelectorCallBack: function( e, el )
    {
        if ( e === false ) return false;
        var classes = ez.$( eZOEPopupUtils.settings.tagName + '_class_source' ).el, editorEl = eZOEPopupUtils.settings.editorElement || false;
        eZOEPopupUtils.removeChildren( classes );
        eZOEPopupUtils.addSelectOptions( classes, cellClassList[ el.value ] );
        if ( editorEl && editorEl.className )
            classes.value = editorEl.className;
        eZOEPopupUtils.toggleCustomAttributes.call( this );
    }
}));


{/literal}

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
            <h2 style="padding: 0 0 4px 0;" id="tag-edit-title">{'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))}</h2>
        </div>
        {def $cell_tag_list = hash('td', 'Table cell'|i18n('design/standard/ezoe'), 'th', 'Table header'|i18n('design/standard/ezoe'))}

        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name   = $tag_name
                 attributes = hash('tag', $cell_tag_list,
                                   'width', 'htmlsize',
                                   'class', $class_list
                                 )
                 attribute_defaults = hash('tag', $tag_name )
                 classes    = hash('tag', 'mceItemSkip')
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