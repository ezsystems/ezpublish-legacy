<div class="product">
    {attribute_view_gui attribute=$object.data_map.image image_class=small}
    <p><a href={concat( '/content/view/full/', $object.main_node_id )|ezurl}>{$object.name}</a> ({$object.data_map.price.content|l10n(currency)})</p>
</div>
