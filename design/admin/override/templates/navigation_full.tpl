{* Show the contents of the current node (will probably use an override)*}

<div class="viewbar">
{$node.name}, [{$node.object.class_name}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id} <br />
{*
Created: {$node.object.owner.name} ({$node.object.published|l10n(shortdatetime)})<br />
Last modified: {$node.object.current.creator.name} ({$node.object.modified|l10n(shortdatetime)})<br />
Number of versions: {$node.object.versions|count()}<br />
Number of children: {$node.children_count}<br />
Number of releated objects: {$node.object.related_contentobject_count}<br />
Number objects using this one: {$node.object.reverse_related_contentobject_count}<br />
*}
<table>
<tr>
<th>Created</th>
<th>Last modified</th>
<th>Versions</th>
<th>Related items</th>
<th>Used by</th>
{* <th>Sub items</th> *}
<th>Node ID</th>
<th>Object ID</th>
</tr>
<tr>
<td>{$node.object.owner.name} <br />({$node.object.published|l10n(shortdatetime)})</td>
<td>{$node.object.current.creator.name}<br /> ({$node.object.modified|l10n(shortdatetime)})</td>
<td>{$node.object.versions|count()}</td>
<td>{$node.object.related_contentobject_count}</td>
<td>{$node.object.reverse_related_contentobject_count}</td>
{* <td>{$node.children_count}</td> *}
<td>{$node.node_id}</td>
<td>{$node.object.id}</td>
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
