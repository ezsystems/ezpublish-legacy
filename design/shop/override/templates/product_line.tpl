<div class="product">

<h2><a href={$node.url_alias|ezurl}>{$node.name}</a></h2>
Price: {attribute_view_gui attribute=$node.object.data_map.price}
<a href={$node.url_alias|ezurl}>{attribute_view_gui attribute=$node.object.data_map.image image_class=small}</a>

</div>