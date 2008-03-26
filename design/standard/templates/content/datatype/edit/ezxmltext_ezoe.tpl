{default input_handler=$attribute.content.input
         attribute_base='ContentObjectAttribute'
         editorRow=10}

{if gt($attribute.contentclass_attribute.data_int1,10)}
    {set editorRow=$attribute.contentclass_attribute.data_int1}
{/if}

{if $input_handler.is_editor_enabled}
<!-- Start editor -->
    {run-once}

    {def $custom_tags = ezini('CustomTagSettings', 'AvailableCustomTags', 'content.ini',,true() )
         $button_list = ezini('EditorSettings', 'Buttons', 'ezoe.ini',,true()  )|implode(',')
         $plugin_list = ezini('EditorSettings', 'Plugins', 'ezoe.ini',,true()  )
         $skin        = ezini('EditorSettings', 'Skin', 'ezoe.ini',,true() )
         $skin_variant = ezini('EditorSettings', 'SkinVariant', 'ezoe.ini',,true() )
         $dev_mode    = ezini('EditorSettings', 'DevelopmentMode', 'ezoe.ini',,true()  )|eq('enabled')
         $content_css_list_temp = ezini('StylesheetSettings', 'EditorCSSFileList', 'design.ini',,true())
         $content_css_list = array()
         $editor_css_list = array( concat('skins/', $skin, '/ui.css') )
         $plugin_js_list     = array()
    }

    {if and( $custom_tags|contains('underline')|not, $button_list|contains(',underline') )}
        {set $button_list = $button_list|explode(',underline')|implode('')}
    {/if}

    {if and( $custom_tags|contains('pagebreak')|not, $button_list|contains(',pagebreak') )}
        {set $button_list = $button_list|explode(',pagebreak')|implode('')}
    {/if}
    
    {if $skin_variant}
        {set $editor_css_list = $editor_css_list|append( concat('skins/', $skin, '/ui_', $skin_variant, '.css') )}
    {/if}
    
    {foreach $content_css_list_temp as $css}
        {set $content_css_list = $content_css_list|append( $css|explode( '<skin>' )|implode( $skin ) )}
    {/foreach}

    {foreach $plugin_list as $plugin}
        {set $plugin_js_list = $plugin_js_list|append( concat( 'plugins/', $plugin, '/editor_plugin.js' ))}
    {/foreach}

    <!-- Load TinyMCE code -->
    <script type="text/javascript" src={"javascript/tiny_mce.js"|ezdesign}></script>
    {ezoescript( $plugin_js_list )}
    <!-- Init TinyMCE script -->
    <script type="text/javascript">
    <!--
    
    if ( window.ez === undefined ) document.write('<script type="text/javascript" src={"javascript/ezoe/ez_core.js"|ezdesign}><\/script>');
    
    var eZOeMCE = new Object(), ezTinyIdString;
    eZOeMCE['root']             = {'/'|ezroot};
    eZOeMCE['extension_url']    = {'/ezoe/'|ezurl};
    eZOeMCE['content_css']      = '{ezoecss( $content_css_list, false() )|implode(',')}';
    eZOeMCE['editor_css']       = '{ezoecss( $editor_css_list, false() )|implode(',')}';
    eZOeMCE['popup_css']        = {concat("stylesheets/skins/", $skin, "/dialog.css")|ezdesign};
    eZOeMCE['contentobject_id'] = {$attribute.contentobject_id};
    eZOeMCE['contentobject_version'] = {$attribute.version};
    eZOeMCE['plugins']       = "-{$plugin_list|implode(',-')}";
    eZOeMCE['buttons']       = "{$button_list}";
    eZOeMCE['skin']          = '{$skin}';
    eZOeMCE['skin_variant']  = '{$skin_variant}';
    eZOeMCE['i18n']          = {ldelim}
h1: "{'Heading 1'|i18n('design/standard/ezoe')}",
h2: "{'Heading 2'|i18n('design/standard/ezoe')}",
h3: "{'Heading 3'|i18n('design/standard/ezoe')}",
h4: "{'Heading 4'|i18n('design/standard/ezoe')}",
h5: "{'Heading 5'|i18n('design/standard/ezoe')}",
h6: "{'Heading 6'|i18n('design/standard/ezoe')}",
normal: "{'Normal'|i18n('design/standard/ezoe')}",
literal: "{'Literal'|i18n('design/standard/ezoe')}",
bold: "{'Bold'|i18n('design/standard/ezoe')}",
italic: "{'Italic'|i18n('design/standard/ezoe')}",
underline: "{'Underline'|i18n('design/standard/ezoe')}",
numbedred_list: "{'Numbered list'|i18n('design/standard/ezoe')}",
bullet_list: "{'Bullet list'|i18n('design/standard/ezoe')}",
outdent: "{'Increase list indent'|i18n('design/standard/ezoe')}",
indent: "{'Decrease list indent'|i18n('design/standard/ezoe')}",
insert_literal: "{'Insert literal text'|i18n('design/standard/ezoe')}",
insert_special: "{'Insert special character'|i18n('design/standard/ezoe')}",
insert_anchor: "{'Insert anchor'|i18n('design/standard/ezoe')}",
insert_custom: "{'Insert custom tag'|i18n('design/standard/ezoe')}",
insert_object: "{'Insert object'|i18n('design/standard/ezoe')}",
insert_image: "{'Insert image'|i18n('design/standard/ezoe')}",
insert_pagebreak: "{'Insert pagebreak'|i18n('design/standard/ezoe')}",
make_link: "{'Make link'|i18n('design/standard/ezoe')}",
remove_link: "{'Remove link'|i18n('design/standard/ezoe')}",
redo: "{'Redo'|i18n('design/standard/ezoe')}",
undo: "{'Undo'|i18n('design/standard/ezoe')}",
type: "{'Type'|i18n('design/standard/ezoe')}",

insert_table: "{'Insert table'|i18n('design/standard/ezoe')}",
delete_table: "{'Delete table'|i18n('design/standard/ezoe')}",
insert_row: "{'Insert row'|i18n('design/standard/ezoe')}",
insert_column: "{'Insert column'|i18n('design/standard/ezoe')}",
delete_column: "{'Delete column'|i18n('design/standard/ezoe')}",
delete_row: "{'Delete row'|i18n('design/standard/ezoe')}",
merge_cells: "{'Merge Cells'|i18n('design/standard/ezoe')}",
split_cell: "{'Split Cell'|i18n('design/standard/ezoe')}",

path: "{'Path'|i18n('design/standard/setup')}"
    {rdelim};

    {literal}

    tinyMCE.init({
    	mode : "none",
    	theme : "ez",
    	width : '100%',
    	skin : eZOeMCE['skin'],
    	skin_variant : eZOeMCE['skin_variant'],
    	plugins : eZOeMCE['plugins'],
    	theme_advanced_buttons1 : eZOeMCE['buttons'],
    	theme_advanced_buttons2 : "",
    	theme_advanced_buttons3 : "",
    	theme_advanced_blockformats : "p,pre,h1,h2,h3,h4,h5,h6",
    	theme_advanced_path_location : "bottom",
    	theme_advanced_statusbar_location: "bottom",
    	theme_advanced_toolbar_location : "top",
    	theme_advanced_toolbar_align : 'left',
        theme_advanced_toolbar_floating : true,
    	theme_advanced_resize_horizontal : false,
    	theme_advanced_resizing : true,
    	valid_elements: "-strong/-b/-bold[class],-em/-i/-emphasize[class],pre[class|title|customattributes],ol[class],ul[class],li,a/link[href|target=_blank|title|class|id],p/paragraph[class],anchor[name],img[src|class|alt|align|inline|id|customattributes],table[class|border|width|id|title|customattributes|ezborder|bordercolor],tr,th[class|width|rowspan|colspan],td[class|width|rowspan|colspan],h1,h2,h3,h4,h5,h6,br",
    	valid_child_elements: "h1/h2/h3/h4/h5/h6/a/link[%itrans_na],table[tr],tr[td|th],pre/strong/b/p/div/em/i/td/th[%itrans|#text]",
    	cleanup : false,
    	cleanup_serializer : 'xml',	
    	entity_encoding : 'raw',
    	remove_linebreaks : false,
    	apply_source_formatting : false,
    	fix_list_elements : true,
    	fix_table_elements : true,
    	tab_focus : ':prev,:next',
    	theme_advanced_editor_css : eZOeMCE['editor_css'],
    	theme_advanced_content_css : eZOeMCE['content_css'],
    	popup_css : eZOeMCE['popup_css'],
    	gecko_spellcheck : true
    });
    
    function ezMceToggleEditor( id )
    {
        var el = document.getElementById( id );
    	if ( el )
    	{
    		if ( tinyMCE.getInstanceById(id) == null )
    			tinyMCE.execCommand('mceAddControl', false, id);
    		else
    			tinyMCE.execCommand('mceRemoveControl', false, id);
    	}
    }
    
    {/literal}
    
    //-->
    </script>
    {/run-once}
    
    <div class="oe-window">
        <textarea class="box" id="{$attribute_base}_data_text_{$attribute.id}" name="{$attribute_base}_data_text_{$attribute.id}" cols="88" rows="{$editorRow}">{$input_handler.input_xml}</textarea>
    </div>
    
    <div class="block">
        <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_disable_editor]" value="{'Disable editor'|i18n('design/standard/content/datatype')}" />
        <script type="text/javascript">
        <!--
        
        ezTinyIdString = '{$attribute_base}_data_text_{$attribute.id}';
        // comment out this if you don't want the editor to toggle on by default
        ezMceToggleEditor( ezTinyIdString );
        
        {if $dev_mode}
        document.write(' &nbsp; <a href="JavaScript:ezMceToggleEditor(\'' + ezTinyIdString + '\');">Toggle editor<\/a>');
        {/if}
        
        -->
        </script>
    </div>
<!-- End editor -->
{else}
    {let aliased_handler=$input_handler.aliased_handler}
    {include uri=concat("design:content/datatype/edit/",$aliased_handler.edit_template_name,".tpl") input_handler=$aliased_handler}
    <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_enable_editor]" value="{'Enable editor'|i18n('design/standard/content/datatype')}" /><br />
    {/let}
{/if}
{/default}
