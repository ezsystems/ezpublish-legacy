{include uri='design:infocollection_validation.tpl'}
{include uri='design:window_controls.tpl'}

<div class="content-navigation">

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{def $js_class_languages = $node.object.content_class.prioritized_languages_js_array
     $disable_another_language = cond( eq( 0, count( $node.object.content_class.can_create_languages ) ),"'edit-class-another-language'", '-1' )
     $disabled_sub_menu = "['class-createnodefeed', 'class-removenodefeed']"
     $hide_status = ''}

{if $node.is_invisible}
    {set $hide_status = concat( '(', $node.hidden_status_string, ')' )}
{/if}

{* Check if user has rights and if there are any RSS/ATOM Feed exports for current node *}
{if is_set( ezini( 'RSSSettings', 'DefaultFeedItemClasses', 'site.ini' )[ $node.class_identifier ] )}
    {def $create_rss_access = fetch( 'user', 'has_access_to', hash( 'module', 'rss', 'function', 'edit' ) )}
    {if $create_rss_access}
        {if fetch( 'rss', 'has_export_by_node', hash( 'node_id', $node.node_id ) )}
            {set $disabled_sub_menu = "'class-createnodefeed'"}
        {else}
            {set $disabled_sub_menu = "'class-removenodefeed'"}
        {/if}
    {/if}
{/if}

<h1 class="context-title"><a href={concat( '/class/view/', $node.object.contentclass_id )|ezurl} onclick="ezpopmenu_showTopLevel( event, 'ClassMenu', ez_createAArray( new Array( '%classID%', {$node.object.contentclass_id}, '%objectID%', {$node.contentobject_id}, '%nodeID%', {$node.node_id}, '%currentURL%', '{$node.url|wash( javascript )}', '%languages%', {$js_class_languages} ) ), '{$node.class_name|wash(javascript)}', {$disabled_sub_menu}, {$disable_another_language} ); return false;">{$node.class_identifier|class_icon( normal, $node.class_name )}</a>&nbsp;{$node.name|wash}&nbsp;[{$node.class_name|wash}]&nbsp;{$hide_status}</h1>

{undef $js_class_languages $disable_another_language $disabled_sub_menu $hide_status}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

<form method="post" action={'content/action'|ezurl}>
<div class="box-ml"><div class="box-mr">

<div class="context-information">
<p class="modified">{'Last modified'|i18n( 'design/admin/node/view/full' )}: {$node.object.modified|l10n(shortdatetime)}, <a href={$node.object.current.creator.main_node.url_alias|ezurl}>{$node.object.current.creator.name|wash}</a></p>
<p class="translation">{$node.object.current_language_object.locale_object.intl_language_name}&nbsp;<img src="{$node.object.current_language|flag_icon}" alt="{$language_code}" style="vertical-align: middle;" /></p>
<div class="break"></div>
</div>

{* Content preview in content window. *}
{if ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-window" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/node/view/full' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/node/view/full' )}: {$node.object.id}">
<div class="fixedsize">{* Fix for overflow bug in Opera *}
<div class="holdinplace">{* Fix for some width bugs in IE *}
    {node_view_gui content_node=$node view=admin_preview}
</div>
</div>
<div class="break"></div>{* Terminate overflow bug fix *}
</div>
{/if}

</div></div>

{* Buttonbar for content window. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />

<div class="block">

<div class="left">

{* Edit button. *}
{def $can_create_languages = $node.object.can_create_languages
     $languages            = fetch( 'content', 'prioritized_languages' )}
{if $node.can_edit}
    {if and(eq( $languages|count, 1 ), is_set( $languages[0] ) )}
            <input name="ContentObjectLanguageCode" value="{$languages[0].locale}" type="hidden" />
    {else}
            <select name="ContentObjectLanguageCode">
            {foreach $node.object.can_edit_languages as $language}
                       <option value="{$language.locale}"{if $language.locale|eq($node.object.current_language)} selected="selected"{/if}>{$language.name|wash}</option>
            {/foreach}
            {if gt( $can_create_languages|count, 0 )}
                <option value="">{'Another language'|i18n( 'design/admin/node/view/full')}</option>
            {/if}
            </select>
    {/if}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit the contents of this item.'|i18n( 'design/admin/node/view/full' )}" />
{else}
    <select name="ContentObjectLanguageCode" disabled="disabled">
        <option value="">{'Not available'|i18n( 'design/admin/node/view/full')}</option>
    </select>
    <input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/if}
{undef $can_create_languages}

{* Move button. *}
{if $node.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move this item to another location.'|i18n( 'design/admin/node/view/full' )}" />
{else}
    <input class="button-disabled" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to move this item to another location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/if}

{* Remove button. *}
{if $node.can_remove}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
{else}
    <input class="button-disabled" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/if}


<span class="vertical-seperator">&nbsp;</span>

{* The "Create new here" thing: *}
{if $node.can_create}
    <input type="hidden" name="NodeID" value="{$node.node_id}" />
    {if $node.path_array|contains( ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
        {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'group_id', ezini( 'ClassGroupIDs', 'Content', 'content.ini' ), 'parent_node', $node ) )}
    {elseif $node.path_array|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
        {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'group_id', ezini( 'ClassGroupIDs', 'Media', 'content.ini' ), 'parent_node', $node ) )}
    {elseif $node.path_array|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
        {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'group_id', ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), 'parent_node', $node ) )}
    {elseif $node.path_array|contains( ezini( 'NodeSettings', 'SetupRootNode', 'content.ini' ) )}
        {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'group_id', ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ), 'parent_node', $node ) )}
    {else}
        {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'group_id', array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) ), 'parent_node', $node, 'filter_type', 'exclude' ) )}
    {/if}

    {def $can_create_languages = fetch( 'content', 'prioritized_languages' )}

    {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
        <select id="ClassID" name="ClassID" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
    {else}
        <select id="ClassID" name="ClassID" onchange="updateLanguageSelector(this)" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
    {/if}
        {foreach $can_create_classes as $can_create_class}
        {if $can_create_class.can_instantiate_languages}
            <option value="{$can_create_class.id}">{$can_create_class.name|wash()}</option>
        {/if}
        {/foreach}
    </select>

    {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
        <input name="ContentLanguageCode" value="{$can_create_languages[0].locale}" type="hidden" />
    {else}
        <select name="ContentLanguageCode" onchange="checkLanguageSelector(this)" title="{'Use this menu to select the language you want to use for the creation then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
            {foreach $can_create_languages as $tmp_language}
                <option value="{$tmp_language.locale|wash()}">{$tmp_language.name|wash()}</option>
            {/foreach}
       </select>
    {/if}

    <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/admin/node/view/full' )}" title="{'Create a new item in the current location. Use the menu on the left to select the type of  item.'|i18n( 'design/admin/node/view/full' )}" />
    <input type="hidden" name="ViewMode" value="full" />

    {if ne( $can_create_languages|count, 1 )}
    <script type="text/javascript">
    <!--
        {literal}
        function updateLanguageSelector( classSelector )
        {
            var languageSelector = classSelector.form.ContentLanguageCode;
            if ( !languageSelector )
            {
                return;
            }

            var classID = classSelector.value, languages = languagesByClassID[classID], candidateIndex = -1;

            for ( var index = 0, length = languageSelector.options.length; index < length; index++ )
            {
                var value = languageSelector.options[index].value, disabled = true;

                for ( var indexj = 0, lengthj = languages.length; indexj < lengthj; indexj++ )
                {
                    if ( languages[indexj] == value )
                    {
                        disabled = false;
                        break;
                    }
                }

                if ( !disabled && candidateIndex == -1 )
                {
                    candidateIndex = index;
                }

                languageSelector.options[index].disabled = disabled;
                if ( disabled )
                {
                    languageSelector.options[index].style.color = '#888888';
                    if ( languageSelector.options[index].text.substring( 0, 1 ) != '(' )
                    {
                        languageSelector.options[index].text = '(' + languageSelector.options[index].text + ')';
                    }
                }
                else
                {
                    languageSelector.options[index].style.color = '#000000';
                    if ( languageSelector.options[index].text.substring( 0, 1 ) == '(' )
                    {
                        languageSelector.options[index].text = languageSelector.options[index].text.substring( 1, languageSelector.options[index].text.length - 1 );
                    }
                }
            }

            if ( languageSelector.options[languageSelector.selectedIndex].disabled )
            {
                window.languageSelectorIndex = candidateIndex;
                languageSelector.selectedIndex = candidateIndex;
            }
        }

        function checkLanguageSelector( languageSelector )
        {
            if ( languageSelector.options[languageSelector.selectedIndex].disabled )
            {
                languageSelector.selectedIndex = window.languageSelectorIndex;
                return;
            }
            window.languageSelectorIndex = languageSelector.selectedIndex;
        }

        setTimeout( function() { updateLanguageSelector( document.getElementById( 'ClassID' ) ); }, 100 );

        var languagesByClassID = {};
        {/literal}

        {foreach $can_create_classes as $class}
        languagesByClassID[{$class.id}] = [ {foreach $class.can_instantiate_languages as $tmp_language}'{$tmp_language}'{delimiter}, {/delimiter} {/foreach} ];
        {/foreach}
    // -->
    </script>
    {/if}
    {undef $can_create_languages $can_create_classes}
{else}
    <select id="ClassID" name="ClassID" disabled="disabled">
    <option value="">{'Not available'|i18n( 'design/admin/node/view/full' )}</option>
    </select>
    <input class="button-disabled" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to create new items in the current location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/if}


</div>

<div class="right">

    {* Link to manage versions *}
    <a href={concat("content/history/", $node.contentobject_id )|ezurl} title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/edit' )}">{'Manage versions'|i18n( 'design/admin/content/edit' )}</a>
    
    {* Custom content action buttons. *}
    {section var=ContentActions loop=$node.object.content_action_list}
        <input class="button" type="submit" name="{$ContentActions.item.action}" value="{$ContentActions.item.name}" />
    {/section}
</div>

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/node/view/full')}" /> *}

<div class="break"></div>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</form>

</div>

{include uri="design:windows.tpl"}

</div>
