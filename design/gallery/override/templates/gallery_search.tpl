{* Gallery search template *}
{default href=$node.url_alias|ezurl}

<div class="gallery_search">
    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash} [Gallery]</a></h2>

    {attribute_view_gui attribute=$node.data_map.description}

    <div class="image">
    {let album_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( "album" ),
                                                limit, 3,
                                                sort_by, array( 'published', false() ) ) )}
    <h3>{"Albums"|i18n("design/gallery/layout")}</h3>
    <ul>
    {section var=album loop=$album_list}
        <li>
        {$album.item.name}
        </li>
    {/section}
    </ul>
    {/let}
    </div>

    {let gallery_item_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                              class_filter_type, include,
                                                              class_filter_array, array( "album" ) ) )}
        <p class="counter">The gallery contains {$gallery_item_count} albums.</p>
    {/let}


</div>

{/default}
