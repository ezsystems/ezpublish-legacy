<div id="infopage">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<div class="object_title">
    <h1>{$node.name|wash}</h1>
</div>

{attribute_view_gui attribute=$node.object.data_map.body}

</form>

</div>