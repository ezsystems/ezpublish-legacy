{default node_name=$object.main_node.name node_url=$object.main_node.url_alias}{if $node_url}<a href={$node_url|ezurl}>{/if}{$node_name|shorten(5)|wash}{if $node_url}</a>{/if}
{/default}