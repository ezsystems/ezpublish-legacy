{let link_limit=10
     link_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                           class_filter_type, exclude,
                                           class_filter_array, array( 'folder' ) ) )
     link_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          class_filter_type, exclude,
                                          class_filter_array, array( 'folder' ),
                                          offset, $view_parameters.offset,
                                          limit, $link_limit,
                                          sort_by, array( 'published', false() ) ) )}
<div id="category">

  <div class="header">
    <h1><span>Link Archive by Category</span></h1>
    <em>{$node.name|wash}</em>
    <p><strong>Description:</strong> {attribute_view_gui attribute=$node.data_map.description}</p>
  </div>


  {section var=link loop=$link_list}
    {node_view_gui view=line content_node=$link.item}
  {/section}

  {include name=navigator
           uri='design:navigator/google.tpl'
           page_uri=concat( '/content/view/full/', $node.node_id )
           item_count=$link_count
           view_parameters=$view_parameters
           item_limit=$link_limit}


</div>

{/let}
