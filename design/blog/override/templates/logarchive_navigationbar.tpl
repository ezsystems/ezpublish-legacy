{* month=hash( "month", "October",
                 "year", 2003,
                 "previous", hash( "link", "/sep", "month", "September", "year", 2003 ),
                 "next", hash( "link", "/nov", "month", "November", "year", 2003 ),
                 "weekdays", array( hash( "day", "Mon", "class", "weekday" ),
                                    hash( "day", "Tue", "class", "weekday" ),
                                    hash( "day", "Wed", "class", "weekday" ),
                                    hash( "day", "Thu", "class", "weekday" ),
                                    hash( "day", "Fri", "class", "weekday" ),
                                    hash( "day", "Sat", "class", "holiday" ),
                                    hash( "day", "Sun", "class", "holiday" ) ),
                 "weeks", hash( 40, array( false(), false(), hash( "day", 1, link, "/" ), hash( "day", 2, link, "/" ), 3, 4, 5 ),
                                41, array( 6, 7, 8, hash( "day", 9, link, "/" ), 10, 11, 12 ),
                                42, array( 13, hash( "day", 14, link, "/abc" ), hash( "day", 15, link, "/" ), 16, 17, 18, 19 ),
                                43, array( hash( "day", 20, link, "/" ), 21, hash( "day", 22, link, "/" ), hash( "day", 23, link, "/" ), 24, 25, 26 ),
                                44, array( 27, 28, 29, hash( "day", 30, link, "/" ), hash( "day", 31, link, "/", "class", "selected", "highlight", true() ), false(), false() ) ) )*}
{let show_week=false()
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
{/let}
