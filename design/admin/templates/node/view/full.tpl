{include uri="design:window_controls.tpl"}

{* Content window. *}
<div class="context-block">
<h2 class="context-title"><a href={concat( '/class/view/', $node.object.contentclass_id )|ezurl} onclick="ezpopmenu_showTopLevel( 'ClassMenu', ez_createAArray( new Array( '%classID%', {$node.object.contentclass_id}) ), '{$node.class_name}', -1 ); return false;">{$node.class_identifier|class_icon( normal, $node.class_name )}</a> {$node.name|wash} [{$node.class_name|wash}]</h2>

<div class="context-information">
<p>{'Last modified'|i18n( 'design/admin/node/view/full' )}: {$node.object.modified|l10n(shortdatetime)}, <a href={$node.object.current.creator.main_node.url_alias|ezurl}>{$node.object.current.creator.name}</a></p>
</div>

{* Content preview in content window. *}
{section show=ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-vindow" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/node/view/full' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/node/view/full' )}: {$node.object.id}">
    {node_view_gui content_node=$node view=admin_preview}
</div>
{/section}

{* Buttonbar for content window. *}
<div class="controlbar">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

<div class="block">

<div class="left">
{* Edit button. *}
{section show=$node.can_edit}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* Move button. *}
{section show=$node.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move this item to another location.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to move this item to another location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* Remove button. *}
{section show=$node.can_remove}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}
</div>

{* Custom content action buttons. *}
<div class="right">
{section var=ContentActions loop=$node.object.content_action_list}
    <input class="button" type="submit" name="{$ContentActions.item.action}" value="{$ContentActions.item.name}" />
{/section}
</div>

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/node/view/full')}" /> *}

<div class="break"></div>

</div>
</form>
</div>

</div>

{include uri="design:windows.tpl"}


