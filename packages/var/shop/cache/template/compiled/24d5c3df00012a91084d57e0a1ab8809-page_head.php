<?php
// URI:       design:page_head.tpl
// Filename:  design/standard/templates/page_head.tpl
// Timestamp: 1065702791 (Thu Oct 9 14:33:11 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "enable_help" => array( array( 2,
                                                                                                       true,
                                                                                                       false ) ),
                                                                        "enable_link" => array( array( 2,
                                                                                                       true,
                                                                                                       false ) ) ),
                                                                 array( array( 2,
                                                                               0,
                                                                               31 ),
                                                                        array( 2,
                                                                               45,
                                                                               76 ),
                                                                        "design/standard/templates/page_head.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

if ( $currentNamespace != '' )
    $name = $currentNamespace . ':' . "Path";
else
    $name = "Path";
include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "path" => array( array( 4,
                                                                                     array( "",
                                                                                            2,
                                                                                            "module_result" ),
                                                                                     false ),
                                                                              array( 5,
                                                                                     array( array( 3,
                                                                                                   "path",
                                                                                                   false ) ),
                                                                                     false ) ),
                                                             "reverse_path" => array( array( 6,
                                                                                             array( "array" ),
                                                                                             false ) ) ),
                                                      array( array( 4,
                                                                    0,
                                                                    80 ),
                                                             array( 6,
                                                                    25,
                                                                    149 ),
                                                             "design/standard/templates/page_head.tpl" ),
                                                      $name,
                                                      $rootNamespace, $currentNamespace );

$text .= "  ";

$tpl->processOperator( "is_set",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "module_result" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "title_path",
                                                          false ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "    ";

$textElements = array();
$tpl->processFunction( "set", $textElements,
                       false,
                       array( "path" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "module_result" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "title_path",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 8,
                                    4,
                                    207 ),
                             array( 8,
                                    38,
                                    241 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "  ";

}

$text .= "  ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "    ",
                                    array( array( 10,
                                                  21,
                                                  279 ),
                                           array( 11,
                                                  4,
                                                  284 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 4,
                                    false,
                                    "set",
                                    array( "reverse_path" => array( array( 4,
                                                                           array( "",
                                                                                  3,
                                                                                  "reverse_path" ),
                                                                           false ),
                                                                    array( 6,
                                                                           array( "array_prepend",
                                                                                  array( array( 4,
                                                                                                array( "",
                                                                                                       3,
                                                                                                       "item" ),
                                                                                                false ) ) ),
                                                                           false ) ) ),
                                    array( array( 11,
                                                  4,
                                                  285 ),
                                           array( 11,
                                                  57,
                                                  338 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "  ",
                                    array( array( 11,
                                                  57,
                                                  339 ),
                                           array( 12,
                                                  2,
                                                  342 ),
                                           "design/standard/templates/page_head.tpl" ) ) ),
                       array( "loop" => array( array( 4,
                                                     array( "",
                                                            3,
                                                            "path" ),
                                                     false ) ) ),
                       array( array( 10,
                                    2,
                                    259 ),
                             array( 10,
                                    21,
                                    278 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$textElements = array();
$tpl->processFunction( "set-block", $textElements,
                       array( array( 4,
                                    array( array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "",
                                                                       3,
                                                                       "item" ),
                                                                false ),
                                                         array( 5,
                                                                array( array( 3,
                                                                              "text",
                                                                              false ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "wash" ),
                                                                false ) ),
                                                  array( array( 15,
                                                                31,
                                                                431 ),
                                                         array( 15,
                                                                47,
                                                                447 ),
                                                         "design/standard/templates/page_head.tpl" ) ),
                                           array( 4,
                                                  array( array( 2,
                                                                false,
                                                                " / ",
                                                                array( array( 15,
                                                                              56,
                                                                              459 ),
                                                                       array( 15,
                                                                              59,
                                                                              462 ),
                                                                       "design/standard/templates/page_head.tpl" ) ) ),
                                                  "delimiter",
                                                  array(),
                                                  array( array( 15,
                                                                47,
                                                                449 ),
                                                         array( 15,
                                                                56,
                                                                458 ),
                                                         "design/standard/templates/page_head.tpl" ) ) ),
                                    "section",
                                    array( "loop" => array( array( 4,
                                                                   array( "Path",
                                                                          2,
                                                                          "reverse_path" ),
                                                                   false ) ) ),
                                    array( array( 15,
                                                  0,
                                                  398 ),
                                           array( 15,
                                                  31,
                                                  429 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    " - ",
                                    array( array( 15,
                                                  75,
                                                  484 ),
                                           array( 15,
                                                  78,
                                                  487 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "site" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "title",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 15,
                                                  78,
                                                  488 ),
                                           array( 15,
                                                  94,
                                                  504 ),
                                           "design/standard/templates/page_head.tpl" ) ) ),
                       array( "scope" => array( array( 3,
                                                      "root",
                                                      false ) ),
                             "variable" => array( array( 3,
                                                         "site_title",
                                                         false ) ) ),
                       array( array( 14,
                                    0,
                                    355 ),
                             array( 14,
                                    40,
                                    395 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "\n    <title>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "site_title" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "</title>\n\n    ";

$tpl->processOperator( "and",
                       array( array( array( 6,
                                            array( "is_set",
                                                   array( array( 4,
                                                                 array( "Header",
                                                                        1,
                                                                        "extra_data" ),
                                                                 false ) ) ),
                                            false ) ),
                              array( array( 6,
                                            array( "is_array",
                                                   array( array( 4,
                                                                 array( "Header",
                                                                        1,
                                                                        "extra_data" ),
                                                                 false ) ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "      ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "      ",
                                    array( array( 23,
                                                  53,
                                                  699 ),
                                           array( 24,
                                                  6,
                                                  706 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ) ),
                                    array( array( 24,
                                                  6,
                                                  707 ),
                                           array( 24,
                                                  12,
                                                  713 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n      ",
                                    array( array( 24,
                                                  12,
                                                  714 ),
                                           array( 25,
                                                  6,
                                                  721 ),
                                           "design/standard/templates/page_head.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "ExtraData",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "Header",
                                                            1,
                                                            "extra_data" ),
                                                     false ) ) ),
                       array( array( 23,
                                    6,
                                    651 ),
                             array( 23,
                                    53,
                                    698 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    ";

}

$text .= "\n    \n    ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "site" );
$show1 = "redirect";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "    <meta http-equiv=\"Refresh\" content=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "site" );
$var1 = "redirect";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "timer";
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

$text .= "; URL=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "site" );
$var1 = "redirect";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "location";
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

$text .= "\" />\n\n    ";

}

$text .= "\n    ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "    <meta http-equiv=\"",
                                    array( array( 34,
                                                  43,
                                                  990 ),
                                           array( 35,
                                                  22,
                                                  1013 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "HTTP",
                                                         2,
                                                         "key" ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 35,
                                                  22,
                                                  1014 ),
                                           array( 35,
                                                  36,
                                                  1028 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" content=\"",
                                    array( array( 35,
                                                  36,
                                                  1029 ),
                                           array( 35,
                                                  47,
                                                  1040 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "HTTP",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 35,
                                                  47,
                                                  1041 ),
                                           array( 35,
                                                  62,
                                                  1056 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" />\n\n    ",
                                    array( array( 35,
                                                  62,
                                                  1057 ),
                                           array( 37,
                                                  4,
                                                  1067 ),
                                           "design/standard/templates/page_head.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "HTTP",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "site" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "http_equiv",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 34,
                                    4,
                                    950 ),
                             array( 34,
                                    43,
                                    989 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "    <meta name=\"",
                                    array( array( 39,
                                                  37,
                                                  1118 ),
                                           array( 40,
                                                  16,
                                                  1135 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "meta",
                                                         2,
                                                         "key" ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 40,
                                                  16,
                                                  1136 ),
                                           array( 40,
                                                  30,
                                                  1150 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" content=\"",
                                    array( array( 40,
                                                  30,
                                                  1151 ),
                                           array( 40,
                                                  41,
                                                  1162 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "meta",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 40,
                                                  41,
                                                  1163 ),
                                           array( 40,
                                                  56,
                                                  1178 ),
                                           "design/standard/templates/page_head.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" />\n\n    ",
                                    array( array( 40,
                                                  56,
                                                  1179 ),
                                           array( 42,
                                                  4,
                                                  1189 ),
                                           "design/standard/templates/page_head.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "meta",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "site" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "meta",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 39,
                                    4,
                                    1084 ),
                             array( 39,
                                    37,
                                    1117 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <meta name=\"MSSmartTagsPreventParsing\" content=\"TRUE\" />\n    <meta name=\"generator\" content=\"eZ publish\" />\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "enable_link" );

if ( $show )
{

unset( $show );

$text .= "    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:link.tpl",
                                                    false ) ),
                             "enable_help" => array( array( 4,
                                                            array( "",
                                                                   2,
                                                                   "enable_help" ),
                                                            false ) ),
                             "enable_link" => array( array( 4,
                                                            array( "",
                                                                   2,
                                                                   "enable_link" ),
                                                            false ) ) ),
                       array( array( 48,
                                    4,
                                    1347 ),
                             array( 48,
                                    83,
                                    1426 ),
                             "design/standard/templates/page_head.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

}

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );


?>
