<?php
// URI:       design:parts/content/menu.tpl
// Filename:  design/admin/templates/parts/content/menu.tpl
// Timestamp: 1069775367 (Tue Nov 25 16:49:27 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/content/view/full/2/",
                                            false ) ),
                              array( array( 6,
                                            array( "ezini",
                                                   array( array( 1,
                                                                 "NodeSettings",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "RootNode",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "content.ini",
                                                                 false ) ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
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

$var = "Frontpage";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/content/view/sitemap/2/";
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

$var = "Sitemap";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/content/trash/",
                                            false ) ),
                              array( array( 6,
                                            array( "ezini",
                                                   array( array( 1,
                                                                 "NodeSettings",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "RootNode",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "content.ini",
                                                                 false ) ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
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

$var = "Trash";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n <a class=\"leftmenuitem\" href=";

$var = "/content/bookmark/";
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

$var = "Bookmarks";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>";

$tpl->processOperator( "eq",
                       array( array( array( 6,
                                            array( "ezpreference",
                                                   array( array( 1,
                                                                 "bookmark_menu",
                                                                 false ) ) ),
                                            false ) ),
                              array( array( 1,
                                            "on",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= " <a href=";

$var = "/user/preferences/set/bookmark_menu/off";
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

$var = "up.gif";
$tpl->processOperator( "ezimage",
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

$text .= " alt=\"\" width=\"11\" height=\"6\" /></a>\n</div>\n<ul class=\"leftmenu\">";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "bookmark_list" => array( array( 6,
                                                                                              array( "fetch",
                                                                                                     array( array( 3,
                                                                                                                   "content",
                                                                                                                   false ) ),
                                                                                                     array( array( 3,
                                                                                                                   "bookmarks",
                                                                                                                   false ) ) ),
                                                                                              false ) ) ),
                                                      array( array( 19,
                                                                    0,
                                                                    1590 ),
                                                             array( 19,
                                                                    42,
                                                                    1632 ),
                                                             "design/admin/templates/parts/content/menu.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<li>&#187; <a href=",
                                    array( array( 20,
                                                  42,
                                                  1678 ),
                                           array( 21,
                                                  19,
                                                  1698 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "node",
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
                                    array( array( 21,
                                                  19,
                                                  1699 ),
                                           array( 21,
                                                  46,
                                                  1726 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 21,
                                                  46,
                                                  1727 ),
                                           array( 21,
                                                  47,
                                                  1728 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
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
                                    array( array( 21,
                                                  47,
                                                  1729 ),
                                           array( 21,
                                                  63,
                                                  1745 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a></li>",
                                    array( array( 21,
                                                  63,
                                                  1746 ),
                                           array( 22,
                                                  0,
                                                  1756 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ) ),
                       array( "name" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "BookMark" ),
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "bookmark_list" ),
                                                     false ) ) ),
                       array( array( 20,
                                    0,
                                    1635 ),
                             array( 20,
                                    42,
                                    1677 ),
                             "design/admin/templates/parts/content/menu.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "</ul>";

}
else
{

unset( $show );

$text .= " <a href=";

$var = "/user/preferences/set/bookmark_menu/on";
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

$var = "down.gif";
$tpl->processOperator( "ezimage",
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

$text .= " alt=\"\" width=\"11\" height=\"6\" /></a>\n</div>";

}

$text .= "\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n <a class=\"leftmenuitem\">";

$var = "History";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>";

$tpl->processOperator( "eq",
                       array( array( array( 6,
                                            array( "ezpreference",
                                                   array( array( 1,
                                                                 "history_menu",
                                                                 false ) ) ),
                                            false ) ),
                              array( array( 1,
                                            "on",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= " <a href=";

$var = "/user/preferences/set/history_menu/off";
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

$var = "up.gif";
$tpl->processOperator( "ezimage",
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

$text .= " alt=\"\" width=\"11\" height=\"6\" /></a>\n</div>\n<ul class=\"leftmenu\">";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "history_list" => array( array( 6,
                                                                                             array( "fetch",
                                                                                                    array( array( 3,
                                                                                                                  "content",
                                                                                                                  false ) ),
                                                                                                    array( array( 3,
                                                                                                                  "recent",
                                                                                                                  false ) ) ),
                                                                                             false ) ) ),
                                                      array( array( 36,
                                                                    0,
                                                                    2420 ),
                                                             array( 36,
                                                                    38,
                                                                    2458 ),
                                                             "design/admin/templates/parts/content/menu.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<li>&#187; <a href=",
                                    array( array( 37,
                                                  40,
                                                  2502 ),
                                           array( 38,
                                                  19,
                                                  2522 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "node",
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
                                    array( array( 38,
                                                  19,
                                                  2523 ),
                                           array( 38,
                                                  46,
                                                  2550 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 38,
                                                  46,
                                                  2551 ),
                                           array( 38,
                                                  47,
                                                  2552 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
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
                                    array( array( 38,
                                                  47,
                                                  2553 ),
                                           array( 38,
                                                  63,
                                                  2569 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a></li>",
                                    array( array( 38,
                                                  63,
                                                  2570 ),
                                           array( 39,
                                                  0,
                                                  2580 ),
                                           "design/admin/templates/parts/content/menu.tpl" ) ) ),
                       array( "name" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "History" ),
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "history_list" ),
                                                     false ) ) ),
                       array( array( 37,
                                    0,
                                    2461 ),
                             array( 37,
                                    40,
                                    2501 ),
                             "design/admin/templates/parts/content/menu.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

$text .= "</ul>";

}
else
{

unset( $show );

$text .= " <a href=";

$var = "/user/preferences/set/history_menu/on";
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

$var = "down.gif";
$tpl->processOperator( "ezimage",
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

$text .= " alt=\"\" width=\"11\" height=\"6\" /></a>\n</div>";

}

$text .= "\n\n\n\n\n\n";


?>
