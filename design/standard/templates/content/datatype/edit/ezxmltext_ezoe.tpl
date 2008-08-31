{default input_handler=$attribute.content.input
         attribute_base='ContentObjectAttribute'
         editorRow=10}

{if gt($attribute.contentclass_attribute.data_int1,10)}
    {set editorRow=$attribute.contentclass_attribute.data_int1}
{/if}

{if $input_handler.is_editor_enabled}
<!-- Start editor -->

    {def $layout_settings = $input_handler.editor_layout_settings}

    {run-once}
    {* code that only run once (common for all xml blocks) *}

    {def $plugin_list = ezini('EditorSettings', 'Plugins', 'ezoe.ini',,true()  )
         $skin        = ezini('EditorSettings', 'Skin', 'ezoe.ini',,true() )
         $skin_variant = ezini('EditorSettings', 'SkinVariant', 'ezoe.ini',,true() )
         $content_css_list_temp = ezini('StylesheetSettings', 'EditorCSSFileList', 'design.ini',,true())
         $content_css_list = array()
         $editor_css_list  = array( concat('skins/', $skin, '/ui.css') )
         $language         = '-'|concat( ezini( 'RegionalSettings', 'Locale', 'site.ini') )
         $plugin_js_list   = array( 'ezoe::i18n::'|concat( $language ) )
    }

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
    <script id="tinymce_script_loader" type="text/javascript" src={"javascript/tiny_mce.js"|ezdesign}></script>
    {ezoescript( $plugin_js_list )}
    <!-- Init TinyMCE script -->
    <script type="text/javascript">
    <!--
    
    if ( window.ez === undefined ) document.write('<script type="text/javascript" src={"javascript/ezoe/ez_core.js"|ezdesign}><\/script>');

    var eZOeAttributeSettings, eZOeGlobalSettings = {ldelim}
        mode : "none",
        theme : "ez",
        width : '100%',
        language : '{$language}',
        skin : '{$skin}',
        skin_variant : '{$skin_variant}',
        plugins : "-{$plugin_list|implode(',-')}",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_blockformats : "p,pre,h1,h2,h3,h4,h5,h6",
        theme_advanced_path_location : "bottom",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
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
        theme_ez_editor_css : '{ezoecss( $editor_css_list, false() )|implode(',')}',
        theme_ez_content_css : '{ezoecss( $content_css_list, false())|implode(',')}',
        popup_css : {concat("stylesheets/skins/", $skin, "/dialog.css")|ezdesign},
        save_callback : "eZOeCleanUpEmbedTags",
        gecko_spellcheck : true,
        save_enablewhendirty : true,
        ez_root_url : {'/'|ezroot},
        ez_extension_url : {'/ezoe/'|ezurl},
        ez_js_url : {'/extension/ezoe/design/standard/javascript/'|ezroot},
        ez_contentobject_id : {$attribute.contentobject_id},
        ez_contentobject_version : {$attribute.version}
    {rdelim};
    
    {literal}

    (function(){
        var uri = document.getElementById('tinymce_script_loader').src;
        tinymce.ScriptLoader.markDone( uri.replace( 'tiny_mce', 'langs/' + eZOeGlobalSettings.language ) );
    }())

    tinyMCE.init( eZOeGlobalSettings );

    function eZOeToggleEditor( id, settings )
    {
        var el = document.getElementById( id );
        if ( el )
        {
            if ( tinyMCE.getInstanceById( id ) == null )
                //tinyMCE.execCommand('mceAddControl', false, id);
                new tinymce.Editor(id, settings).render();
            else
                tinyMCE.execCommand( 'mceRemoveControl', false, id );
        }
    }

    function eZOeCleanUpEmbedTags( element_id, html, body )
    {
    	// remove the content of the embed tags that are just there for oe preview
        // purpose, this is to avoid that the ez xml parsers in some cases 
        // duplicates the embed tag
        ez.array.forEach( body.getElementsByTagName('div'), function( node ){
            if ( node && node.className.indexOf('mceNonEditable') !== -1 )
                node.innerHTML = '';
        });
        ez.array.forEach( body.getElementsByTagName('span'), function( node ){
            if ( node && node.className.indexOf('mceNonEditable') !== -1 )
                node.innerHTML = '';
        });

        // fix anchor only urls on IE (it adds the current url before the anchor)
        ez.array.forEach( body.getElementsByTagName('a'), function( node ){
            if ( node && node.href.indexOf('#') > 0 )
            {
                var links = node.href.split('#'), loc = document.location.href;
                if ( (links[0].length >= loc.length - 7) && loc.indexOf( links[0] ) === 0 )
                    node.href = '#' + links[1];
            }
        });
        return body.innerHTML;
    }


    {/literal}
    //-->
    </script>
    {/run-once}
    
    
    
    <div class="oe-window">
        <textarea class="box" id="{$attribute_base}_data_text_{$attribute.id}" name="{$attribute_base}_data_text_{$attribute.id}" cols="88" rows="{$editorRow}">{$input_handler.input_xml}</textarea>
    </div>
    
    <div class="block">
        <input class="button{if $layout_settings['buttons']|contains('disable')} hide{/if}" type="submit" name="CustomActionButton[{$attribute.id}_disable_editor]" value="{'Disable editor'|i18n('design/standard/content/datatype')}" />
        <script type="text/javascript">
        <!--
        
        eZOeAttributeSettings = eZOeGlobalSettings;
        eZOeAttributeSettings['ez_attribute_id'] = {$attribute.id};
        eZOeAttributeSettings['theme_advanced_buttons1'] = "{$layout_settings['buttons']|implode(',')}";
        eZOeAttributeSettings['theme_advanced_path_location'] = "{$layout_settings['path_location']}";
        eZOeAttributeSettings['theme_advanced_statusbar_location'] = "{$layout_settings['path_location']}";
        eZOeAttributeSettings['theme_advanced_toolbar_location'] = "{$layout_settings['toolbar_location']}";

        eZOeToggleEditor( '{$attribute_base}_data_text_{$attribute.id}', eZOeAttributeSettings );

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
