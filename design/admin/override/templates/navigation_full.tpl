{* Show the contents of the current node (will probably use an override)*}

<div class="mainobject-vindow">
{node_view_gui content_node=$node view=navigation}
</div>

{* Buttons for remove/edit/etc. and the location interface. *}
{include uri="design:buttons.tpl"}

{* Show the children of this node: *}
{include uri="design:children.tpl"}
