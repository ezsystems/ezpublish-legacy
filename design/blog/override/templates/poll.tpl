<div id="poll">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<div class="object_title">
    <h1>{$node.name}</h1>
</div>

{attribute_view_gui attribute=$node.object.data_map.option}

{section name=ContentAction loop=$node.object.content_action_list show=$content_object.content_action_list}
      <div class="block">
      <input class="button" type="submit" name="{$ContentAction:item.action}" value="Vote" />
      </div>
{/section}

</form>

</div>