{* Folder - Full view *}
<div class="content-view-full">
    <div class="class-folder">

        <h1>{$node.object.data_map.name.content|wash()}</h1>

        {section show=$node.object.data_map.summary.content.is_empty|not}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.summary}
            </div>
        {/section}

        {section show=$node.object.data_map.body.content.is_empty|not}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.object.data_map.body}
            </div>
        {/section}

        {section show=$node.object.data_map.show_children.content}
            {let page_limit=10
                 list_items=array()
                 list_count=0}

            {section show=or( $view_parameters.day, $view_parameters.month, $view_parameters.year )}
                {let time_filter=array( and,
                                        array( 'published', '>=',
                                               maketime( 0, 0, 0,
                                                         $view_parameters.month, cond( $view_parameters.day, $view_parameters.day, 1 ), $view_parameters.year ) ),
                                        array( 'published', '<=',
                                               maketime( 23, 59, 59,
                                                         cond( $view_parameters.day, $view_parameters.month, $view_parameters.month|inc ), cond( $view_parameters.day, $view_parameters.day, 0 ), $view_parameters.year ) ) )}
                {set list_items=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                             offset, $view_parameters.offset,
                                                             attribute_filter, $time_filter,
                                                             sort_by, $node.sort_array,
                                                             limit, $page_limit ) )
                     list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}
                {/let}
            {section-else}
                {set list_items=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                             offset, $view_parameters.offset,
                                                             sort_by, $node.sort_array,
                                                             limit, $page_limit ) )}
                {set list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}
            {/section}

            <div class="content-view-children">
                {section var=child loop=$list_items sequence=array(bglight,bgdark)}
                    {node_view_gui view=line content_node=$child}
                {/section}
            </div>

            {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=concat( '/content/view', '/full/', $node.node_id )
                     item_count=$list_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
            {/let}

        {/section}

    </div>
</div>
