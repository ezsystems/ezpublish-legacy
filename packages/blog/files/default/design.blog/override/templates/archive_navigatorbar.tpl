{let view_parameters=$module_result.content_info.view_parameters
     today_info=$view_parameters
     cache_keys=array( $today_info.year, $today_info.month, $today_info.day )}

{section show=or( $today_info.year|not, $today_info.month|not, $today_info.day|not )}
    {set today_info=currentdate()|gettime}
    {set cache_keys=false()}
{/section}

{cache-block keys=$cache_keys}
{let show_week=false()
     item_node_id=50
     month_list=fetch( content, tree, hash( parent_node_id, $item_node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 'log' ),
                                            attribute_filter, array( and, array( 'published', '>=',
                                                                                  maketime( 0, 0, 0, $today_info.month, 1, $today_info.year ) ),
                                                                          array( 'published', '<=',
                                                                                  maketime( 23, 59, 59, $today_info.month | inc, 0, $today_info.year ) ) ),
                                            group_by, array( "published", "day" ),
                                            as_object, false() ) )
     month=$month_list|month_overview( 'published', maketime( 0, 0, 0, $today_info.month, $today_info.day, $today_info.year ),
                                        hash( current, maketime( 0, 0, 0, $today_info.month, $today_info.day, $today_info.year ),
                                              current_class, 'selected',
                                              link, concat( "content/view/full/", $item_node_id ),
                                              month_link, true(), year_link, true(), day_link, true(),
                                              next, hash( link, concat( "content/view/full/", $item_node_id ) ),
                                              previous, hash( link, concat( "content/view/full/", $item_node_id ) )  ) )}
    <h2>{"Log entries"|i18n("design/blog/layout")}</h2>
    {include uri="design:navigator/monthview.tpl"}
{/let}

{cache-block}

{/let}
