<?php
$contentInfo = array( "site_design" => "shop",
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
$contentPath = array( array( "text" => "Shop",
                             "url" => false,
                             "url_alias" => false,
                             "node_id" => "2" ) );
$nodeID = "2";
$sectionID = "1";
$navigationPartIdentifier = "ezcontentnavigationpart";
// Timestamp for the cache format
$eZContentCacheCodeDate = 1054816011;

ob_start();

?><div class="folder">

<form method="post" action="/content/action">

<input type="hidden" name="ContentNodeID" value="2" />
<input type="hidden" name="ContentObjectID" value="1" />
<input type="hidden" name="ViewMode" value="full" />

<h1>Shop</h1>



<div class="children">       <div class="productline">

<h2><a href="/products/dvd/action_dvd">Action DVD</a></h2>

<div class="listimage">
<a href="/products/dvd/action_dvd">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
Clips from the best action movies from the leading actors from Hollywood. 3 hours of non-stop action from back to back.
</p>

<div class="price">
    <p>Price £ 12.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/dvd/action_dvd">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/dvd/music_dvd">Music DVD</a></h2>

<div class="listimage">
<a href="/products/dvd/music_dvd">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
A collection of music from the year 2003. The best of the best. All top of the charts from Top 100.
</p>

<div class="price">
    <p>Price £ 6.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/dvd/music_dvd">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/books/ez_publish_basics">eZ publish basics</a></h2>

<div class="listimage">
<a href="/products/books/ez_publish_basics">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
Everything you need to know about eZ publish. All steps from download to the finished site.
</p>

<div class="price">
    <p>Price £ 9.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/books/ez_publish_basics">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/books/summer_book">Summer book</a></h2>

<div class="listimage">
<a href="/products/books/summer_book">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
The book is about all the colors and smells of summer. The book is packed with picures of the beautiful landscape in Norway.
</p>

<div class="price">
    <p>Price £ 79.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/books/summer_book">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/cars/ferrari">Ferrari</a></h2>

<div class="listimage">
<a href="/products/cars/ferrari">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
Enjoy the feeling. It's nothing more to say. If you have ever tried one you never want to leave and you<br />re a fan forever.<br />
</p>

<div class="price">
    <p>Price £ 200,000.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/cars/ferrari">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/cars/troll">Troll</a></h2>

<div class="listimage">
<a href="/products/cars/troll">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
Troll was the first - and so far the only - car made in Norway. Only five cars left the factory in total. 
</p>

<div class="price">
    <p>Price £ 980.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/cars/troll">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/cords/5_meter_cord">5 meter cord</a></h2>

<div class="listimage">
<a href="/products/cords/5_meter_cord">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
This cord is five meters long and works for all machines
</p>

<div class="price">
    <p>Price £ 13.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/cords/5_meter_cord">Read more</a></p>
</div>

</div>
       <div class="productline">

<h2><a href="/products/cords/1_meter_cord">1 meter cord</a></h2>

<div class="listimage">
<a href="/products/cords/1_meter_cord">         <img src="" width="" height=""   border="0" alt="" title="" />  </a>
</div>
<p>
This cord is one meter long and works for all machines
</p>

<div class="price">
    <p>Price £ 9.00 <br/></p>
</div>

<div class="readmore">
    <p><a href="/products/cords/1_meter_cord">Read more</a></p>
</div>

</div>
 

 </div>


<div class="buttonblock">
</div>

</form>
</div><?php
$contentData = ob_get_contents();
ob_end_clean();

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentInfo['section_id'] );
?>
