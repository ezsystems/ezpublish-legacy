<?php
// URI:       design:pagelayout.tpl
// Filename:  design/intranet/templates/pagelayout.tpl
// Timestamp: 1069844105 (Wed Nov 26 11:55:05 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"no\" lang=\"no\">\n\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "pagedesign" => array( array( 6,
                                                                                           array( "fetch_alias",
                                                                                                  array( array( 3,
                                                                                                                "by_identifier",
                                                                                                                false ) ),
                                                                                                  array( array( 6,
                                                                                                                array( "hash",
                                                                                                                       array( array( 3,
                                                                                                                                     "attr_id",
                                                                                                                                     false ) ),
                                                                                                                       array( array( 3,
                                                                                                                                     "intranet_package",
                                                                                                                                     false ) ) ),
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 7,
                                                                    0,
                                                                    227 ),
                                                             array( 7,
                                                                    72,
                                                                    299 ),
                                                             "design/intranet/templates/pagelayout.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "\n<head>\n";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:page_head.tpl",
                                                    false ) ),
                             "enable_glossary" => array( array( 2,
                                                                false,
                                                                false ) ),
                             "enable_help" => array( array( 2,
                                                            false,
                                                            false ) ) ),
                       array( array( 11,
                                    0,
                                    396 ),
                             array( 11,
                                    78,
                                    474 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n\n<style>\n    @import url(";

$var = "stylesheets/core.css";
$tpl->processOperator( "ezdesign",
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

$text .= ");\n    @import url(";

$var = "stylesheets/intranet.css";
$tpl->processOperator( "ezdesign",
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

$text .= ");\n   \n</style>\n</head>\n\n\n<body>\n<div id=\"container\">\n    \n    <div id=\"topbox\">\n        <form action=";

$var = "/content/advancedsearch/";
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

$text .= " method=\"get\">\n	<div id=\"logo\">\n        ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult2 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "content" => array( array( 4,
                                                                                        array( "",
                                                                                               2,
                                                                                               "pagedesign" ),
                                                                                        false ),
                                                                                 array( 5,
                                                                                        array( array( 3,
                                                                                                      "data_map",
                                                                                                      false ) ),
                                                                                        false ),
                                                                                 array( 5,
                                                                                        array( array( 3,
                                                                                                      "image",
                                                                                                      false ) ),
                                                                                        false ),
                                                                                 array( 5,
                                                                                        array( array( 3,
                                                                                                      "content",
                                                                                                      false ) ),
                                                                                        false ) ) ),
                                                      array( array( 28,
                                                                    8,
                                                                    1002 ),
                                                             array( 28,
                                                                    54,
                                                                    1048 ),
                                                             "design/intranet/templates/pagelayout.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "	    <a href=";

$var = "/";
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

$text .= "><img src=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "content" );
$var1 = "logo";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "full_path";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "ezroot",
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

$text .= " /></a> \n        ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult2 );

$text .= "	</div>\n	<div id=\"searchbox\">\n                <select name=\"SearchContentClassID\">\n		<option value=\"-1\"";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "search_contentclass_id" ),
                                            false ) ),
                              array( array( 2,
                                            -1,
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "selected";

}

$text .= " />Whole site</option>\n		<option value=\"2\" ";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "search_contentclass_id" ),
                                            false ) ),
                              array( array( 2,
                                            2,
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "selected";

}

$text .= " />News</option>\n		<option value=\"16\" ";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "search_contentclass_id" ),
                                            false ) ),
                              array( array( 2,
                                            16,
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "selected";

}

$text .= " />Companies</option>\n		<option value=\"17\" ";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "search_contentclass_id" ),
                                            false ) ),
                              array( array( 2,
                                            17,
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "selected";

}

$text .= " />Persons</option>	\n		</select>\n	        <input type=\"text\" size=\"15\" name=\"SearchText\" id=\"Search\" value=\"\" />\n	        <input class=\"searchbutton\" name=\"SearchButton\" type=\"submit\" value=\"";

$var = "Search";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
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

$text .= "\" />\n	</div>\n	<div id=\"sitelogo\">\n	&nbsp;\n	</div>\n	</form>\n    </div>\n    ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "    \n    <div id=\"mainmenu\">\n       <div class=\"design\">\n\n	\n	";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "top_menu" => array( array( 6,
                                                                                         array( "fetch",
                                                                                                array( array( 3,
                                                                                                              "content",
                                                                                                              false ) ),
                                                                                                array( array( 3,
                                                                                                              "list",
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
                                                                                                                                          array( array( 3,
                                                                                                                                                        "priority",
                                                                                                                                                        false ) ),
                                                                                                                                          array( array( 2,
                                                                                                                                                        true,
                                                                                                                                                        false ) ) ),
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "class_filter_type",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "include",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 3,
                                                                                                                                   "class_filter_array",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 6,
                                                                                                                                   array( "array",
                                                                                                                                          array( array( 1,
                                                                                                                                                        "folder",
                                                                                                                                                        false ) ) ),
                                                                                                                                   false ) ) ),
                                                                                                              false ) ) ),
                                                                                         false ) ) ),
                                                      array( array( 54,
                                                                    1,
                                                                    2051 ),
                                                             array( 57,
                                                                    51,
                                                                    2246 ),
                                                             "design/intranet/templates/pagelayout.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "\n        <ul>\n	";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "	    <li>\n	        <a href=",
                                    array( array( 60,
                                                  33,
                                                  2297 ),
                                           array( 62,
                                                  17,
                                                  2325 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "url_alias",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 62,
                                                  17,
                                                  2326 ),
                                           array( 62,
                                                  39,
                                                  2348 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 62,
                                                  39,
                                                  2349 ),
                                           array( 62,
                                                  40,
                                                  2350 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
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
                                    array( array( 62,
                                                  40,
                                                  2351 ),
                                           array( 62,
                                                  56,
                                                  2367 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a>\n	    </li>\n	",
                                    array( array( 62,
                                                  56,
                                                  2368 ),
                                           array( 64,
                                                  1,
                                                  2385 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "item",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "top_menu" ),
                                                     false ) ) ),
                       array( array( 60,
                                    1,
                                    2264 ),
                             array( 60,
                                    33,
                                    2296 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "        </ul>\n	";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "	    \n       </div>\n    </div>\n    \n\n    <div id=\"pathline\">\n    \n    <div id=\"mainpath\">\n	";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "            <div class=\"item\">  \n	    ",
                                    array( array( 75,
                                                  43,
                                                  2610 ),
                                           array( 77,
                                                  5,
                                                  2649 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "	    <a href=",
                                                  array( array( 77,
                                                                32,
                                                                2678 ),
                                                         array( 78,
                                                                13,
                                                                2692 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "Path",
                                                                       2,
                                                                       "item" ),
                                                                false ),
                                                         array( 5,
                                                                array( array( 3,
                                                                              "url",
                                                                              false ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "ezurl" ),
                                                                false ) ),
                                                  array( array( 78,
                                                                13,
                                                                2693 ),
                                                         array( 78,
                                                                33,
                                                                2713 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  ">",
                                                  array( array( 78,
                                                                33,
                                                                2714 ),
                                                         array( 78,
                                                                34,
                                                                2715 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "Path",
                                                                       2,
                                                                       "item" ),
                                                                false ),
                                                         array( 5,
                                                                array( array( 3,
                                                                              "text",
                                                                              false ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "shorten",
                                                                       array( array( 2,
                                                                                     18,
                                                                                     false ) ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "wash" ),
                                                                false ) ),
                                                  array( array( 78,
                                                                34,
                                                                2716 ),
                                                         array( 78,
                                                                66,
                                                                2748 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "</a>\n	    ",
                                                  array( array( 78,
                                                                66,
                                                                2749 ),
                                                         array( 79,
                                                                5,
                                                                2759 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 4,
                                                  false,
                                                  "section-else",
                                                  array(),
                                                  array( array( 79,
                                                                5,
                                                                2760 ),
                                                         array( 79,
                                                                17,
                                                                2772 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "	    ",
                                                  array( array( 79,
                                                                17,
                                                                2773 ),
                                                         array( 80,
                                                                5,
                                                                2779 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "Path",
                                                                       2,
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
                                                  array( array( 80,
                                                                5,
                                                                2780 ),
                                                         array( 80,
                                                                25,
                                                                2800 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\n	    ",
                                                  array( array( 80,
                                                                25,
                                                                2801 ),
                                                         array( 81,
                                                                5,
                                                                2807 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "Path",
                                                                          2,
                                                                          "item" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "url",
                                                                                 false ) ),
                                                                   false ) ) ),
                                    array( array( 77,
                                                  5,
                                                  2650 ),
                                           array( 77,
                                                  32,
                                                  2677 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "            </div>   \n	    ",
                                    array( array( 81,
                                                  12,
                                                  2817 ),
                                           array( 83,
                                                  5,
                                                  2845 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "            <div class=\"delimiter\">  \n	    /\n            </div>   \n	    ",
                                                  array( array( 83,
                                                                14,
                                                                2856 ),
                                                         array( 87,
                                                                5,
                                                                2929 ),
                                                         "design/intranet/templates/pagelayout.tpl" ) ) ),
                                    "delimiter",
                                    array(),
                                    array( array( 83,
                                                  5,
                                                  2846 ),
                                           array( 83,
                                                  14,
                                                  2855 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "	",
                                    array( array( 87,
                                                  14,
                                                  2941 ),
                                           array( 88,
                                                  1,
                                                  2943 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Path",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "module_result" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "path",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 75,
                                    1,
                                    2567 ),
                             array( 75,
                                    43,
                                    2609 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    </div>\n    \n    \n    \n    <div id=\"login\">\n    ";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "current_user" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "is_logged_in",
                                                          false ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "    <a href=";

$var = "/user/login";
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

$text .= ">";

$var = "login";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/shop/layout",
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

$text .= "</a>\n    ";

}
else
{

unset( $show );

$text .= "    <a href=";

$var = "/user/logout";
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

$text .= ">";

$var = "logout";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/shop/layout",
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

$text .= "</a> ( ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "current_user" );
$var1 = "contentobject";
$var = compiledFetchAttribute( $var, $var1 );
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

$text .= " )\n    ";

}

$text .= "    </div>\n    \n\n    </div>\n    \n    \n    <div id=\"date\">\n    ";

$tpl->processOperator( "currentdate",
                       array(),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "date",
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

$text .= "\n    </div>\n    \n    \n   \n    \n    <div id=\"mainframe\">\n\n    \n\n    <div id=\"maincontent\">\n       <div class=\"design\">\n       ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "module_result" );
$var1 = "content";
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

$text .= "    \n       </div>\n    </div>\n\n\n    \n    <div id=\"submenu\">\n       <div class=\"design\">\n          <div id=\"navigation\">\n           <h3>Navigation</h3>\n           ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "sub_menu" => array( array( 6,
                                                                                         array( "treemenu",
                                                                                                array( array( 4,
                                                                                                              array( "",
                                                                                                                     2,
                                                                                                                     "module_result" ),
                                                                                                              false ),
                                                                                                       array( 5,
                                                                                                              array( array( 3,
                                                                                                                            "path",
                                                                                                                            false ) ),
                                                                                                              false ) ),
                                                                                                array( array( 4,
                                                                                                              array( "",
                                                                                                                     2,
                                                                                                                     "module_result" ),
                                                                                                              false ),
                                                                                                       array( 5,
                                                                                                              array( array( 3,
                                                                                                                            "node_id",
                                                                                                                            false ) ),
                                                                                                              false ) ),
                                                                                                array( array( 6,
                                                                                                              array( "array",
                                                                                                                     array( array( 1,
                                                                                                                                   "folder",
                                                                                                                                   false ) ),
                                                                                                                     array( array( 1,
                                                                                                                                   "info_page",
                                                                                                                                   false ) ) ),
                                                                                                              false ) ),
                                                                                                array( array( 2,
                                                                                                              1,
                                                                                                              false ) ),
                                                                                                array( array( 2,
                                                                                                              3,
                                                                                                              false ) ) ),
                                                                                         false ) ) ),
                                                      array( array( 128,
                                                                    11,
                                                                    3846 ),
                                                             array( 128,
                                                                    110,
                                                                    3945 ),
                                                             "design/intranet/templates/pagelayout.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "           <ul>\n               ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "                <li class=\"level_",
                                    array( array( 130,
                                                  47,
                                                  4012 ),
                                           array( 131,
                                                  33,
                                                  4046 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "level",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 131,
                                                  33,
                                                  4047 ),
                                           array( 131,
                                                  45,
                                                  4059 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "\"><a href=",
                                    array( array( 131,
                                                  45,
                                                  4060 ),
                                           array( 131,
                                                  55,
                                                  4070 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "url_alias",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 131,
                                                  55,
                                                  4071 ),
                                           array( 131,
                                                  77,
                                                  4093 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 131,
                                                  77,
                                                  4094 ),
                                           array( 131,
                                                  78,
                                                  4095 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
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
                                                  false ) ),
                                    array( array( 131,
                                                  78,
                                                  4096 ),
                                           array( 131,
                                                  89,
                                                  4107 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a></li>\n               ",
                                    array( array( 131,
                                                  89,
                                                  4108 ),
                                           array( 132,
                                                  15,
                                                  4133 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Menu",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "sub_menu" ),
                                                     false ) ) ),
                       array( array( 130,
                                    15,
                                    3979 ),
                             array( 130,
                                    47,
                                    4011 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "           </ul>\n           ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "           </div>\n\n            ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "news_list" => array( array( 6,
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
                                                                                                                                    "limit",
                                                                                                                                    false ) ),
                                                                                                                      array( array( 2,
                                                                                                                                    5,
                                                                                                                                    false ) ),
                                                                                                                      array( array( 3,
                                                                                                                                    "sort_by",
                                                                                                                                    false ) ),
                                                                                                                      array( array( 6,
                                                                                                                                    array( "array",
                                                                                                                                           array( array( 3,
                                                                                                                                                         "published",
                                                                                                                                                         false ) ),
                                                                                                                                           array( array( 2,
                                                                                                                                                         false,
                                                                                                                                                         false ) ) ),
                                                                                                                                    false ) ),
                                                                                                                      array( array( 3,
                                                                                                                                    "class_filter_type",
                                                                                                                                    false ) ),
                                                                                                                      array( array( 3,
                                                                                                                                    "include",
                                                                                                                                    false ) ),
                                                                                                                      array( array( 3,
                                                                                                                                    "class_filter_array",
                                                                                                                                    false ) ),
                                                                                                                      array( array( 6,
                                                                                                                                    array( "array",
                                                                                                                                           array( array( 1,
                                                                                                                                                         "article",
                                                                                                                                                         false ) ) ),
                                                                                                                                    false ) ) ),
                                                                                                               false ) ) ),
                                                                                          false ) ) ),
                                                      array( array( 137,
                                                                    12,
                                                                    4211 ),
                                                             array( 141,
                                                                    55,
                                                                    4459 ),
                                                             "design/intranet/templates/pagelayout.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "                                                          \n            <div id=\"latestnews\">\n            <h3>";

$var = "Latest news";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/intranet/layout",
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

$text .= "</h3>\n            <ul>\n                   ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "                       <li class=\"",
                                    array( array( 146,
                                                  82,
                                                  4723 ),
                                           array( 147,
                                                  34,
                                                  4758 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "news" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "sequence",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 147,
                                                  34,
                                                  4759 ),
                                           array( 147,
                                                  48,
                                                  4773 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">\n                       <a href=",
                                    array( array( 147,
                                                  48,
                                                  4774 ),
                                           array( 148,
                                                  31,
                                                  4808 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "news" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "item",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "url_alias",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 148,
                                                  31,
                                                  4809 ),
                                           array( 148,
                                                  57,
                                                  4835 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 148,
                                                  57,
                                                  4836 ),
                                           array( 148,
                                                  58,
                                                  4837 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "news" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "item",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "name",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 148,
                                                  58,
                                                  4838 ),
                                           array( 148,
                                                  78,
                                                  4858 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a>\n                       <div class=\"date\">\n                        (",
                                    array( array( 148,
                                                  78,
                                                  4859 ),
                                           array( 150,
                                                  25,
                                                  4931 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "news" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "item",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "object",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "published",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "l10n",
                                                         array( array( 3,
                                                                       "shortdate",
                                                                       false ) ) ),
                                                  false ) ),
                                    array( array( 150,
                                                  25,
                                                  4932 ),
                                           array( 150,
                                                  70,
                                                  4977 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    ")\n                       </div>  \n                       </li>\n                    ",
                                    array( array( 150,
                                                  70,
                                                  4978 ),
                                           array( 153,
                                                  20,
                                                  5061 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ) ),
                       array( "var" => array( array( 3,
                                                    "news",
                                                    false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "news_list" ),
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
                       array( array( 146,
                                    19,
                                    4659 ),
                             array( 146,
                                    82,
                                    4722 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "            </ul>\n            </div>\n           ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "       </div>\n    </div>\n\n    \n    \n    </div>\n    \n    \n\n\n\n";

$textElements = array();
$tpl->processFunction( "cache-block", $textElements,
                       array( array( 2,
                                    false,
                                    "    <div id=\"footer\">\n        <div class=\"design\">\n            <address>\n		 ",
                                    array( array( 169,
                                                  11,
                                                  5276 ),
                                           array( 173,
                                                  3,
                                                  5353 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 6,
                                                  array( "ezini",
                                                         array( array( 1,
                                                                       "SiteSettings",
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "MetaDataArray",
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "site.ini",
                                                                       false ) ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "copyright",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 173,
                                                  3,
                                                  5354 ),
                                           array( 173,
                                                  61,
                                                  5412 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n		 <br /><a href=\"http://ez.no/\">Powered by eZ publish Content Management System</a>\n            </address>\n        </div>\n    </div>",
                                    array( array( 173,
                                                  61,
                                                  5413 ),
                                           array( 178,
                                                  0,
                                                  5548 ),
                                           "design/intranet/templates/pagelayout.tpl" ) ) ),
                       array(),
                       array( array( 169,
                                    0,
                                    5264 ),
                             array( 169,
                                    11,
                                    5275 ),
                             "design/intranet/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n\n\n</div>\n<!--DEBUG_REPORT-->\n\n</body>\n";


?>
