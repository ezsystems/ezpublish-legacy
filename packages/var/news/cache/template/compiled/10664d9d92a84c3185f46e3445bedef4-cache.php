<?php
// URI:       design:setup/cache.tpl
// Filename:  design/standard/templates/setup/cache.tpl
// Timestamp: 1066054898 (Mon Oct 13 16:21:38 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<form method=\"post\" action=";

$var = "/setup/cache/";
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

$text .= ">\n\n<h1>";

$var = "Cache admin";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/setup",
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

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_cleared" );
$show1 = "content";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">\n";

$var = "Content view cache was cleared.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/setup",
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

$text .= "\n</div>";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_cleared" );
$show1 = "all";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">\nAll caches were cleared.\n</div>";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_cleared" );
$show1 = "ini";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">\n";

$var = "Ini file cache was cleared.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/setup",
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

$text .= "\n</div>";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_cleared" );
$show1 = "template";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">\n";

$var = "Template cache was cleared.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/setup",
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

$text .= "\n</div>";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_cleared" );
$show1 = "list";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 3,
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
                                                  false ) ),
                                    array( array( 32,
                                                  0,
                                                  746 ),
                                           array( 32,
                                                  11,
                                                  757 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 2,
                                    false,
                                    " was cleared",
                                    array( array( 32,
                                                  11,
                                                  758 ),
                                           array( 33,
                                                  0,
                                                  771 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "<br/>",
                                                  array( array( 33,
                                                                9,
                                                                782 ),
                                                         array( 33,
                                                                14,
                                                                787 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ) ),
                                    "delimiter",
                                    array(),
                                    array( array( 33,
                                                  0,
                                                  772 ),
                                           array( 33,
                                                  9,
                                                  781 ),
                                           "design/standard/templates/setup/cache.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Cache",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "cache_cleared" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "list",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 31,
                                    0,
                                    700 ),
                             array( 31,
                                    43,
                                    743 ),
                             "design/standard/templates/setup/cache.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</div>";

}

$text .= "\n<div class=\"objectheader\">\n<h2>Cache collections</h2>\n</div>\n\n<div class=\"object\">\n    <p>Click a button to clear a collection of caches.</p>\n\n\n    <table>\n    <tr>\n        <td><p>All caches.</p></dt>\n        <td><div class=\"buttonblock\">\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_enabled" );
$show1 = "all";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "        <input type=\"submit\" name=\"ClearAllCacheButton\" value=\"All caches\" />\n        ";

}
else
{

unset( $show );

$text .= "            <p>All caches are disabled</p>\n        ";

}

$text .= "    </div></td>\n    </tr>\n\n    <tr>\n        <td><p>Content views and template blocks.</p></td>\n        <td>\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_enabled" );
$show1 = "content";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "        <input type=\"submit\" name=\"ClearContentCacheButton\" value=\"Content caches\" />\n        ";

}
else
{

unset( $show );

$text .= "            <p>Content caches is disabled</p>\n        ";

}

$text .= "        </td>\n    </tr>\n\n    <tr>\n        <td><p>Template overrides and template compiling.</p></td>\n        <td>\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_enabled" );
$show1 = "template";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "        <input type=\"submit\" name=\"ClearTemplateCacheButton\" value=\"Template caches\" />\n        ";

}
else
{

unset( $show );

$text .= "            <p>Template caches are disabled</p>\n        ";

}

$text .= "        </td>\n    </tr>\n\n    <tr>\n        <td><p>INI caches.</p></td>\n        <td>\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "cache_enabled" );
$show1 = "ini";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "        <input type=\"submit\" name=\"ClearINICacheButton\" value=\"INI caches\" />\n        ";

}
else
{

unset( $show );

$text .= "            <p>INI cache is disabled</p>\n        ";

}

$text .= "        </td>\n    </tr>\n\n    </table>\n\n</div>\n\n<table class=\"list\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n<tr>\n    <th width=\"50%\">Name</th>\n    <th width=\"50%\">Path</th>\n    <th width=\"1\">Selection</th>\n</tr>";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<tr class=\"",
                                    array( array( 101,
                                                  66,
                                                  2591 ),
                                           array( 102,
                                                  11,
                                                  2603 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "sequence" ),
                                                  false ) ),
                                    array( array( 102,
                                                  11,
                                                  2604 ),
                                           array( 102,
                                                  21,
                                                  2614 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">\n    <td>",
                                    array( array( 102,
                                                  21,
                                                  2615 ),
                                           array( 103,
                                                  8,
                                                  2626 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
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
                                                  false ) ),
                                    array( array( 103,
                                                  8,
                                                  2627 ),
                                           array( 103,
                                                  19,
                                                  2638 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 2,
                                    false,
                                    "</td>\n    <td>",
                                    array( array( 103,
                                                  19,
                                                  2639 ),
                                           array( 104,
                                                  8,
                                                  2653 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "path",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 104,
                                                  8,
                                                  2654 ),
                                           array( 104,
                                                  19,
                                                  2665 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 2,
                                    false,
                                    "</td>",
                                    array( array( 104,
                                                  19,
                                                  2666 ),
                                           array( 105,
                                                  0,
                                                  2672 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "    <td align=\"right\"><input type=\"checkbox\" name=\"CacheList[]\" value=\"",
                                                  array( array( 105,
                                                                43,
                                                                2717 ),
                                                         array( 106,
                                                                71,
                                                                2789 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ),
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
                                                  array( array( 106,
                                                                71,
                                                                2790 ),
                                                         array( 106,
                                                                80,
                                                                2799 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\" /></td>",
                                                  array( array( 106,
                                                                80,
                                                                2800 ),
                                                         array( 107,
                                                                0,
                                                                2810 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ),
                                           array( 4,
                                                  false,
                                                  "section-else",
                                                  array(),
                                                  array( array( 107,
                                                                0,
                                                                2811 ),
                                                         array( 107,
                                                                12,
                                                                2823 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "    <td align=\"right\">Disabled</td>",
                                                  array( array( 107,
                                                                12,
                                                                2824 ),
                                                         array( 109,
                                                                0,
                                                                2865 ),
                                                         "design/standard/templates/setup/cache.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          2,
                                                                          "cache_enabled" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "list",
                                                                                 false ) ),
                                                                   false ),
                                                            array( 5,
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
                                                                   false ) ) ),
                                    array( array( 105,
                                                  0,
                                                  2673 ),
                                           array( 105,
                                                  43,
                                                  2716 ),
                                           "design/standard/templates/setup/cache.tpl" ) ),
                             array( 2,
                                    false,
                                    "</tr>",
                                    array( array( 109,
                                                  7,
                                                  2875 ),
                                           array( 111,
                                                  0,
                                                  2882 ),
                                           "design/standard/templates/setup/cache.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Cache",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "cache_list" ),
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
                       array( array( 101,
                                    0,
                                    2524 ),
                             array( 101,
                                    66,
                                    2590 ),
                             "design/standard/templates/setup/cache.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</table>\n\n<div class=\"buttonblock\" align=\"right\">\n    <input type=\"submit\" name=\"ClearCacheButton\" value=\"Clear selected\" />\n</div>\n\n\n\n</form>\n";


?>
