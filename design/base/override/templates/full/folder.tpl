{* Folder - Full view *}

<div class="view-full">
    <div class="class-folder">

        <h1>{$node.object.data_map.name.content|wash()}</h1>
        {section show=$node.object.data_map.summary.content.is_empty|not}
            <div class="content-short">
                {attribute_view_gui attribute=$node.object.data_map.summary}
            </div>
        {/section}
        {section show=$node.object.data_map.description.content.is_empty|not}
            <div class="content-long">
                {attribute_view_gui attribute=$node.object.data_map.description}
            </div>
        {/section}

        {let page_limit=10
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                  offset, $view_parameters.offset,
			        		  limit, $page_limit ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        <div class="view-children">
            {section var=Child loop=$children sequence=array(bglight,bgdark)}
                {node_view_gui view=line content_node=$Child}
            {/section}
        </div>

        {section show=$node.object.data_map.related_information.content.is_empty|not}
            <div class="relatedinfo">
                {attribute_view_gui attribute=$node.object.data_map.related_information}
            </div>
        {/section}

        {include name=navigator
                 uri='design:navigator/google.tpl'
                 page_uri=concat( '/content/view', '/full/', $node.node_id )
                 item_count=$list_count
                 view_parameters=$view_parameters
                 item_limit=$page_limit}
        {/let}

    </div>
</div>
