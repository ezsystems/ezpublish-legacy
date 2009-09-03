{* Weblog - Full view *}

{let sort_order=$node.parent.sort_array[0][1]
     sort_column=$node.parent.sort_array[0][0]
     sort_column_value=cond( $sort_column|eq( 'published' ), $node.object.published,
                             $sort_column|eq( 'modified' ), $node.object.modified,
                             $sort_column|eq( 'name' ), $node.object.name,
                             $sort_column|eq( 'priority' ), $node.priority,
                             $sort_column|eq( 'modified_subnode' ), $node.modified_subnode,
                             false() )
     previous_log=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                              class_filter_type, include,
                                              class_filter_array, array( 'weblog' ),
                                              limit, 1,
                                              attribute_filter, array( and, array( $sort_column, $sort_order|choose( '>', '<' ), $sort_column_value ) ),
                                              sort_by, array( array( $sort_column, $sort_order|not ), array( 'node_id', $sort_order|not ) ) ) )
     next_log=fetch_alias( subtree, hash( parent_node_id, $node.parent_node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 'weblog' ),
                                          limit, 1,
                                          attribute_filter, array( and, array( $sort_column, $sort_order|choose( '<', '>' ), $sort_column_value ) ),
                                          sort_by, array( array( $sort_column, $sort_order ), array( 'node_id', $sort_order ) ) ) )}
<div class="content-view-full">
    <div class="class-weblog">

        <h1>{$node.name|wash()}</h1>

        {section show=is_unset( $versionview_mode )}
        <div class="content-navigator">
            {section show=$previous_log}
                <div class="content-navigator-previous">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div><a href={$previous_log[0].url_alias|ezurl} title="{$previous_log[0].name|wash}">{'Previous entry'|i18n( 'design/base' )}</a>
                </div>
            {section-else}
                <div class="content-navigator-previous-disabled">
                    <div class="content-navigator-arrow">&laquo;&nbsp;</div>{'Previous entry'|i18n( 'design/base' )}
                </div>
            {/section}

            {section show=$previous_log}
                <div class="content-navigator-separator">|</div>
            {section-else}
                <div class="content-navigator-separator-disabled">|</div>
            {/section}

            {let weblogs=$node.parent}
                <div class="content-navigator-weblog-link"><a href={$weblogs.url_alias|ezurl}>{$weblogs.name|wash}</a></div>
            {/let}

            {section show=$next_log}
                <div class="content-navigator-separator">|</div>
            {section-else}
                <div class="content-navigator-separator-disabled">|</div>
            {/section}

            {section show=$next_log}
                <div class="content-navigator-next">
                    <a href={$next_log[0].url_alias|ezurl} title="{$next_log[0].name|wash}">{'Next entry'|i18n( 'design/base' )}</a><div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {section-else}
                <div class="content-navigator-next-disabled">
                    {'Next entry'|i18n( 'design/base' )}<div class="content-navigator-arrow">&nbsp;&raquo;</div>
                </div>
            {/section}
        </div>
        {/section}

        <div class="attribute-byline">
           <p class="author">{$node.object.owner.name|wash(xhtml)}</p>
           <p class="date">{$node.object.published|l10n(date)}</p>
           <div class="break"></div>
        </div>

        <div class="attribute-message">
           {attribute_view_gui attribute=$node.data_map.message}
        </div>

        {section show=$node.data_map.enable_comments.content}
            <h2>{"Comments"|i18n("design/base")}</h2>

            <div class="content-view-children">
               {section var=comment loop=fetch_alias( comments, hash( parent_node_id, $node.node_id ) )}
                   {node_view_gui view='line' content_node=$comment}
               {/section}
            </div>

            {section show=fetch( content, access, hash( access, 'create',
                                                        contentobject, $node,
                                                        contentclass_id, 'comment',
                                                        parent_contentclass_id, $node.object.class_identifier ) )}
            <div class="content-action">
                <form method="post" action={"content/action"|ezurl}>
                   <input type="hidden" name="ClassIdentifier" value="comment" />
                   <input type="hidden" name="NodeID" value="{$node.node_id}" />
                   <input class="button new_comment" type="submit" name="NewButton" value="{'New comment'|i18n("design/base")}" />
                </form>
            </div>
            {section-else}
                <div class="message-warning">
                    <h3>{"You are not allowed to create comments."|i18n("design/base")}</h3>
                </div>
            {/section}
        {/section}
   </div>
</div>

{/let}