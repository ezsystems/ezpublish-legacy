<div class="person_line">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<div class="object_title">
<a href={$node.url_alias|ezurl}><h1>{$node.name} ( {attribute_view_gui attribute=$node.object.data_map.position} )</h1></a>
</div>

{section show=$node.object.can_edit}
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
{/section}

<div class="imageright">
    {attribute_view_gui attribute=$node.object.data_map.picture image_class=medium}
</div>

{attribute_view_gui attribute=$node.object.data_map.person_numbers}

{attribute_view_gui attribute=$node.object.data_map.comment}

</form>

</div>