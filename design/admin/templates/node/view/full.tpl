<div class="content-view-full">
 <div class="class-{$node.class_identifier}">

{include uri='design:infocollection_validation.tpl'}

<div class="content-navigation">

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

{def $js_class_languages = $node.object.content_class.prioritized_languages_js_array|explode( '"' )|implode( '\'' )
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

{* DESIGN: Header END *}</div>


{* DESIGN: Content START *}<div class="box-content">

<div class="context-information">
<p class="left modified">{'Last modified'|i18n( 'design/admin/node/view/full' )}: {$node.object.modified|l10n(shortdatetime)}, <a href={$node.object.current.creator.main_node.url_alias|ezurl}>{$node.object.current.creator.name|wash}</a> ({'Node ID'|i18n( 'design/admin/node/view/full' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/node/view/full' )}: {$node.object.id})</p>
<p class="right translation">{$node.object.current_language_object.locale_object.intl_language_name}&nbsp;<img src="{$node.object.current_language|flag_icon}" width="18" height="12" alt="{$language_code}" style="vertical-align: middle;" /></p>
<div class="break"></div>
</div>

<div id="window-controls" class="tab-block">

{include uri='design:window_controls.tpl'}

</div>

{* DESIGN: Content END *}</div>

{include uri='design:node/view/controlbar.tpl'}

</div>

{* Children window.*}
<div id="content-view-children">
{if $node.is_container}
    {include uri='design:children.tpl'}
{else}
    {include uri='design:no_children.tpl'}
{/if}
</div>

</div>

 </div>
</div>