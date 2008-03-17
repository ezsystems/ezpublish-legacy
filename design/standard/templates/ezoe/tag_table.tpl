{set scope=global persistent_variable=hash('title', 'Table Properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup.js'),
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
        if ( el.nodeName === ezTagName.toUpperCase() )
            tinyMCEelement = el;
    }

    if ( tinyMCEelement )
    {
        initGeneralmAttributes( ezTagName + '_attributes', tinyMCEelement );
        initCustomAttributeValue( ezTagName + '_customattributes', tinyMCEelement.getAttribute('customattributes'));
    }
    else
    {
        var td = ez.$$('#table_cell_size_grid td'), table = ez.$('table_cell_size_grid');
        td.forEach(function(o, i){
            o.addEvent('mouseover', ez.fn.bind(tableSizeGridMouse, this, o, i, false ) );
            o.addEvent('click', ez.fn.bind(tableSizeGridMouse, this, o, i, true ) );
        }, td);
        table.addEvent('mouseout', ez.fn.bind(tableSizeGridMouse, td, 0, -1, false ));
        ez.$('table_cell_size').show();
        tableSizeGrid['cols'] = ez.$('table_cell_size_grid_cols');
        tableSizeGrid['rows'] = ez.$('table_cell_size_grid_rows');
        tableSizeGrid['cols'].addEvent('keyup', ez.fn.bind(tableSizeGridInput, td, true ));
        tableSizeGrid['rows'].addEvent('keyup', ez.fn.bind(tableSizeGridInput, td, true ));
        tableSizeGridInput.call( td, true );
    }
});

function specificTagGenerator( tag )
{
    var html = '<table id="__mce_tmp"><tbody>';
    for (var y = 0, yl = ez.num(tableSizeGrid['rows'].el.value, 1, 'int'); y < yl; y++)
    {
        html += "<tr>";
        for (var x = 0, xl = ez.num(tableSizeGrid['cols'].el.value, 2, 'int'); x < xl; x++)
            html += '<td><br mce_bogus="1"/></td>';

        html += "</tr>";
    }
    return html + '</tbody></table>';
}

var specificAttributeGenerator = {
    'class': function( o, args ){
        return ez.string.trim( o.postData(true) + ( args['border'] == 0 ? ' mceItemTable' : ''));
    }
}, tableSizeGrid = {'rows': 0, 'cols': 0};

function tableSizeGridMouse( o, i, save )
{
    var cell = i +1, rows = Math.floor( i / 5) +1, cols = cell - (rows -1) * 5;

    tableSizeGridShowChange.call( this, rows, cols, save );

    if ( save )
    {
        tableSizeGrid['rows'].el.value = rows;
        tableSizeGrid['cols'].el.value = cols;
    }
}

function tableSizeGridInput( save )
{
    tableSizeGridShowChange.call( this, tableSizeGrid['rows'].el.value, tableSizeGrid['cols'].el.value, save );
}


function tableSizeGridShowChange( rows, cols, save )
{
    this.forEach(function(o2, i2){
        var cell2 = i2 +1, rows2 = Math.floor( i2 / 5) +1, cols2 = cell2 - (rows2 -1) * 5;
        if ( rows2 <= rows && cols2 <= cols )
        {
            if ( save )
                o2.el.style.backgroundColor = '#ccc';
            else
                o2.el.style.borderColor = '#000';
        }
        else
        {
            if ( save )
                o2.el.style.backgroundColor = '';
            else
                o2.el.style.borderColor = '';
        }
    });
}


// -->
</script>
<style>

#table_cell_size_grid { border-spacing: 1px; }
#table_cell_size_grid td { width: 12px; height: 12px; border: 1px solid #aaa; }

</style>
{/literal}

<div>

    <form onsubmit="return insertGeneralTag( this );" action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data"
    style="width: 360px;">
    

    <div class="slide" style="width: 360px;">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash}</h2>
        </div>
        
        <table class="properties" id="table_cell_size" summary="Click to select table size" style="display: none">
        <tr>
            <td class="column1">Size:</td>
            <td>
                <table id="table_cell_size_grid">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </table>
                {def $table_default_values = ezini( 'table', 'Defaults', 'content.ini' )}
                Cols: <input id="table_cell_size_grid_cols" type="number" maxlength="3" size="3" value="{first_set( $table_default_values['cols'], 2 )}" />
                Rows: <input id="table_cell_size_grid_rows" type="number" maxlength="2" size="3" value="{first_set( $table_default_values['rows'], 1 )}" />
            </td>
        </tr>
        </table>
        
        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name   = $tag_name
                 attributes = hash('width', '',
                                   'border', '',
                                   'class', $class_list
                                 )
        }

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