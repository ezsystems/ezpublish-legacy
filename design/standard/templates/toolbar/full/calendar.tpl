{section show=or( $module_result.content_info.url_alias|begins_with( $show_subtree ),
                  eq( $module_result.content_info.class_identifier, $class_identifier ) )}

{let today_info=$view_parameters
     cache_keys=array( $today_info.year, $today_info.month, $today_info.day )}

{section show=or( $today_info|not, $today_info.year|not, $today_info.month|not, $today_info.day|not )}
    {set today_info=currentdate()|gettime}
    {set cache_keys=false()}
{/section}

{*{cache-block keys=$cache_keys}*}

<div class="toolbox">
    <div class="toolbox-design">
    <h2>Calendar</h2>

    {let log_node_id=$module_result.content_info.node_id
         show_week=false()
         month_list=fetch( content, tree, hash( parent_node_id, $module_result.content_info.node_id,
                                                class_filter_type, include,
                                                class_filter_array, $class_identifier|explode( ',' ),
                                                attribute_filter, array( and, array( 'published', '>=',
                                                                                      maketime( 0, 0, 0, $today_info.month, 1, $today_info.year ) ),
                                                                              array( 'published', '<=',
                                                                                      maketime( 23, 59, 59, $today_info.month | inc, 0, $today_info.year ) ) ),
                                                group_by, array( "published", "day" ),
                                                as_object, false() ) )
         month=$month_list|month_overview( 'published', maketime( 0, 0, 0, $today_info.month, $today_info.day, $today_info.year ),
                                           hash( current, maketime( 0, 0, 0, $today_info.month, $today_info.day, $today_info.year ),
                                                 current_class, 'selected',
                                                 link, concat( "content/view/full/", $log_node_id ),
                                                 month_link, true(), year_link, true(), day_link, true(),
                                                 next, hash( link, concat( "content/view/full/", $log_node_id ) ),
                                                 previous, hash( link, concat( "content/view/full/", $log_node_id ) )  ) )}
        {include name=Month uri="design:navigator/monthview.tpl" month=$month show_week=$show_week}
     {/let}

     </div>
</div>


{*{cache-block}*}

{/let}

{/section}
