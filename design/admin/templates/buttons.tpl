<div class="controlbar">
<div class="editblock">
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

{section show=$node.object.can_edit}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'Click here to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{section-else}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" title="{'You do not have permissions to edit the item that is being displayed above.'|i18n( 'design/admin/layout' )} "disabled="disabled" />
{/section}

{* Allow remove button if this is not a root node and if the user is allowed to remove it: *}
{section show=or( eq( $node.node_id, ezini( 'NodeSettings', 'RootNode',      'content.ini' ) ),
                  eq( $node.node_id, ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) ),
                  eq( $node.node_id, ezini( 'NodeSettings', 'UserRootNode',  'content.ini' ) ),
                  eq( $node.object.can_remove, false() ) ) }
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'You do not have permissions to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}"disabled="disabled" />
{section-else}
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" title="{'Click here to remove the item that is being displayed above.'|i18n( 'design/admin/layout' )}" />
{/section}

<input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" />
</form>
</div>



{*
<div class="locationblock">

<table class="list" cellspacing="0">
<tr>
    <th class="delete">&nbsp;</th>
    <th class="path">Location:</th>
    <th class="main">Main:</th>
</tr>
<tr class="bglight">
    <td class="delete">&nbsp;</td>
    <td class="path">&gt; <a href="/">Top node</a> / <a href="/">First sub node</a> / <a href="/">Another sub node</a> / This node</td>
    <td class="main"><input type="radio" checked="checked" /></td>
</tr>
<tr class="bgdark">
    <td class="delete"><input type="checkbox" /></td>
    <td class="path">&gt; <a href="/">Top node</a> / <a href="/">First sub node</a> / <a href="/">Another sub node</a> / Another node</td>
    <td class="main"><input type="radio" /></td>
</tr>
</table>

<div class="button-left">
<input class="button" type="submit" value="Delete location" />
<input class="button" type="submit" value="Add location" />
</div>
<div class="button-right">
<input class="button" type="submit" value="Update main" />
</div>
<div class="break"></div>
</div>
*}

</div>
