<?php
// URI:       design:node/view/full.tpl
// Filename:  design/blog/override/templates/frontpagefolder.tpl
// Timestamp: 1069775359 (Tue Nov 25 16:49:19 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/blog/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<h1>";

$var = "Latest blogs";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/blog/layout",
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

$text .= "</h1>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "log_list" => array( array( 6,
                                                                                         array( "fetch",
                                                                                                array( array( 3,
                                                                                                              "content",
                                                                                                              false ) ),
                                                                                                array( array( 3,
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
                                                                                                                                                               array( array( 3,
                                                                                                                                                                             "published",
                                                                                                                                                                             false ) ),
                                                                                                                                                               array( array( 2,
                                                                                                                                                                             false,
                                                                                                                                                                             false ) ) ),
                                                                                                                                                        false ) ) ),
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
                                                                                                                                                        "log",
                                                                                                                                                        false ) ) ),
                                                                                                                                   false ) ) ),
                                                                                                              false ) ) ),
                                                                                         false ) ) ),
                                                      array( array( 3,
                                                                    0,
                                                                    55 ),
                                                             array( 6,
                                                                    80,
                                                                    357 ),
                                                             "design/blog/override/templates/frontpagefolder.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "    ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "       ",
                                    array( array( 7,
                                                  35,
                                                  396 ),
                                           array( 8,
                                                  7,
                                                  404 ),
                                           "design/blog/override/templates/frontpagefolder.tpl" ) ),
                             array( 4,
                                    false,
                                    "node_view_gui",
                                    array( "view" => array( array( 3,
                                                                   "line",
                                                                   false ) ),
                                           "content_node" => array( array( 4,
                                                                           array( "",
                                                                                  3,
                                                                                  "item" ),
                                                                           false ) ) ),
                                    array( array( 8,
                                                  7,
                                                  405 ),
                                           array( 8,
                                                  50,
                                                  448 ),
                                           "design/blog/override/templates/frontpagefolder.tpl" ) ),
                             array( 2,
                                    false,
                                    "    ",
                                    array( array( 8,
                                                  50,
                                                  449 ),
                                           array( 9,
                                                  4,
                                                  454 ),
                                           "design/blog/override/templates/frontpagefolder.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Log",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "log_list" ),
                                                     false ) ) ),
                       array( array( 7,
                                    4,
                                    364 ),
                             array( 7,
                                    35,
                                    395 ),
                             "design/blog/override/templates/frontpagefolder.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "\n";


?>
