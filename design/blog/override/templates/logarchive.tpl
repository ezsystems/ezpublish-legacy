{let log_limit=10
     log_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 23 ),
                                          offset, $view_parameters.offset,
                                          limit, $log_limit,
                                          sort_by, array( 'published', false() ) ) )
     category_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                               class_filter_type, include,
                                               class_filter_array, array( 1 ),
                                               sort_by, array( 'name', true() ) ) )}
<div id="archive">

  <div class="header">
    <h1><span>Log Archive</span></h1>
    <em>Parenthetical thoughts, concepts, and brainstorms</em>
  </div>

  <h2>Excerpts from Recent Entries</h2>
  <dl>
    {section var=log loop=$log_list}
      <dt><a href={concat( "content/view/full/", $log.item.node_id )|ezurl} >{$log.item.name|wash}</a> <em class="date">{$log.item.object.published|datetime( custom, "%d %M %Y" )}</em></dt>
      <dd>{attribute_view_gui attribute=$log.item.data_map.log}</dd>
    {/section}

    <dt><a href={concat( "content/view/full/", $node.node_id )|ezurl} )>View all entry titles</a><strong class="arrow">&nbsp;&raquo;</strong></dt>
  </dl>
  
  
  <h2>Entry Categories</h2>
  <dl>
    {section var=category loop=$category_list}
    {let log_count=fetch( content, tree_count, hash( parent_node_id, $category.item.node_id,
                                                     class_filter_type, include,
                                                     class_filter_array, array( 23 ) ) )}
      <dt><a href={concat( "content/view/full/", $category.item.node_id )|ezurl}>{$category.item.name|wash}</a> <em>{section show=$log_count|eq(1)}1 entry{section-else}{$log_count} entries{/section}</em></dt>
      <dd>{attribute_view_gui attribute=$category.item.data_map.description}</dd>
    {/let}
    {/section}
  </dl>

</div>

{/let}
