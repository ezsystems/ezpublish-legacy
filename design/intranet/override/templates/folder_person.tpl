{let page_limit=10
     list_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                  class_filter_type, include,
						  class_filter_array, array( 17 ) ) )}

<h1>{$node.name}</h1>

{attribute_view_gui attribute=$node.object.data_map.description}

{let news=fetch(content,tree,hash(parent_node_id, $node.node_id,
     		                         sort_by,array(published,0),
					 limit, $page_limit, 
                                         offset, $view_parameters.offset,
					 class_filter_type, include,
					 class_filter_array, array( 17 ) ) )}

{section loop=$news}

{node_view_gui view=full content_node=$:item}

<hr>

{/section}

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$list_count
	 view_parameters=$view_parameters
	 item_limit=$page_limit}

{/let}
