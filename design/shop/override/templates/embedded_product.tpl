<h3><a href={concat( '/content/view/full/', $object.main_node_id )|ezurl}>{$object.name}</a></h3>

{attribute_view_gui attribute=$object.data_map.image image_class=small}

{attribute_view_gui attribute=$object.data_map.price}