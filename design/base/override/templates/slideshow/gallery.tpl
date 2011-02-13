{* Gallery - Full view *}

<div class="view-slideshow">
    <div class="class-gallery">

        {let page_limit=1
             list_count=fetch_alias( children_count, hash( parent_node_id, $node.node_id ) )}

        {if $list_count}
            <div class="content-view-children">
                {foreach fetch_alias( children, hash( parent_node_id, $node.node_id,
                                                      offset, $view_parameters.offset,
                                                      limit, $page_limit ) ) as $child}
                    {node_view_gui view=galleryslide content_node=$child parent_name=$node.name}
                {/foreach}
            </div>
        {/if}

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