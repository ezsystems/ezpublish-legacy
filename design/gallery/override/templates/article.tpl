<div id="article">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<h1>{$node.name|wash}</h1>

<div class="byline">
  <p>
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