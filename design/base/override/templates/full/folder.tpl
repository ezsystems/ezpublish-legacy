{* Folder - Full view *}

<div class="view-full">
    <div class="class-folder">

        <h1>{$node.name}</h1>

        {section show=ne($node.object.data_map.short_description.content.output.output_text,'')}
            <div class="content-short">
                {attribute_view_gui attribute=$node.object.data_map.short_description}
            </div>
        {/section}

        {section show=ne($node.object.data_map.full_description.content.output.output_text,'')}
            <div class="content-long">
                {attribute_view_gui attribute=$node.object.data_map.full_description}
            </div>
        {/section}

        {let page_limit=10
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                  offset, $view_parameters.offset,
			        		  limit, $page_limit ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        <div class="view-children">
            {section name=Child loop=$children sequence=array(bglight,bgdark)}
                {node_view_gui view=line content_node=$Child:item}
            {/section}
        </div>
        
        {include name=navigator
                 uri='design:navigator/google.tpl'
                 page_uri=concat( '/content/view', '/full/', $node.node_id )
                 item_count=$list_count
                 view_parameters=$view_parameters
                 item_limit=$page_limit}

    </div>
</div>
