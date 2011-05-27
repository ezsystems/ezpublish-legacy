{default input_handler=$attribute.content.input
         attribute_base='ContentObjectAttribute'
         editorRow=10}

{if gt($attribute.contentclass_attribute.data_int1,1)}
    {set editorRow=$attribute.contentclass_attribute.data_int1}
{/if}

{if $input_handler.is_editor_enabled}
<!-- Start editor -->

    {def $layout_settings = $input_handler.editor_layout_settings}

    {run-once}
    {* code that only run once (common for all xml blocks) *}

    {def $plugin_list = ezini('EditorSettings', 'Plugins', 'ezoe.ini',,true() )
         $skin        = ezini('EditorSettings', 'Skin', 'ezoe.ini',,true() )
         $skin_variant = ''
         $content_css_list_temp = ezini('StylesheetSettings', 'EditorCSSFileList', 'design.ini',,true())
         $content_css_list = array()
         $editor_css_list  = array( concat('skins/', $skin, '/ui.css') )
         $ez_locale        = ezini( 'RegionalSettings', 'Locale', 'site.ini')
         $language         = '-'|concat( $ez_locale )
         $dependency_js_list   = array( 'ezoe::i18n::'|concat( $language ) )
         $spell_languages = '+English=en'
         $directionality    = 'ltr'
         $toolbar_alignment = 'left'
    }
    {if ezini_hasvariable( 'EditorSettings', 'SkinVariant', 'ezoe.ini',,true() )}
        {set $skin_variant = ezini('EditorSettings', 'SkinVariant', 'ezoe.ini',,true() )}
    {/if}
    {if ezini_hasvariable( 'EditorSettings', 'Directionality', 'ezoe.ini',,true() )}
        {set $directionality = ezini('EditorSettings', 'Directionality', 'ezoe.ini',,true() )}
    {/if}
    {if ezini_hasvariable( 'EditorSettings', 'ToolbarAlign', 'ezoe.ini',,true() )}
        {set $toolbar_alignment = ezini('EditorSettings', 'ToolbarAlign', 'ezoe.ini',,true() )}
    {/if}
    {if $attribute.language_code|eq( $ez_locale )}
        {def $cur_locale = fetch( 'content', 'locale' )}
        {set $spell_languages = concat( '+', $cur_locale.intl_language_name, '=', $cur_locale.http_locale_code|explode('-')[0])}
        {undef $cur_locale}
    {else}
        {def $cur_locale = fetch( 'content', 'locale' )
             $atr_locale = fetch( 'content', 'locale', hash( 'locale_code', $attribute.language_code ) )}
        {set $spell_languages = concat( '+', $atr_locale.intl_language_name, '=', $atr_locale.http_locale_code|explode('-')[0])}
        {set $spell_languages = concat( $spell_languages, ',', $cur_locale.intl_language_name, '=', $cur_locale.http_locale_code|explode('-')[0])}
        {undef $cur_locale $atr_locale}
    {/if}

    {if $skin_variant}
        {set $editor_css_list = $editor_css_list|append( concat('skins/', $skin, '/ui_', $skin_variant, '.css') )}
    {/if}

    {foreach $content_css_list_temp as $css}
        {set $content_css_list = $content_css_list|append( $css|explode( '<skin>' )|implode( $skin ) )}
    {/foreach}

    {foreach $plugin_list as $plugin}
        {set $dependency_js_list = $dependency_js_list|append( concat( 'plugins/', $plugin|trim, '/editor_plugin.js' ))}
    {/foreach}

    <!-- Load TinyMCE code -->
    {ezscript_require( 'ezjsc::jquery' )}
    <script id="tinymce_script_loader" type="text/javascript" src={"javascript/tiny_mce_jquery.js"|ezdesign} charset="utf-8"></script>
    {ezscript( $dependency_js_list )}
    <!-- Init TinyMCE script -->
    <script type="text/javascript">
    <!--

    var eZOeAttributeSettings, eZOeGlobalSettings = {ldelim}
        mode : "none",
        theme : "ez",
        width : '100%',
        language : '{$language}',
        skin : '{$skin}',
        skin_variant : '{$skin_variant}',
        plugins : "-{$plugin_list|implode(',-')}",
        directionality : '{$directionality}',
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_blockformats : "p,pre,h1,h2,h3,h4,h5,h6",// removes address tag, not suppored by ezxml
        theme_advanced_path_location : false,// ignore, use theme_advanced_statusbar_location
        theme_advanced_statusbar_location : "bottom",// correct value set by layout code bellow pr attribute
        theme_advanced_toolbar_location : "top",// correct value set by layout code bellow pr attribute
        theme_advanced_toolbar_align : "{$toolbar_alignment}",
        theme_advanced_toolbar_floating : true,
        theme_advanced_resize_horizontal : false,
        theme_advanced_resizing : true,
        valid_elements : "-strong/-b/-bold[class|customattributes],-em/-i/-emphasize[class|customattributes],span[id|type|class|title|customattributes|align|style|view|inline|alt],sub[class|type|customattributes|align],sup[class|type|customattributes|align],u[class|type|customattributes|align],pre[class|title|customattributes],ol[class|customattributes],ul[class|customattributes],li[class|customattributes],a[href|name|target|view|title|class|id|customattributes],p[class|customattributes|align|style],img[id|type|class|title|customattributes|align|style|view|inline|alt|src],table[class|border|width|id|title|customattributes|ezborder|bordercolor|align|style],tr,th[class|width|rowspan|colspan|customattributes|align|style],td[class|width|rowspan|colspan|customattributes|align|style],div[id|type|class|title|customattributes|align|style|view|inline|alt],h1[class|customattributes|align|style],h2[class|customattributes|align|style],h3[class|customattributes|align|style],h4[class|customattributes|align|style],h5[class|customattributes|align|style],h6[class|customattributes|align|style],br",
        valid_child_elements : "a[%itrans_na],table[tr],tr[td|th],ol/ul[li],h1/h2/h3/h4/h5/h6/pre/strong/b/p/em/i/u/span/sub/sup/li[%itrans|#text]div/pre/td/th[%btrans|%itrans|#text]",
        // cleanup : false,
        // cleanup_serializer : 'xml',
        // entity_encoding : 'raw',
        entities : '160,nbsp', // We need to transform nonbreaking white space to encoded form, all other charthers as stored in raw unicode form.
        // remove_linebreaks : false,
        // apply_source_formatting : false,
        fix_list_elements : true,
        fix_table_elements : true,
        convert_urls : false,
        inline_styles : false,
        tab_focus : ':prev,:next',
        theme_ez_xml_alias_list : {$input_handler.json_xml_tag_alias},
        theme_ez_editor_css : '{ezcssfiles( $editor_css_list, 3, true() )|implode(',')}',
        theme_ez_content_css : '{ezcssfiles( $content_css_list, 3, true() )|implode(',')}',
        theme_ez_statusbar_open_dialog : {cond( ezini('EditorSettings', 'TagPathOpenDialog', 'ezoe.ini',,true())|eq('enabled'), 'true', 'false' )},
        popup_css : {concat("stylesheets/skins/", $skin, "/dialog.css")|ezdesign},
        //popup_css_add : {"stylesheets/core.css"|ezdesign},
        gecko_spellcheck : true,
        object_resizing : false,//disable firefox inline image/table resizing
        table_inline_editing : true, // table edit controlls in gecko
        save_enablewhendirty : true,
        ez_root_url : {'/'|ezroot},
        ez_extension_url : {'/ezoe/'|ezurl},
        ez_js_url : {'/extension/ezoe/design/standard/javascript/'|ezroot},
        /* Used by language pack / plugin url fixer bellow, do not change */
        ez_tinymce_url : {'javascript/tiny_mce.js'|ezdesign},
        ez_contentobject_id : {$attribute.contentobject_id},
        ez_contentobject_version : {$attribute.version},
        spellchecker_rpc_url : {'/ezoe/spellcheck_rpc'|ezurl},
        spellchecker_languages : '{$spell_languages}',
        atd_rpc_url : {'/ezoe/atd_rpc?url='|ezurl},
        atd_rpc_id  : "your API key here",
        /* this list contains the categories of errors we want to show */
        atd_show_types              : "Bias Language,Cliches,Complex Expression,Diacritical Marks,Double Negatives,Hidden Verbs,Jargon Language,Passive voice,Phrases to Avoid,Redundant Expression",
        /* strings this plugin should ignore */
        atd_ignore_strings          : "AtD,rsmudge",
        /* enable "Ignore Always" menu item, uses cookies by default. Set atd_ignore_rpc_url to a URL AtD should send ignore requests to. */
        atd_ignore_enable           : "true",
        /* the URL to the button image to display */
        //atd_button_url              : "atdbuttontr.gif",
        atd_css_url : {'javascript/plugins/AtD/css/content.css'|ezdesign},
        paste_preprocess : function(pl, o) {ldelim}
            // Strip <a> HTML tags from clipboard content (Happens on Internet Explorer)
            o.content = o.content.replace( /(\s[a-z]+=")<a\s[^>]+>([^<]+)<\/a>/gi, '$1$2' );
        {rdelim}

    {rdelim};

    {literal}

    // make sure TinyMCE doesn't try to load language pack
    // and set urls for plugins so their dialogs work correctly
    (function(){
        var uri = document.location.protocol + '//' + document.location.host + eZOeGlobalSettings.ez_tinymce_url, tps = eZOeGlobalSettings.plugins.split(','), pm = tinymce.PluginManager, tp;
        tinymce.ScriptLoader.markDone( uri.replace( 'tiny_mce', 'langs/' + eZOeGlobalSettings.language ) );
        for (var i = 0, l = tps.length; i < l; i++)
        {
            tp = tps[i].slice(1);
            pm.urls[ tp ] = uri.replace( 'tiny_mce.js', 'plugins/' + tp );
        }
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

    {/literal}
    //-->
    </script>
    {/run-once}



    <div class="oe-window">
        <textarea class="box" id="{$attribute_base}_data_text_{$attribute.id}" name="{$attribute_base}_data_text_{$attribute.id}" cols="88" rows="{$editorRow}">{$input_handler.input_xml}</textarea>
    </div>

    <div class="block">
        {if $input_handler.can_disable}
            <input class="button{if $layout_settings['buttons']|contains('disable')} hide{/if}" type="submit" name="CustomActionButton[{$attribute.id}_disable_editor]" value="{'Disable editor'|i18n('design/standard/content/datatype')}" />
        {/if}
        <script type="text/javascript">
        <!--

        eZOeAttributeSettings = eZOeGlobalSettings;
        eZOeAttributeSettings['ez_attribute_id'] = {$attribute.id};
        eZOeAttributeSettings['theme_advanced_buttons1'] = "{$layout_settings['buttons']|implode(',')}";
        eZOeAttributeSettings['theme_advanced_statusbar_location'] = "{$layout_settings['path_location']}";
        eZOeAttributeSettings['theme_advanced_toolbar_location'] = "{$layout_settings['toolbar_location']}";

        eZOeToggleEditor( '{$attribute_base}_data_text_{$attribute.id}', eZOeAttributeSettings );

        -->
        </script>
    </div>
<!-- End editor -->
{else}
    {* Require jQuery even when disabled to make sure user don't get cache issues when they enable editor *}
    {ezscript_require( 'ezjsc::jquery' )}
    {let aliased_handler=$input_handler.aliased_handler}
    {include uri=concat("design:content/datatype/edit/",$aliased_handler.edit_template_name,".tpl") input_handler=$aliased_handler}
    <input class="button" type="submit" name="CustomActionButton[{$attribute.id}_enable_editor]" value="{'Enable editor'|i18n('design/standard/content/datatype')}" /><br />
    {/let}
{/if}
{/default}
