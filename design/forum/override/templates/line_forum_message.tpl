{* Forum message template *}

{default node_name=$node.name}
<a href={concat( "/content/view/full/", $node.node_id, "/" )|ezurl}>{$node.name|wash}</a>
{/default}