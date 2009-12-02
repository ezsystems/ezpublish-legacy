{* Weblog - Admin preview *}

{let sort_order=$node.parent.sort_array[0][1]
     sort_column=$node.parent.sort_array[0][0]
     sort_column_value=cond( $sort_column|eq( 'published' ), $node.object.published,
                             $sort_column|eq( 'modified' ), $node.object.modified,
                             $sort_column|eq( 'name' ), $node.object.name,
                             $sort_column|eq( 'priority' ), $node.priority,
                             $sort_column|eq( 'modified_subnode' ), $node.modified_subnode,
                             false() )
     previous_log=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'weblog' ),
                                              limit, 1,
                                              attribute_filter, array( and, array( $sort_column, $sort_order|choose( '>', '<' ), $sort_column_value ) ),
                                              sort_by, array( array( $sort_column, $sort_order|not ), array( 'node_id', $sort_order|not ) ) ) )
     next_log=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 'weblog' ),
                                          limit, 1,
                                          attribute_filter, array( and, array( $sort_column, $sort_order|choose( '<', '>' ), $sort_column_value ) ),
                                          sort_by, array( array( $sort_column, $sort_order ), array( 'node_id', $sort_order ) ) ) )}
<div class="content-view-full">
    <div class="class-weblog">

        <h1>{$node.name|wash()}</h1>

        <div class="attribute-byline">
           <p class="author">{$node.object.owner.name|wash()}</p>
           <p class="date">{$node.object.published|l10n(date)}</p>
           <div class="break"></div>
        </div>

        <div class="attribute-message">
           {attribute_view_gui attribute=$node.data_map.message}
        </div>

   </div>
</div>

{/let}