{* Show the contents of the current node (will probably use an override)*}

<div class="viewbar">
<table>
<tr>
<td>
{$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )}
</td>
<td>
Name: {$node.name}
<br />
Type: {$node.object.class_name}<br />
</td>
</tr>
</table>
</div>

<div class="mainobject-vindow" title="{$node_name|wash} [{'Folder'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}">
{* Show the actual contents of the object. *}
{node_view_gui content_node=$node view=navigation}

{* Show related objects. *}
{* include uri="design:related_objects.tpl" *}
</div>

{* Buttons for remove/edit/etc. and the location interface. *}
{include uri="design:buttons.tpl"}

{* List of children. *}
<div class="content-view-children">
{include uri="design:children.tpl"}
</div>
