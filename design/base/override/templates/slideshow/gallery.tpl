{* Gallery - Full view *}

<div class="view-slideshow">
    <div class="class-gallery">

        <h1>{$node.name|wash()}</h1>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="content-long">
           {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <a href={$node.url_alias|ezurl}>Thumbnail view</a>

        {let page_limit=1
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                  offset, $view_parameters.offset,
			        		  limit, $page_limit ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        {section show=$children}
           <div class="view-children">
               {section var=child loop=$children sequence=array(bglight,bgdark)}
                  {node_view_gui view=full content_node=$child}
               {/section}
           </div>
        {/section}

        {include name=navigator
                 uri='design:navigator/google.tpl'
                 page_uri=concat( '/content/view', '/slideshow/', $node.node_id )
                 item_count=$list_count
                 view_parameters=$view_parameters
                 item_limit=$page_limit}
        {/let}

    </div>
</div>