{include uri="design:window_controls.tpl"}

{* Content window. *}
<div class="context-block">
<h2 class="context-title">{$node.class_identifier|class_icon( normal, $node.class_name )} {$node.name} [{$node.class_name}]</h2>

<div class="context-information">
<p>{'Last modified:'|i18n( 'design/admin/node/view/full' )} {$node.object.modified|l10n(shortdatetime)}, {$node.object.current.creator.name}</p>
</div>

{* Content preview in content window. *}
{section show=ezpreference( 'admin_navigation_content'  )}
<div class="mainobject-vindow" title="{$node.name|wash} {'Node ID'|i18n( 'design/admin/node/view/full' )}: {$node.node_id}, {'Object ID'|i18n( 'design/admin/node/view/full' )}: {$node.object.id}">
    {node_view_gui content_node=$node view=admin_preview}
</div>
{/section}

{* Buttonbar for content window. *}
<div class="controlbar">
<div class="block">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

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
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/node/view/full')}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
{section-else}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/admin/node/view/full')}" title="{'You do not have permissions to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/section}

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/admin/node/view/full')}" /> *}

</form>
</div>
</div>

</div>


{include uri="design:windows.tpl"}


