<strong>{$node.name}</strong>
<div class="imageright">
{attribute_view_gui attribute=$node.object.data_map.thumbnail image_class=small}
</div>
{attribute_view_gui attribute=$node.object.data_map.intro}
<a class="small" href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>Read more....</a>

