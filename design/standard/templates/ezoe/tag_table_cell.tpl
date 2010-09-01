{set scope=global persistent_variable=hash('title', 'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') )),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', cellClassList = {$cell_class_list};
eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
eZOEPopupUtils.settings.tagEditTitleText = "{'Edit %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))|wash('javascript')}";
{literal} 

tinyMCEPopup.onInit.add( eZOEPopupUtils.BIND( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    tagAttributeEditor:    function( ed, el, args )
    {
        var mode = document.getElementById('cell_args_apply_to').value, nodes = false, x = 0, target = this.settings.tagSelector[0].value;

        if ( mode === 'row' )
        {
            // get nodes (cells) in this row
            nodes = jQuery('> *', el.parentNode );
        }
        else if ( mode === 'column' )
        {
            // Figgure out what column we are in adjusted by cell with colspan
            var colspans = 1, rowIndex = el.parentNode.rowIndex, skipRows = 0, rowSpanArray = [];
            for (var i = 0, c = el.parentNode.childNodes, l = c.length; i < l; i++ ) 
            {
                if ( c[i] === el ) x = i + colspans;
                else if ( c[i].colSpan > 1 ) colspans += c[i].colSpan -1;
            };

            // Addjust the column index if any prevous cells in table uses rowspan that might affect it
            if ( rowIndex > 0 )
            {
                var row = el.parentNode, rowSpanOffset = 1;
                while ( row = row.previousSibling )
                {
                    jQuery('> *[rowspan]', row ).each(function( i, cell ){
                        if ( cell.rowSpan >  rowSpanOffset )
                        {
                            x++;
                        }
                    });
                    rowSpanOffset++;
                }
            }

            // Get nodes (cells) in this column
            jQuery('tr', el.parentNode.parentNode ).each( function( trIndex, tr )
            {
                // count down rowSpan values and remove the ones that has reached 0
                for( var i = 0, l = rowSpanArray.length; i < l; i++ )
                {
                    rowSpanArray[i]--;
                    if ( rowSpanArray[i] < 1 ) rowSpanArray.splice( i, 1 );
                }
                if ( skipRows === 0 )
                {
                    var colIndex = x - rowSpanArray.length;
                    jQuery('> *', tr ).each( function( i, cell )
                    {
                        if ( colIndex === ( i + 1 ) )
                        {
                             // add current cell to selected nodes array
                             if ( nodes === false ) nodes = jQuery( cell );
                             else nodes.push( cell );

                             // If this cell has rowspan, make sure we skip the next rows
                             if ( cell.rowSpan > 1 ) skipRows = cell.rowSpan - 1;
                        }
                        else if ( colIndex > ( i + 1 ) )
                        {
                            // correct col index when some cells use colSpan
                            if ( cell.colSpan >  1 ) colIndex -= cell.colSpan - 1;
                            // store rowspans that will effect column index in the next rows
                            if ( cell.rowSpan > 1 ) rowSpanArray.push( cell.rowSpan);
                            
                        }
                    });
                }
                else
                {
                    skipRows--;
                }
            });
        }

        // Apply changes to selected node(s)
        if ( !nodes || !nodes.size() )
        {
            el = eZOEPopupUtils.switchTagTypeIfNeeded( el, target );
            ed.dom.setAttribs( el, args );
        }
        else nodes.each( function( i, cell )
        {
            if ( el === cell )
                cell = el = eZOEPopupUtils.switchTagTypeIfNeeded( cell, target );
            else
                cell = eZOEPopupUtils.switchTagTypeIfNeeded( cell, target );
            ed.dom.setAttribs( cell, args );
        });
        return el;
    },
    tagSelector: ezTagName + '_tag_source',
    tagSelectorCallBack: function( e )
    {
        if ( e === false ) return false;
        var classes = jQuery( '#' + eZOEPopupUtils.settings.tagName + '_class_source' )[0], editorEl = eZOEPopupUtils.settings.editorElement || false;
        eZOEPopupUtils.removeChildren( classes );
        eZOEPopupUtils.addSelectOptions( classes, cellClassList[ this.value ] );
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