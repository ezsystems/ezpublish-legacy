{default node_name=$node.name}
<a class="small" href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>{$node.name}</a><br />
{/default}