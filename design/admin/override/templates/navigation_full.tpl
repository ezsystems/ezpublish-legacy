<h1>Full view template for navigation part</h1>
<h2>Currently viewing node number {$node.node_id}: {$node.name}</h2>

<hr />

{* Show the contents of the current node (will probably use an override)*}
{node_view_gui content_node=$node view=navigation}

<hr />

{* Buttons for remove/edit/etc. and the location interface. *}
{include uri="design:buttons.tpl"}

<hr />

{* Show the children of this node: *}
{include uri="design:children.tpl"}
