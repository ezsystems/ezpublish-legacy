<?php
// URI:       design:node/view/full.tpl
// Filename:  design/shop/override/templates/folder_frontpage.tpl
// Timestamp: 1069775372 (Tue Nov 25 16:49:32 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div class=\"folder\">\n\n<form method=\"post\" action=";

$var = "content/action";
$tpl->processOperator( "ezurl",
                       array(),
                       $rootNamespace, $currentNamespace, $var, false, false );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= ">\n\n<input type=\"hidden\" name=\"ContentNodeID\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "node_id";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n<input type=\"hidden\" name=\"ContentObjectID\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "id";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n<input type=\"hidden\" name=\"ViewMode\" value=\"full\" />\n\n<h1>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "name";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "</h1>\n\n\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "node" );
$show1 = "object";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "data_map";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "description";
$show = compiledFetchAttribute( $show, $show1 );
$tpl->processOperator( "ne",
                       array(),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "<div class=\"folderdescription\">";

$textElements = array();
$tpl->processFunction( "attribute_view_gui", $textElements,
                       false,
                       array( "attribute" => array( array( 4,
                                                          array( "",
                                                                 2,
                                                                 "node" ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "object",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "data_map",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "description",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 31,
                                    0,
                                    1010 ),
                             array( 31,
                                    62,
                                    1072 ),
                             "design/shop/override/templates/folder_frontpage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</div>";

}

$text .= "\n<div class=\"children\">";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "page_limit" => array( array( 2,
                                                                                           20,
                                                                                           false ) ),
                                                             "children" => array( array( 6,
                                                                                         array( "fetch",
                                                                                                array( array( 1,
                                                                                                              "content",
                                                                                                              false ) ),
                                                                                                array( array( 1,
                                                                                                              "tree",
                                                                                                              false ) ),
                                                                                                array( array( 6,
                                                                                                              array( "hash",
                                                                                                                     array( array( 3,
                                                                                                                                   "parent_node_id",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 2,
                                                                                                                                   2,
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "sort_by",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 6,
                                                                                                                                   array( "array",
                                                                                                                                          array( array( 6,
                                                                                                                                                        array( "array",
                                                                                                                                                               array( array( 1,
                                                                                                                                                                             "published",
                                                                                                                                                                             false ) ),
                                                                                                                                                               array( array( 2,
                                                                                                                                                                             false,
                                                                                                                                                                             false ) ) ),
                                                                                                                                                        false ) ) ),
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "limit",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 4,
                                                                                                                                   array( "",
                                                                                                                                          2,
                                                                                                                                          "page_limit" ),
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "class_filter_type",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 1,
                                                                                                                                   "include",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "class_filter_array",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 6,
                                                                                                                                   array( "array",
                                                                                                                                          array( array( 1,
                                                                                                                                                        "product",
                                                                                                                                                        false ) ) ),
                                                                                                                                   false ) ) ),
                                                                                                              false ) ) ),
                                                                                         false ) ),
                                                             "list_count" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 1,
                                                                                                                "content",
                                                                                                                false ) ),
                                                                                                  array( array( 1,
                                                                                                                "list_count",
                                                                                                                false ) ),
                                                                                                  array( array( 6,
                                                                                                                array( "hash",
                                                                                                                       array( array( 3,
                                                                                                                                     "parent_node_id",
                                                                                                                                     false ) ),
                                                                                                                       array( array( 4,
                                                                                                                                     array( "",
                                                                                                                                            2,
                                                                                                                                            "node" ),
                                                                                                                                     false ),
                                                                                                                              array( 5,
                                                                                                                                     array( array( 3,
                                                                                                                                                   "node_id",
                                                                                                                                                   false ) ),
                                                                                                                                     false ) ) ),
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 36,
                                                                    0,
                                                                    1117 ),
                                                             array( 42,
                                                                    79,
                                                                    1584 ),
                                                             "design/shop/override/templates/folder_frontpage.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "       ",
                                    array( array( 44,
                                                  64,
                                                  1653 ),
                                           array( 45,
                                                  7,
                                                  1661 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ),
                             array( 4,
                                    false,
                                    "node_view_gui",
                                    array( "view" => array( array( 3,
                                                                   "line",
                                                                   false ) ),
                                           "content_node" => array( array( 4,
                                                                           array( "Child",
                                                                                  2,
                                                                                  "item" ),
                                                                           false ) ) ),
                                    array( array( 45,
                                                  7,
                                                  1662 ),
                                           array( 45,
                                                  55,
                                                  1710 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Child",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "children" ),
                                                     false ) ),
                             "sequence" => array( array( 6,
                                                         array( "array",
                                                                array( array( 3,
                                                                              "bglight",
                                                                              false ) ),
                                                                array( array( 3,
                                                                              "bgdark",
                                                                              false ) ) ),
                                                         false ) ) ),
                       array( array( 44,
                                    0,
                                    1588 ),
                             array( 44,
                                    64,
                                    1652 ),
                             "design/shop/override/templates/folder_frontpage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "name" => array( array( 3,
                                                     "navigator",
                                                     false ) ),
                             "uri" => array( array( 1,
                                                    "design:navigator/google.tpl",
                                                    false ) ),
                             "page_uri" => array( array( 6,
                                                         array( "concat",
                                                                array( array( 1,
                                                                              "/content/view",
                                                                              false ) ),
                                                                array( array( 1,
                                                                              "/full/",
                                                                              false ) ),
                                                                array( array( 4,
                                                                              array( "",
                                                                                     2,
                                                                                     "node" ),
                                                                              false ),
                                                                       array( 5,
                                                                              array( array( 3,
                                                                                            "node_id",
                                                                                            false ) ),
                                                                              false ) ) ),
                                                         false ) ),
                             "item_count" => array( array( 4,
                                                           array( "",
                                                                  2,
                                                                  "list_count" ),
                                                           false ) ),
                             "view_parameters" => array( array( 4,
                                                                array( "",
                                                                       2,
                                                                       "view_parameters" ),
                                                                false ) ),
                             "item_limit" => array( array( 4,
                                                           array( "",
                                                                  2,
                                                                  "page_limit" ),
                                                           false ) ) ),
                       array( array( 48,
                                    0,
                                    1725 ),
                             array( 53,
                                    26,
                                    1936 ),
                             "design/shop/override/templates/folder_frontpage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "</div>\n\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "content_object" => array( array( 4,
                                                                                                          array( "",
                                                                                                                 2,
                                                                                                                 "node" ),
                                                                                                          false ),
                                                                                                   array( 5,
                                                                                                          array( array( 3,
                                                                                                                        "object",
                                                                                                                        false ) ),
                                                                                                          false ) ),
                                                                        "content_version" => array( array( 4,
                                                                                                           array( "",
                                                                                                                  2,
                                                                                                                  "node" ),
                                                                                                           false ),
                                                                                                    array( 5,
                                                                                                           array( array( 3,
                                                                                                                         "contentobject_version_object",
                                                                                                                         false ) ),
                                                                                                           false ) ),
                                                                        "node_name" => array( array( 4,
                                                                                                     array( "",
                                                                                                            2,
                                                                                                            "node" ),
                                                                                                     false ),
                                                                                              array( 5,
                                                                                                     array( array( 3,
                                                                                                                   "name",
                                                                                                                   false ) ),
                                                                                                     false ) ) ),
                                                                 array( array( 59,
                                                                               0,
                                                                               1956 ),
                                                                        array( 61,
                                                                               29,
                                                                               2081 ),
                                                                        "design/shop/override/templates/folder_frontpage.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

$text .= "\n<div class=\"buttonblock\">\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "content_object" );
$show1 = "can_create";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "         <input type=\"hidden\" name=\"NodeID\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "node_id";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n         <select name=\"ClassID\">\n              ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "              <option value=\"",
                                    array( array( 68,
                                                  77,
                                                  2336 ),
                                           array( 69,
                                                  29,
                                                  2366 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "id",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 69,
                                                  29,
                                                  2367 ),
                                           array( 69,
                                                  38,
                                                  2376 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">",
                                    array( array( 69,
                                                  38,
                                                  2377 ),
                                           array( 69,
                                                  40,
                                                  2379 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "name",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 69,
                                                  40,
                                                  2380 ),
                                           array( 69,
                                                  56,
                                                  2396 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ),
                             array( 2,
                                    false,
                                    "</option>\n              ",
                                    array( array( 69,
                                                  56,
                                                  2397 ),
                                           array( 70,
                                                  14,
                                                  2421 ),
                                           "design/shop/override/templates/folder_frontpage.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Classes",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "content_object" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "can_create_class_list",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 68,
                                    14,
                                    2272 ),
                             array( 68,
                                    77,
                                    2335 ),
                             "design/shop/override/templates/folder_frontpage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "         </select>\n         <input class=\"button\" type=\"submit\" name=\"NewButton\" value=\"";

$var = "Create here";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/node/v\niew",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />";

}

$text .= "</div>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );

$text .= "\n</form>\n</div>";


?>
