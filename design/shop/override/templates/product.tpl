<h1>{$node.name}</h1>

<div class="imageright">
   {attribute_view_gui attribute=$node.object.data_map.image}
</div>

{attribute_view_gui attribute=$node.object.data_map.product_number}

{attribute_view_gui attribute=$node.object.data_map.description}

{attribute_view_gui attribute=$node.object.data_map.price}

<a href={concat('/layout/set/print/', $node.url_alias )|ezurl}>Printer version</a>