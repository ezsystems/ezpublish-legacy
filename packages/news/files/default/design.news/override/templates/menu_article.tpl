{default content_version=$node.contentobject_version_object
         node_name=$node.name}
<h3>{$node_name|wash}</h3>
<div class="imageright">
{attribute_view_gui attribute=$content_version.data_map.thumbnail image_class=small}
</div>
{attribute_view_gui attribute=$content_version.data_map.intro}
<p class="readmore"><a href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>Read more....</a></p>

{/default}