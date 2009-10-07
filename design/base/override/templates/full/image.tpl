{* Image - Full view *}
{let sort_order=$node.parent.sort_array[0][1]
     sort_column=$node.parent.sort_array[0][0]
     sort_column_value=cond( $sort_column|eq( 'published' ), $node.object.published,
                             $sort_column|eq( 'modified' ), $node.object.modified,
                             $sort_column|eq( 'name' ), $node.object.name,
                             $sort_column|eq( 'priority' ), $node.priority,
                             $sort_column|eq( 'modified_subnode' ), $node.modified_subnode,
                             false() )
     previous_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                                class_filter_type, include,
                                                class_filter_array, array( 'image' ),
                                                limit, 1,
                                                attribute_filter, array( and, array( $sort_column, $sort_order|choose( '>', '<' ), $sort_column_value ) ),
                                                sort_by, array( array( $sort_column, $sort_order|not ), array( 'node_id', $sort_order|not ) ) ) )
     next_image=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 'image' ),
                                            limit, 1,
                                            attribute_filter, array( and, array( $sort_column, $sort_order|choose( '<', '>' ), $sort_column_value ) ),
                                            sort_by, array( array( $sort_column, $sort_order ), array( 'node_id', $sort_order ) ) ) )}

<div class="content-view-full">
    <div class="class-image">

        <h1>{$node.name|wash}</h1>

        {if is_unset( $versionview_mode )}
        <div class="content-navigator">
            {if $previous_image}
                <div class="content-navigator-previous">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div><a href={$previous_image[0].url_alias|ezurl} title="{$previous_image[0].name|wash}">{'Previous image'|i18n( 'design/base' )}</a>
                </div>
            {else}
                <div class="content-navigator-previous-disabled">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div>{'Previous image'|i18n( 'design/base' )}
                </div>
            {/if}

            {if $previous_image}
                <div class="content-navigator-separator">|</div>
            {else}
                <div class="content-navigator-separator-disabled">|</div>
            {/if}

            {let forum=$node.parent}
                <div class="content-navigator-forum-link"><a href={$forum.url_alias|ezurl}>{$forum.name|wash}</a></div>
            {/let}

            {if $next_image}
                <div class="content-navigator-separator">|</div>
            {else}
                <div class="content-navigator-separator-disabled">|</div>
            {/if}

            {if $next_image}
                <div class="content-navigator-next">
                    <a href={$next_image[0].url_alias|ezurl} title="{$next_image[0].name|wash}">{'Next image'|i18n( 'design/base' )}</a><div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {else}
                <div class="content-navigator-next-disabled">
                    {'Next image'|i18n( 'design/base' )}<div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {/if}
        </div>
        {/if}

        <div class="attribute-image">
            <p>{attribute_view_gui attribute=$node.data_map.image image_class=large}</p>
        </div>

        <div class="attribute-caption">
            {attribute_view_gui attribute=$node.data_map.caption}
        </div>

    </div>
</div>
{/let}