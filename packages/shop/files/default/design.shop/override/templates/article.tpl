<div class="article">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<h1>{$node.name|wash}</h1>

<div class="byline">
    <p class="date">
    ({$node.object.published|l10n( datetime )})
    </p>
</div>

{*
<div class="imageright">
    {attribute_view_gui attribute=$node.object.data_map.thumbnail image_class=medium}
</div>
*}

<div class="intro">
{attribute_view_gui attribute=$node.object.data_map.intro}
</div>

<div class="body">
{attribute_view_gui attribute=$node.object.data_map.body}
</div>

</form>
</div>