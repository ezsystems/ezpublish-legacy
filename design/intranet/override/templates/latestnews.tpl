<h1>{$node.name}</h1>

{attribute_view_gui attribute=$node.object.data_map.description}

{let latest_news=fetch(content,tree,hash(parent_node_id, $node.node_id,
                                         limit, 5,
     		                         sort_by,array(published,0),
					 class_filter_type, include,
					 class_filter_array, array( 2 ) ) )}

{section loop=$latest_news}

{node_view_gui view=line content_node=$:item}

{/section}

{/let}
