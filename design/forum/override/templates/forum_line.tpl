<div class="forum_line">
<h2><a href={$node.url_alias|ezurl}>{$node.name}</a></h2>

Number of Topics: 
{fetch('content','list_count',hash(parent_node_id,$node.node_id))}

Number of Posts: 
{fetch('content','tree_count',hash(parent_node_id,$node.node_id))}

</div>