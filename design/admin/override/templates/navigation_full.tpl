{* Show the contents of the current node (will probably use an override)*}

<div class="menu-block">
<ul>
    <li><a class="enabled" href="/">Content</a></li>
    <li><a href="/">Information</a></li>
    <li><a class="enabled" href="/">Locations</a></li>
    <li><a href="/">Relations</a></li>
    <li><a class="enabled" href="/">Children</a></li>
</ul>
</div>

{*
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
*}

<div class="context-block">

<h2 class="title">{$node.object.content_class.identifier|class_icon( normal, $node.object.content_class.name )} {$node.name} [{$node.object.class_name}]</h2>

<div class="mainobject-vindow" title="{$node_name|wash} [{'Folder'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}">
{* Show the actual contents of the object. *}
{node_view_gui content_node=$node view=navigation}

</div>

{include uri="design:buttons.tpl"}

</div>


{* Show related objects. *}
{* include uri="design:related_objects.tpl" *}


{* Buttons for remove/edit/etc. and the location interface. *}


{* List of children. *}
<div class="content-view-children">
{include uri="design:children.tpl"}
</div>
