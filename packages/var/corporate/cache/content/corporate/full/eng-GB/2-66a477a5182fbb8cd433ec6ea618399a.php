<?php
$contentInfo = array( "site_design" => "corporate",
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
$contentPath = array( array( "text" => "Corporate",
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

<form method="post" action="/content/action">

<input type="hidden" name="ContentNodeID" value="2" />
<input type="hidden" name="ContentObjectID" value="1" />
<input type="hidden" name="ViewMode" value="full" />

<h1>Corporate</h1>


<div class="object_content"><p>
Welcome to the website of MyCompany. Here you can read about our company, our products and services. Take a tour through our digitised archive, and find out more about the comapny and what we offer. 
</p>
<p>
Our mission is to keep our customers in touch with the latest updates, releases and products.
</p>
</div>

<div class="children"> 

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
