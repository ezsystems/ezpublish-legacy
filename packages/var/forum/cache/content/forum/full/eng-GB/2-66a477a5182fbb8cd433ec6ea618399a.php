<?php
$contentInfo = array( "site_design" => "forum",
                      "node_id" => "2",
                      "parent_node_id" => "1",
                      "node_depth" => "1",
                      "url_alias" => "",
                      "object_id" => "1",
                      "class_id" => "1",
                      "section_id" => "1",
                      "navigation_part_identifier" => "ezcontentnavigationpart",
                      "viewmode" => "full",
                      "language" => "eng-GB",
                      "offset" => 0,
                      "view_parameters" => array( "offset" => 0,
                                                  "year" => false,
                                                  "month" => false,
                                                  "day" => false ),
                      "role_list" => array( "1" ),
                      "discount_list" => array() );
$contentPath = array( array( "text" => "Forum",
                             "url" => false,
                             "url_alias" => false,
                             "node_id" => "2" ) );
$nodeID = "2";
$sectionID = "1";
$navigationPartIdentifier = "ezcontentnavigationpart";
// Timestamp for the cache format
$eZContentCacheCodeDate = 1054816011;

ob_start();

?><div id="folder">


<h1>Forum</h1>


<div class="object_content"><p>
Welcome to our community site
</p>
<table class="frontpagelist" cellspacing="0">
<tr>
<th align="top" >  <p>
Latest discussions in music
</p>

  </th><th align="top" >  <p>
Latest discussions in sports
</p>

  </th>
</tr>
<tr>
<td valign="top">  <ul class="forumlist">
        <li>
            <a href="/discussions/forum_main_group/music_discussion/what_is_wrong_with_pop/madonna_is_one_of_the_greats">Madonna is one of the greats</a>
            by Administrator User 
    </li>
        <li>
            <a href="/discussions/forum_main_group/music_discussion/what_is_wrong_with_pop">What is wrong with pop?</a>
            by Administrator User 
    </li>
    </ul>





  </td><td valign="top">  &nbsp;
  </td>
</tr>

</table>
<table class="frontpagelist" cellspacing="0">
<tr>
<th align="top" >  <p>
Latest news
</p>

  </th>
</tr>
<tr>
<td valign="top">  
<ul>
        <li>
            <a href="/news/latest_forum_dreamcars">Latest forum: Dreamcars</a>
        
    </li>
        <li>
            <a href="/news/choose_the_correct_forum">Choose the correct forum</a>
        
    </li>
        <li>
            <a href="/news/news_bulletin">News bulletin</a>
        
    </li>
    </ul>





  </td>
</tr>

</table>
</div>

</div><?php
$contentData = ob_get_contents();
ob_end_clean();

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentInfo['section_id'] );
?>
