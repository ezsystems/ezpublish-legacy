<div class="controlbar">
<div class="block">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

{section show=$node.object.can_edit}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'Click here to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'You do not have permissions to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )} "disabled="disabled" />
{/section}

{section show=$node.object.can_move}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/standard/node/view' )}" title="{'Click here to move the item displayed above to a new location.'|i18n( 'design/admin/layout' )}" />
{section-else}
    <input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/standard/node/view' )}" title="{'You do not have permissions to move the item that is being displayed above.'|i18n( 'design/admin/layout' )} "disabled="disabled" />
{/section}

    {* Allow remove button if this is not a root node and if the user is allowed to remove it: *}
{section show=$node.object.can_remove|not}
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'You do not have permissions to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}"disabled="disabled" />
{section-else}
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'Click here to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{/section}

{* The preview button has been commented out. Might be absent until better preview functionality is implemented. *}
{* <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" /> *}

</form>
</div>
</div>

