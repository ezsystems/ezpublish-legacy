<div id="poll">
<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<h4>{$node.name|wash}</h4>
{attribute_view_gui attribute=$node.object.data_map.option}
{section name=ContentAction loop=$node.object.content_action_list show=$content_object.content_action_list}
      <div class="block">
      <input class="button" type="submit" name="{$ContentAction:item.action}" value="Vote" />
      </div>
{/section}
<div class="block">
    <a href={concat( "/content/collectedinfo/", $node.node_id, "/" )|ezurl}>Result</a>
</div>
</form>

<a href={concat( "/content/view/full/", $node.parent_node_id, "/" )|ezurl}><h4>View all polls</h4></a>
</div>

