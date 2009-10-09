{* Gallery - Full view *}

<div class="view-slideshow">
    <div class="class-gallery">

        {let page_limit=1
             children=fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                  offset, $view_parameters.offset,
			        		  limit, $page_limit ) )
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}


        {section show=$children}
           <div class="content-view-children">
               {section var=child loop=$children}
                  {node_view_gui view=galleryslide content_node=$child parent_name=$node.name}
               {/section}
           </div>
        {/section}

        {include name=navigator
                 uri='design:navigator/gallery.tpl'
                 page_uri=concat( '/content/view', '/slideshow/', $node.node_id )
                 item_count=$list_count
                 view_parameters=$view_parameters
                 item_limit=$page_limit}

        <div class="content-link">
            <p>
                <a href={$node.url_alias|ezurl}>Thumbnail view</a>
            </p>
        </div>

        {/let}

    </div>
</div>