{* Album search template *}
{default href=$node.url_alias|ezurl}
{let image_content=$node.data_map.image.content}

<div class="album_search">
    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash} [Album]</a></h2>

    <div class="image">
    {section show=$image_content.original.is_valid}
        {attribute_view_gui attribute=$node.data_map.image href=$href image_class=small}
    {section-else}
        {let album_first_image=fetch( content, list, hash( parent_node_id, $node.node_id,
                                                           class_filter_type, include,
                                                           class_filter_array, array( "image" ),
                                                           limit, 1,
    
                                                           sort_by, array( 'published', false() ) ) )}
        {section var=image loop=$album_first_image}
            {attribute_view_gui attribute=$image.item.data_map.image href=$href image_class=small_h}
        {/section}
        {/let}
    {/section}
    </div>

    {attribute_view_gui attribute=$node.data_map.description}

    {let album_item_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                            class_filter_type, include,
                                                            class_filter_array, array( "image" ) ) )}
        <p class="counter">The album contains {$album_item_count} images.</p>
    {/let}


</div>

{/let}
{/default}
