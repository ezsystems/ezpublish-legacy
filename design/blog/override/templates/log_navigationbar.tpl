{let log_limit=10
     log_node_id=50
     log_list=fetch( content, tree, hash( parent_node_id, $log_node_id,
                                          class_filter_type, include,
                                          class_filter_array, array( 23 ),
                                          limit, $log_limit,
                                          sort_by, array( 'published', false() ) ) )
     show_week=false()
     month_list=fetch( content, tree, hash( parent_node_id, 2,
                                            class_filter_type, include,
                                            class_filter_array, array( 'log' ),
                                            group_by, array( "published", "day" ),
                                            as_object, false() ) )
     month=$month_list|month_overview( 'published', maketime( 0, 0, 0, 11, 1, 2003 ),
                                       hash( current, maketime( 0, 0, 0, 11, 13, 2003 ),
                                             current_class, 'selected',
                                             link, concat( "content/view/full/", $module_result.content_info.node_id ),
                                             month_link, true(), year_link, true(), day_link, true(),
                                             next, hash( link, concat( "content/view/full/", $module_result.content_info.node_id ) ),
                                             previous, hash( link, concat( "content/view/full/", $module_result.content_info.node_id ) )  ) )}
    {include uri="design:navigator/monthview.tpl"}

<h2>Recent entries</h2>

<ul>
  {section var=log loop=$log_list}
        {section show=eq( $module_result.content_info.node_id, $log.item.node_id )}
            <li class="selected">{$log.item.name|wash}
        {section-else}
            <li><a href={concat( "content/view/full/", $log.item.node_id )|ezurl}>{$log.item.name|wash}</a>
        {/section}
        </li>
    {/let}
  {/section}
</ul>
{/let}
