<?php
$contentInfo = array( "site_design" => "intranet",
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
                      "role_list" => array( "2" ),
                      "discount_list" => array() );
$contentPath = array( array( "text" => "Intranet",
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
<h1>Latest news</h1>

<div id="largenews">   <div class="child">
   <div class="articleline">

<h2>New employee</h2>

<div class="byline">
  <p>
  (Tuesday 25 November 2003 4:27:23 pm)
  </p>
</div>



<div class="intro">
  <p>
We've just got a new member of our team.
</p>
</div>


<div class="readmore">
<a href="/news/staff_news/new_employee">Read more</a>
</div>

</div>
   </div></div>


<div id="mediumnews">
<table>
<tr>
           <td>
          <div class="child">
          <div class="articleline">

<h2>Annual report</h2>

<div class="byline">
  <p>
  (Tuesday 25 November 2003 4:26:08 pm)
  </p>
</div>



<div class="intro">
  <p>
We've just released our annual report.
</p>
</div>


<div class="readmore">
<a href="/news/reports/annual_report">Read more</a>
</div>

</div>
          </div>
       </td>
               <td>
          <div class="child">
          <div class="articleline">

<h2>New business cards</h2>

<div class="byline">
  <p>
  (Tuesday 25 November 2003 4:25:25 pm)
  </p>
</div>



<div class="intro">
  <p>
The new business cards just arrived.<br />
</p>
</div>


<div class="readmore">
<a href="/news/off_topic/new_business_cards">Read more</a>
</div>

</div>
          </div>
       </td>
        </tr>
<tr>      
           <td>
          <div class="child">
          <div class="articleline">

<h2>New business cards</h2>

<div class="byline">
  <p>
  (Tuesday 25 November 2003 4:25:25 pm)
  </p>
</div>



<div class="intro">
  <p>
The new business cards just arrived.<br />
</p>
</div>


<div class="readmore">
<a href="/news/reports/new_business_cards">Read more</a>
</div>

</div>
          </div>
       </td>
        </tr>
</table>
</div>

</div>
<?php
$contentData = ob_get_contents();
ob_end_clean();

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $contentInfo['section_id'] );
?>
