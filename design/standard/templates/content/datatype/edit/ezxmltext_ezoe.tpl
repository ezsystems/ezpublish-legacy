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
         $dev_mode     = ezini('EditorSettings', 'DevelopmentMode', 'ezoe.ini',,true()  )|eq('enabled')
         $content_css_list_temp = ezini('StylesheetSettings', 'EditorCSSFileList', 'design.ini',,true())
         $content_css_list = array()
         $editor_css_list  = array( concat('skins/', $skin, '/ui.css') )
         $language         = '-'|concat( $attribute.language_code )
         $plugin_js_list   = array( 'ezoe::i18n::'|concat( $language ) )
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
    eZOeMCE['content_css']      = '{ezoecss( $content_css_list, false())|implode(',')}';
    eZOeMCE['editor_css']       = '{ezoecss( $editor_css_list, false() )|implode(',')}';
    eZOeMCE['popup_css']        = {concat("stylesheets/skins/", $skin, "/dialog.css")|ezdesign};
    eZOeMCE['contentobject_id'] = {$attribute.contentobject_id};
    eZOeMCE['contentobject_version'] = {$attribute.version};
    eZOeMCE['plugins']       = "-{$plugin_list|implode(',-')}";
    eZOeMCE['buttons']       = "{$button_list}";
    eZOeMCE['skin']          = '{$skin}';
    eZOeMCE['skin_variant']  = '{$skin_variant}';
    eZOeMCE['language']      = '{$language}';
    eZOeMCE['dev_mode']      = {$dev_mode|cond( 'true', 'false' )};

    {literal}

    tinyMCE.init({
    	mode : "none",
    	theme : "ez",
    	width : '100%',
    	language : eZOeMCE['language'],
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
    	valid_elements: "-strong/-b/-bold[class|customattributes],-em/-i/-emphasize[class|customattributes],span[type|class|title|customattributes],pre[class|title|customattributes],ol[class|customattributes],ul[class|customattributes],li,a[href|name|target|title|class|id|customattributes],p[class|customattributes],img[src|class|alt|align|inline|id|customattributes],table[class|border|width|id|title|customattributes|ezborder|bordercolor],tr,th[class|width|rowspan|colspan|customattributes],td[class|width|rowspan|colspan|customattributes],h1,h2,h3,h4,h5,h6,br",
    	valid_child_elements: "a[%itrans_na],table[tr],tr[td|th],h1/h2/h3/h4/h5/h6/pre/strong/b/p/em/i/u[%itrans|#text]span/div/pre/td/th[%btrans|%itrans|#text]",
    	cleanup : false,
    	cleanup_serializer : 'xml',	
    	entity_encoding : 'raw',
    	remove_linebreaks : false,
    	apply_source_formatting : false,
    	fix_list_elements : true,
    	fix_table_elements : true,
    	tab_focus : ':prev,:next',
    	theme_ez_editor_css : eZOeMCE['editor_css'],
    	theme_ez_content_css : eZOeMCE['content_css'],
    	popup_css : eZOeMCE['popup_css'],
    	gecko_spellcheck : true,
    	save_callback : "ezMceEditorSave"
    });
        

    function ezMceEditorSave(element_id, html, body)
    {
        ez.$$( 'span.mceItemCustomTag', body ).forEach(function(o){
            if ( o.el.hasChildNodes() && o.el.childNodes.length === 1 
              && o.el.childNodes[0].nodeName === 'P' )
            {
                while ( o.el.childNodes[0].childNodes.length )
                    o.el.appendChild( o.el.childNodes[0].childNodes[0] );
                o.el.removeChild( o.el.childNodes[0] );
            }
        });
        return body.innerHTML;
    }

    
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
        
        if ( eZOeMCE['dev_mode'] )
            document.write(' &nbsp; <a href="JavaScript:ezMceToggleEditor(\'' + ezTinyIdString + '\');">Toggle editor<\/a>');
        
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
