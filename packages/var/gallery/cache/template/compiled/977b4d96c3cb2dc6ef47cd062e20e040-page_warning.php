<?php
// URI:       design:page_warning.tpl
// Filename:  design/standard/templates/page_warning.tpl
// Timestamp: 1062422345 (Mon Sep 1 15:19:05 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/gallery/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "warning_list" );

if ( $show )
{

unset( $show );

$text .= "<div class=\"error\">\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n  ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<tr>\n    <td>\n      <h3 class=\"error\">",
                                    array( array( 4,
                                                  41,
                                                  156 ),
                                           array( 7,
                                                  24,
                                                  195 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Warning",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "error",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "type",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 7,
                                                  24,
                                                  196 ),
                                           array( 7,
                                                  48,
                                                  220 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 2,
                                    false,
                                    " (",
                                    array( array( 7,
                                                  48,
                                                  221 ),
                                           array( 7,
                                                  50,
                                                  223 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Warning",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "error",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "number",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 7,
                                                  50,
                                                  224 ),
                                           array( 7,
                                                  76,
                                                  250 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 2,
                                    false,
                                    ")</h3>\n      <ul class=\"error\">\n        <li>",
                                    array( array( 7,
                                                  76,
                                                  251 ),
                                           array( 9,
                                                  12,
                                                  295 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Warning",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "text",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 9,
                                                  12,
                                                  296 ),
                                           array( 9,
                                                  30,
                                                  314 ),
                                           "design/standard/templates/page_warning.tpl" ) ),
                             array( 2,
                                    false,
                                    "</li>\n      </ul>\n    </td>\n</tr>\n  ",
                                    array( array( 9,
                                                  30,
                                                  315 ),
                                           array( 13,
                                                  2,
                                                  351 ),
                                           "design/standard/templates/page_warning.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Warning",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "warning_list" ),
                                                     false ) ) ),
                       array( array( 4,
                                    2,
                                    116 ),
                             array( 4,
                                    41,
                                    155 ),
                             "design/standard/templates/page_warning.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</table>\n</div>";

}


?>
