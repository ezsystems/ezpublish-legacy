<div id="person">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<h1>{$node.name|wash} ( {attribute_view_gui attribute=$node.object.data_map.position} )</h1>

{section show=$node.object.can_edit}
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
{/section}

<div class="imageright">
    {attribute_view_gui attribute=$node.object.data_map.picture image_class=medium}
</div>

<div class="contact">
<h2>{"Contact information"|i18n("design/intranet/layout")}</h2>
{attribute_view_gui attribute=$node.object.data_map.person_numbers}
</div>

<div class="comment">
{attribute_view_gui attribute=$node.object.data_map.comment}
</div>

</form>

</div>
