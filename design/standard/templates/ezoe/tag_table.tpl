{set scope=global persistent_variable=hash('title', 'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') )),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
eZOEPopupUtils.settings.tagEditTitleText = "{'Edit %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))|wash('javascript')}";
var ezTagName = '{$tag_name|wash}',{literal} tableSizeGrid = {'rows': 0, 'cols': 0};



tinyMCEPopup.onInit.add( eZOEPopupUtils.BIND( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    onInit: function( el, tag )
    {
        if ( el ) return;
        var td = jQuery('#table_cell_size_grid td div'), table = jQuery('#table_cell_size_grid');
        td.each( function(i, el){
            var o = jQuery( el );
            o.mouseover( eZOEPopupUtils.BIND( tableSizeGridMouse, td, o, i, false ) );
            o.click( eZOEPopupUtils.BIND( tableSizeGridMouse, td, o, i, true ) );
        });
        table.mouseout( eZOEPopupUtils.BIND( tableSizeGridMouse, td, 0, -1, false ) );
        jQuery('#table_cell_size').show();
        tableSizeGrid['cols'] = jQuery('#table_cell_size_grid_cols');
        tableSizeGrid['rows'] = jQuery('#table_cell_size_grid_rows');
        tableSizeGrid['cols'].keyup( eZOEPopupUtils.BIND( tableSizeGridInput, td, true ) );
        tableSizeGrid['rows'].keyup( eZOEPopupUtils.BIND( tableSizeGridInput, td, true ) );
        tableSizeGridInput.call( td, true );
    },
    tagGenerator: function( tag, customTag )
    {
        var html = '<table id="__mce_tmp"><tbody>';
        for (var y = 0, yl = eZOEPopupUtils.Int(tableSizeGrid['rows'].val(), 1); y < yl; y++)
        {
            html += "<tr>";
            for (var x = 0, xl = eZOEPopupUtils.Int(tableSizeGrid['cols'].val(), 2); x < xl; x++)
                html += '<td><br mce_bogus="1"/></td>';
    
            html += "</tr>";
        }
        return html + '</tbody></table>';
    },
    tagAttributeEditor: function( ed, el, args )
    {
        args['class'] = jQuery.trim( args['class'] + ( args['border'] == 0 ? ' mceItemTable' : ''))
        ed.dom.setAttribs( el, args );
        return el;
    }
}));


function tableSizeGridMouse( o, i, save )
{
    var cell = i +1, rows = Math.floor( i / 6) +1, cols = cell - (rows -1) * 6;

    tableSizeGridShowChange.call( this, rows, cols, save );

    if ( save )
    {
        tableSizeGrid['rows'].val( rows );
        tableSizeGrid['cols'].val( cols );
    }
}

function tableSizeGridInput( save )
{
    tableSizeGridShowChange.call( this, tableSizeGrid['rows'].val(), tableSizeGrid['cols'].val(), save );
}


function tableSizeGridShowChange( rows, cols, save )
{
    this.each( function( i, el ){
        var cell2 = i +1, rows2 = Math.floor( i / 6) +1, cols2 = cell2 - (rows2 -1) * 6;
        if ( rows2 <= rows && cols2 <= cols )
        {
            if ( save )
                el.style.backgroundColor = '#cccccc';
            else
                el.style.borderColor = '#aaa';
        }
        else
        {
            if ( save )
                el.style.backgroundColor = '#fff';
            else
                el.style.borderColor = '#fff';
        }
    });
}


// -->
</script>
{/literal}

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

        <table class="properties" id="table_cell_size" summary="Click to select table size" style="display: none">
        <tr>
            <td class="column1">{'Size'|i18n('design/standard/ezoe')}:</td>
            <td>
                <table id="table_cell_size_grid">
                <tr>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                </table>
                {def $table_default_values = ezini( 'table', 'Defaults', 'content.ini' )}
                {'Columns'|i18n('design/standard/ezoe')}: <input id="table_cell_size_grid_cols" type="text" maxlength="2" size="3" value="{first_set( $table_default_values['cols'], 2 )}" />
                {'Rows'|i18n('design/standard/ezoe')}: <input id="table_cell_size_grid_rows" type="text" maxlength="3" size="3" value="{first_set( $table_default_values['rows'], 1 )}" />
            </td>
        </tr>
        </table>
        
        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name   = $tag_name
                 attributes = hash('width', 'htmlsize',
                                   'border', 'htmlsize',
                                   'class', $class_list
                                 )
        }

        {include uri="design:ezoe/customattributes.tpl" tag_name=$tag_name}

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