{* Image - Full view *}
{let sort_order=$node.parent.sort_array[0][1]
     sort_column=$node.parent.sort_array[0][0]
     previous_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( 'image' ),
                                                limit, 1,
                                                attribute_filter, array( and, array( $sort_column, '<', $node.object.published ) ),
                                                sort_by, array( $sort_column, $sort_order|not ) ) )
     next_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 'image' ),
                                            limit, 1,
                                            attribute_filter, array( and, array( $sort_column, '>', $node.object.published ) ),
                                            sort_by, array( $sort_column, $sort_order ) ) ) }

<div class="content-view-full">
    <div class="class-image">

        <h1>{$node.name}</h1>

        {section show=is_unset( $versionview_mode )}
        <div class="content-navigator">
            {section show=$previous_image}
                <div class="content-navigator-previous">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div><a href={$previous_image[0].url_alias|ezurl} title="{$previous_image[0].name|wash}">{'Previous image'|i18n( 'design/base' )}</a>
                </div>
            {section-else}
                <div class="content-navigator-previous-disabled">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div>{'Previous image'|i18n( 'design/base' )}
                </div>
            {/section}

            {section show=$previous_image}
                <div class="content-navigator-separator">|</div>
            {section-else}
                <div class="content-navigator-separator-disabled">|</div>
            {/section}

            {let forum=$node.parent}
                <div class="content-navigator-forum-link"><a href={$forum.url_alias|ezurl}>{$forum.name|wash}</a></div>
            {/let}

            {section show=$next_image}
                <div class="content-navigator-separator">|</div>
            {section-else}
                <div class="content-navigator-separator-disabled">|</div>
            {/section}

            {section show=$next_image}
                <div class="content-navigator-next">
                    <a href={$next_image[0].url_alias|ezurl} title="{$next_image[0].name|wash}">{'Next image'|i18n( 'design/base' )}</a><div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {section-else}
                <div class="content-navigator-next-disabled">
                    {'Next image'|i18n( 'design/base' )}<div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {/section}
        </div>
        {/section}

        <div class="attribute-image">
            <p>{attribute_view_gui attribute=$node.object.data_map.image image_class=imagelarge}</p>
        </div>

        <div class="attribute-caption">
            {attribute_view_gui attribute=$node.object.data_map.caption}
        </div>

    </div>
</div>
{/let}