<?php
$contentInfo = array( "site_design" => "blog",
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
$contentPath = array( array( "text" => "Blog",
                             "url" => false,
                             "url_alias" => false,
                             "node_id" => "2" ) );
$nodeID = "2";
$sectionID = "1";
$navigationPartIdentifier = "ezcontentnavigationpart";
// Timestamp for the cache format
$eZContentCacheCodeDate = 1054816011;

ob_start();

?><h1>Latest blogs</h1>
           <div class="log">

  <h2>Tuesday | 25 November 2003</h2>
  <div class="logentry">
    <h3><a href="/content/view/full/194">Party!</a></h3>
    <p>
I was invited to a party at a friends house this weekend. And I was told that the girl of my dreams will also be there. 
</p>
    <div class="byline">
       <p>
          3:44pm in Personal | 
                        <a href="/blogs/personal/party">1 comments</a>
            
       </p>
    </div>
  </div>

</div>           <div class="log">

  <h2>Tuesday | 25 November 2003</h2>
  <div class="logentry">
    <h3><a href="/content/view/full/185">Finally I got it</a></h3>
    <p>
After such a long time with pulling my hair I finally got the latest edition of my software working. Perhaps the way to fortune in not that long anymore?
</p>
    <div class="byline">
       <p>
          3:25pm in Computers | 
                        <a href="/blogs/computers/finally_i_got_it">1 comments</a>
            
       </p>
    </div>
  </div>

</div>           <div class="log">

  <h2>Tuesday | 25 November 2003</h2>
  <div class="logentry">
    <h3><a href="/content/view/full/184">Tonight I was at the movies</a></h3>
    <p>
My first date with Mia! We went to see the romantic Matrix:-) 
</p>
<p>
It must have been a success since she let me follow here home. 
</p>
    <div class="byline">
       <p>
          3:24pm in Entertainment | 
                        Comments disabled
            
       </p>
    </div>
  </div>

</div>           <div class="log">

  <h2>Tuesday | 25 November 2003</h2>
  <div class="logentry">
    <h3><a href="/content/view/full/183">I overslept again</a></h3>
    <p>
Somehow I must have turned of the alarm in my sleep. I woke up three hours to late and missed a meeting with what will hopefully be my girlfriend. She was not very happy about this.  
</p>
    <div class="byline">
       <p>
          3:22pm in Personal | 
                        Comments disabled
            
       </p>
    </div>
  </div>

</div>           <div class="log">

  <h2>Thursday | 13 November 2003</h2>
  <div class="logentry">
    <h3><a href="/content/view/full/155">Today I got my new car!</a></h3>
    <p>
It is an old Volkswagen Beetle from 1982. It has a lot more charm that it cost me money. 
</p>
<p>
I bought it from a friend for £30 and even got the old original wheels. 
</p>
    <div class="byline">
       <p>
          9:12am in Personal | 
                        <a href="/blogs/personal/today_i_got_my_new_car">0 comments</a>
            
       </p>
    </div>
  </div>

</div>    
<?php
$contentData = ob_get_contents();
ob_end_clean();

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentInfo['section_id'] );
?>
