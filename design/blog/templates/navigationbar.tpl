         <h2>Navigation bar</h2>
{let
 month=hash( "month", "October",
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
                                44, array( 27, 28, 29, hash( "day", 30, link, "/" ), hash( "day", 31, link, "/", "class", "selected", "highlight", true() ), false(), false() ) ) )
  show_week=false()
  list=fetch( content, list, hash( parent_node_id, 2, depth, false(), group_by, array( "published", "day" ), as_object, false() ) )
}
{set month=$list|month_overview( 'published', maketime( 0, 0, 0, 11, 1, 2003 ),
                                 hash( current, maketime( 0, 0, 0, 11, 13, 2003 ),
                                       current_class, 'selected',
                                       link, 'content/view/full/2',
                                       month_link, true(), year_link, true(), day_link, true(),
                                       next, hash( link, "content/view/full/2" ),
                                       previous, hash( link, "content/view/full/2" )  ) )}
{include uri="design:navigator/monthview.tpl"}
{/let}
