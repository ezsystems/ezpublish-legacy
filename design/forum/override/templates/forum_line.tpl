<div class="forumlist">
<div class="image">{attribute_view_gui attribute=$node.object.data_map.image image_class=original}</div>
<h2><a href={$node.url_alias|ezurl}>{$node.name}</a></h2>
{attribute_view_gui attribute=$node.object.data_map.description}
<p>
Number of Topics: 
{fetch('content','list_count',hash(parent_node_id,$node.node_id))}
</p>
<p>
Number of Posts: 
{fetch('content','tree_count',hash(parent_node_id,$node.node_id))}
</p>
</div>