<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" />
<input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" />
</form>

<div class="controlbar">
<div class="editblock">
<span class="advanced-mode">
</span>
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
