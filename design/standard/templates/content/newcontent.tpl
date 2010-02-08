<div class="content-new">
    <h1>{"New content since last visit"|i18n("design/standard/content/newcontent")}</h1>
    <p>{"Your last visit to this site was"|i18n("design/standard/content/newcontent")}:
        {$last_visit_timestamp|l10n(datetime)}
    </p>


    {let page_limit=20
         time_filter=array( array( 'modified', '>=', $last_visit_timestamp ) )
         list_count=fetch( content, tree_count, hash( parent_node_id, 2,
                                                      offset, first_set( $view_parameters.offset, 0),
                                                      attribute_filter, $time_filter ) )}

            <div class="content-view-children">
                {if $list_count}
                    {foreach fetch( content, tree, hash( parent_node_id, 2,
                                                         offset, first_set( $view_parameters.offset, 0),
                                                         attribute_filter, $time_filter,
                                                         sort_by, array( array( 'modified', false() ) ),
                                                         limit, $page_limit ) ) as $child}
                        {node_view_gui view=line content_node=$child}
                    {/foreach}
                {else}
                    <p>{"There is no new content since your last visit."|i18n("design/standard/content/newcontent")}</p>
                {/if}
            </div>

            {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri='/content/new'
                     item_count=$list_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
        {/let}
</div>
