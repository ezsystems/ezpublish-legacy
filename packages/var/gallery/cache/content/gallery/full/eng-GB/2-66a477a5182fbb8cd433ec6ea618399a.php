<?php
$contentInfo = array( "site_design" => "gallery",
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
$contentPath = array( array( "text" => "Gallery",
                             "url" => false,
                             "url_alias" => false,
                             "node_id" => "2" ) );
$nodeID = "2";
$sectionID = "1";
$navigationPartIdentifier = "ezcontentnavigationpart";
// Timestamp for the cache format
$eZContentCacheCodeDate = 1054816011;

ob_start();

?><div id="frontpage">

    <h1>Gallery</h1>

    
    <div id="gallery">
    <div class="list">
    <h2>Galleries</h2>

    <form method="post" action="/content/action">

        <input type="hidden" name="ContentNodeID" value="2" />
        <input type="hidden" name="ContentObjectID" value="1" />
        <input type="hidden" name="ViewMode" value="full" />

                                                        </form>

    <table class="list">
    <tr>
            <td class="image">
                                                                                                              <a href="/abstract"><img src="/var/gallery/storage/images/abstract/misc/cvs_branching/1139-1-eng-GB/cvs_branching_small_h.jpg" width="173" height="130"   border="0" alt="CVS branch" title="CVS branch" /></a>                                </td>
         <td class="info">
             <h2><a href="/abstract" title="<p>
Abstract photography
</p>
">Abstract</a></h2>

             <p>
Abstract photography
</p>

             <p class="byline">Last changed on 20/11/2003.</p>
             <p class="counter">This gallery contains 5 images.</p>
                        <td class="image">
                                                                                                              <a href="/nature"><img src="/var/gallery/storage/images/nature/flowers/blue_flower/1107-1-eng-GB/blue_flower_small_h.jpg" width="173" height="130"   border="0" alt="Blue flower" title="Blue flower" /></a>                                </td>
         <td class="info">
             <h2><a href="/nature" title="<p>
Nature images
</p>
">Nature</a></h2>

             <p>
Nature images
</p>

             <p class="byline">Last changed on 20/11/2003.</p>
             <p class="counter">This gallery contains 7 images.</p>
                    </tr>
    </table>
    </div>
    </div>

    <div id="image">
    <div class="list">
    
    <h2>Latest images</h2>
    <table class="imagelist">
    <tr>
                <td>
                     <a href="/abstract/misc/speeding"><img src="/var/gallery/storage/images/abstract/misc/speeding/1151-1-eng-GB/speeding_small_h.jpg" width="173" height="130"   border="0" alt="Speed" title="Speed" /></a>              <p class="caption"><p>
All withing legal limits, of course.
</p>
</p>
        </td>
                        <td>
                     <a href="/abstract/misc/mjaurits"><img src="/var/gallery/storage/images/abstract/misc/mjaurits/1148-1-eng-GB/mjaurits_small_h.jpg" width="173" height="130"   border="0" alt="A closeup of the cat Mjaurits" title="A closeup of the cat Mjaurits" /></a>              <p class="caption"><p>
Mjaurits the cat.
</p>
</p>
        </td>
                        <td>
                     <a href="/abstract/misc/green_clover"><img src="/var/gallery/storage/images/abstract/misc/green_clover/1145-1-eng-GB/green_clover_small_h.jpg" width="173" height="130"   border="0" alt="Gren clover" title="Gren clover" /></a>              <p class="caption"><p>
Actually it's called gaukesyre
</p>
</p>
        </td>
                    </tr>
    </table>
        </div>
    </div>

    <div id="news">
    <div class="list">
    
    <h2>Latest news</h2>
    <ul>
                <li>
            <a href=news/added_new_gallery>Added new gallery</a>
            <div class="date">
                (14/11/2003)
            </div>
        </li>
            </ul>
        </div>
    </div>

    <div id="comment">
    <div class="list">
    
    <h2>Latest comments</h2>
    <ul>
            </ul>
        </div>
    </div>

</div><?php
$contentData = ob_get_contents();
ob_end_clean();

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentInfo['section_id'] );
?>
