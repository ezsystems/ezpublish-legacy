{default content_object=$node.object
         node_name=$node.name}
<strong class="small">{$node_name}</strong>
<div class="imageright">
{attribute_view_gui attribute=$content_object.data_map.thumbnail image_class=small}
</div>
{attribute_view_gui attribute=$content_object.data_map.intro}
<a class="small" href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>Read more....</a>

{/default}