{* Displays an archive of logs, links and polls.
   The first section part displays items based on a certain date
   The second part displays last items and sub categories *}

{section show=or( $view_parameters.day, $view_parameters.month, $view_parameters.year )}

{let item_limit=5
     item_count=fetch( content, tree_count, hash( parent_node_id, $node.node_id,
                                                  class_filter_type, exclude,
                                                  class_filter_array, array( 'folder', 'comment' ),
                                                  attribute_filter, array( and, array( 'published', '>=',
                                                                                        maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ),
                                                                                array( 'published', '<=',
                                                                                        maketime( 23, 59, 59, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ) ) )
     item_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                           class_filter_type, exclude,
                                           class_filter_array, array( 'folder', 'comment' ),
                                           attribute_filter, array( and, array( 'published', '>=',
                                                                                 maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ),
                                                                         array( 'published', '<=',
                                                                                 maketime( 23, 59, 59, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ),
                                           offset, $view_parameters.offset,
                                           limit, $item_limit,
                                           sort_by, array( 'published', false() ) ) )}

<div id="dayarchive">

{let previous_item=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                               class_filter_type, exclude,
                                               class_filter_array, array( 'folder', 'comment' ),
                                               limit, 1,
                                               attribute_filter, array( and, array( 'published', '<',
                                                                                     maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ),
                                               sort_by, array( 'published', false() ) ) )
    next_item=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, exclude,
                                          class_filter_array, array( 'folder', 'comment' ),
                                          limit, 1,
                                          attribute_filter, array( and, array( 'published', '>',
                                                                                maketime( 23, 59, 59, $view_parameters.month, $view_parameters.day, $view_parameters.year ) ) ),
                                          sort_by, array( 'published', true() ) ) )}

  <div class="header">
    <h1><span>{$node.data_map.archive_title.content|wash} by Day</span></h1>

    <p>

{section show=$previous_item|gt(0)}
    {let date_info=$previous_item[0].object.published|gettime}

        <strong class="arrow">&laquo;</strong>
        <a href={concat( "content/view/full/", $node.node_id, '/year/', $date_info.year, '/month/', $date_info.month, '/day/', $date_info.day )|ezurl} title="{$previous_item[0].object.published|datetime( custom, '%F %d, %Y' )}">Previous day</a>

    {/let}

    <span class="sub">|</span>

{/section}

<strong>{maketime( 0, 0, 0, $view_parameters.month, $view_parameters.day, $view_parameters.year )|datetime( custom, '%d %F %Y' )}</strong>

{section show=$next_item|gt(0)}

    <span class="sub">|</span>

    {let date_info=$next_item[0].object.published|gettime}

        <a href={concat( "content/view/full/", $node.node_id, '/year/', $date_info.year, '/month/', $date_info.month, '/day/', $date_info.day )|ezurl} title="{$next_item[0].object.published|datetime( custom, '%F %d, %Y' )}">Next day</a>
        <strong class="arrow">&raquo;</strong>

    {/let}

{/section}

    </p>
  </div>

{/let}

</div>

  <div id="itemlist">
  {section var=log loop=$item_list}
    {node_view_gui view=line content_node=$log.item}
  {/section}

  {include name=navigator
           uri='design:navigator/google.tpl'
           page_uri=concat( '/content/view/full/', $node.node_id )
           item_count=$item_count
           view_parameters=$view_parameters
           item_limit=$item_limit}
  </div>

{/let}

{section-else}

{let item_limit=10
     item_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                          class_filter_type, exclude,
                                          class_filter_array, array( 'folder', 'comment' ),
                                          offset, $view_parameters.offset,
                                          limit, $item_limit,
                                          sort_by, array( 'published', false() ) ) )
     category_list=fetch( content, tree, hash( parent_node_id, $node.node_id,
                                               class_filter_type, include,
                                               class_filter_array, array( 'folder' ),
                                               sort_by, array( 'name', true() ) ) )}
<div id="archive">
  <div class="header">
    <h1><span>{$node.data_map.archive_title.content|wash}</span></h1>
    {attribute_view_gui attribute=$node.data_map.description}
  </div>

  <div id="itemlist">
  <h2>{$node.data_map.list_title.content|wash}</h2>
    {section var=item loop=$item_list}
        {node_view_gui view=line archive_view=true() content_node=$item.item}
    {/section}
  
  {section show=ne($node.node_id,173)}
  <div id="categorylist">
  <h2>{$node.data_map.category_list_title.content|wash}</h2>
    {section var=category loop=$category_list}
        {node_view_gui view=line content_node=$category.item}
    {/section}
  </div>
  {/section}
  </div>
</div>

{/let}

{/section}
