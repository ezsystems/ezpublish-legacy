<h2>{$node.name|wash}</h2>

<a href={$node.url_alias|ezurl}>{attribute_view_gui attribute=$node.object.data_map.image image_class=small}</a>

{attribute_view_gui attribute=$node.object.data_map.caption}
