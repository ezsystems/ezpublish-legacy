<form method="post" action={"content/action"|ezurl}> 

<h1>{$node.name}</h1>

   {attribute_view_gui attribute=$node.object.data_map.image}

{attribute_view_gui attribute=$node.object.data_map.product_number}

{attribute_view_gui attribute=$node.object.data_map.description}

{attribute_view_gui attribute=$node.object.data_map.price}

<a href={concat('/layout/set/print/', $node.url_alias )|ezurl}>Printer version</a>

<input type="submit" class="defaultbutton" name="ActionAddToBasket" value="Add to basket" />

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" /> 

</form>