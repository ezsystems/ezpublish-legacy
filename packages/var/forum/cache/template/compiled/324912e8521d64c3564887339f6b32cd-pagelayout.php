<?php
// URI:       design:pagelayout.tpl
// Filename:  design/forum/templates/pagelayout.tpl
// Timestamp: 1069843520 (Wed Nov 26 11:45:20 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/forum/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";

$textElements = array();
$tpl->processFunction( "cache-block", $textElements,
                       array( array( 2,
                                    false,
                                    "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"no\" lang=\"no\">",
                                    array( array( 3,
                                                  28,
                                                  156 ),
                                           array( 5,
                                                  0,
                                                  225 ),
                                           "design/forum/templates/pagelayout.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "<head>",
                                                  array( array( 5,
                                                                69,
                                                                296 ),
                                                         array( 7,
                                                                0,
                                                                304 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 4,
                                                  false,
                                                  "include",
                                                  array( "uri" => array( array( 1,
                                                                                "design:page_head.tpl",
                                                                                false ) ),
                                                         "enable_glossary" => array( array( 2,
                                                                                            false,
                                                                                            false ) ),
                                                         "enable_help" => array( array( 2,
                                                                                        false,
                                                                                        false ) ) ),
                                                  array( array( 7,
                                                                0,
                                                                305 ),
                                                         array( 7,
                                                                78,
                                                                383 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\n<style>\n    @import url(",
                                                  array( array( 7,
                                                                78,
                                                                384 ),
                                                         array( 10,
                                                                16,
                                                                410 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 1,
                                                                "stylesheets/core.css",
                                                                false ),
                                                         array( 6,
                                                                array( "ezdesign" ),
                                                                false ) ),
                                                  array( array( 10,
                                                                16,
                                                                411 ),
                                                         array( 10,
                                                                47,
                                                                442 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  ");\n    @import url(\"/design/forum/stylesheets/forum_blue.css\");\n  \n</style>\n</head>\n\n<body>\n\n<div id=\"background\">\n\n    <div id=\"header\">\n        <div class=\"design\">\n        \n           ",
                                                  array( array( 10,
                                                                47,
                                                                443 ),
                                                         array( 23,
                                                                11,
                                                                721 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 4,
                                                  array( array( 2,
                                                                false,
                                                                "            <div id=\"logo\">\n                <a href=",
                                                                array( array( 23,
                                                                              57,
                                                                              769 ),
                                                                       array( 25,
                                                                              24,
                                                                              822 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 3,
                                                                false,
                                                                array( array( 1,
                                                                              "/",
                                                                              false ),
                                                                       array( 6,
                                                                              array( "ezurl" ),
                                                                              false ) ),
                                                                array( array( 25,
                                                                              24,
                                                                              823 ),
                                                                       array( 25,
                                                                              33,
                                                                              832 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "><img src=",
                                                                array( array( 25,
                                                                              33,
                                                                              833 ),
                                                                       array( 25,
                                                                              43,
                                                                              843 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 3,
                                                                false,
                                                                array( array( 4,
                                                                              array( "",
                                                                                     2,
                                                                                     "content" ),
                                                                              false ),
                                                                       array( 5,
                                                                              array( array( 3,
                                                                                            "logo",
                                                                                            false ) ),
                                                                              false ),
                                                                       array( 5,
                                                                              array( array( 3,
                                                                                            "full_path",
                                                                                            false ) ),
                                                                              false ),
                                                                       array( 6,
                                                                              array( "ezroot" ),
                                                                              false ) ),
                                                                array( array( 25,
                                                                              43,
                                                                              844 ),
                                                                       array( 25,
                                                                              74,
                                                                              875 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                " /></a>\n            </div>\n           ",
                                                                array( array( 25,
                                                                              74,
                                                                              876 ),
                                                                       array( 27,
                                                                              11,
                                                                              914 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ) ),
                                                  "let",
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
                                                  array( array( 23,
                                                                11,
                                                                722 ),
                                                         array( 23,
                                                                57,
                                                                768 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "                  \n        </div>\n    </div>\n\n        \n    <div id=\"subheader\">\n        <div class=\"design\">\n        \n            <div id=\"searchbox\">\n                <form action=",
                                                  array( array( 27,
                                                                14,
                                                                920 ),
                                                         array( 37,
                                                                29,
                                                                1101 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 1,
                                                                "/content/search/",
                                                                false ),
                                                         array( 6,
                                                                array( "ezurl" ),
                                                                false ) ),
                                                  array( array( 37,
                                                                29,
                                                                1102 ),
                                                         array( 37,
                                                                53,
                                                                1126 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  " method=\"get\">\n                    <input class=\"searchtext\" type=\"text\" size=\"10\" name=\"SearchText\" id=\"Search\" value=\"\" />\n                    <input class=\"searchbutton\" name=\"SearchButton\" type=\"submit\" value=\"Search\" />\n                </form>\n            </div>\n        \n        <div id=\"mainmenu\">\n            <div class=\"design\">\n\n                <h3 class=\"invisible\">Main menu</h3>\n                <ul>\n                ",
                                                  array( array( 37,
                                                                53,
                                                                1127 ),
                                                         array( 48,
                                                                16,
                                                                1556 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 4,
                                                  array( array( 2,
                                                                false,
                                                                "                ",
                                                                array( array( 48,
                                                                              118,
                                                                              1660 ),
                                                                       array( 49,
                                                                              16,
                                                                              1677 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 4,
                                                                array( array( 2,
                                                                              false,
                                                                              "                    <li><a href=",
                                                                              array( array( 49,
                                                                                            53,
                                                                                            1716 ),
                                                                                     array( 50,
                                                                                            32,
                                                                                            1749 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 3,
                                                                              false,
                                                                              array( array( 6,
                                                                                            array( "concat",
                                                                                                   array( array( 1,
                                                                                                                 "/content/view/full/",
                                                                                                                 false ) ),
                                                                                                   array( array( 4,
                                                                                                                 array( "Folder",
                                                                                                                        2,
                                                                                                                        "item" ),
                                                                                                                 false ),
                                                                                                          array( 5,
                                                                                                                 array( array( 3,
                                                                                                                               "node_id",
                                                                                                                               false ) ),
                                                                                                                 false ) ),
                                                                                                   array( array( 1,
                                                                                                                 "/",
                                                                                                                 false ) ) ),
                                                                                            false ),
                                                                                     array( 6,
                                                                                            array( "ezurl" ),
                                                                                            false ) ),
                                                                              array( array( 50,
                                                                                            32,
                                                                                            1750 ),
                                                                                     array( 50,
                                                                                            96,
                                                                                            1814 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 2,
                                                                              false,
                                                                              ">",
                                                                              array( array( 50,
                                                                                            96,
                                                                                            1815 ),
                                                                                     array( 50,
                                                                                            97,
                                                                                            1816 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 3,
                                                                              false,
                                                                              array( array( 4,
                                                                                            array( "Folder",
                                                                                                   2,
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
                                                                              array( array( 50,
                                                                                            97,
                                                                                            1817 ),
                                                                                     array( 50,
                                                                                            119,
                                                                                            1839 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 2,
                                                                              false,
                                                                              "</a></li>\n                ",
                                                                              array( array( 50,
                                                                                            119,
                                                                                            1840 ),
                                                                                     array( 51,
                                                                                            16,
                                                                                            1866 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ) ),
                                                                "section",
                                                                array( "name" => array( array( 3,
                                                                                               "Folder",
                                                                                               false ) ),
                                                                       "loop" => array( array( 4,
                                                                                               array( "",
                                                                                                      2,
                                                                                                      "folder_list" ),
                                                                                               false ) ) ),
                                                                array( array( 49,
                                                                              16,
                                                                              1678 ),
                                                                       array( 49,
                                                                              53,
                                                                              1715 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "                ",
                                                                array( array( 51,
                                                                              23,
                                                                              1876 ),
                                                                       array( 52,
                                                                              16,
                                                                              1893 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ) ),
                                                  "let",
                                                  array( "folder_list" => array( array( 6,
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
                                                                                                                                         array( array( 6,
                                                                                                                                                       array( "array",
                                                                                                                                                              array( array( 3,
                                                                                                                                                                            "priority",
                                                                                                                                                                            false ) ) ),
                                                                                                                                                       false ) ) ),
                                                                                                                                  false ) ) ),
                                                                                                             false ) ) ),
                                                                                        false ) ) ),
                                                  array( array( 48,
                                                                16,
                                                                1557 ),
                                                         array( 48,
                                                                118,
                                                                1659 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "                </ul>\n            \n            </div>\n        </div>\n        \n        <div class=\"break\"></div> \n        \n        </div>\n    </div>\n",
                                                  array( array( 52,
                                                                19,
                                                                1899 ),
                                                         array( 63,
                                                                0,
                                                                2105 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ) ),
                                    "let",
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
                                                                                                                   "forum_package",
                                                                                                                   false ) ) ),
                                                                                              false ) ) ),
                                                                         false ) ) ),
                                    array( array( 5,
                                                  0,
                                                  226 ),
                                           array( 5,
                                                  69,
                                                  295 ),
                                           "design/forum/templates/pagelayout.tpl" ) ) ),
                       array( "keys" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "uri_string" ),
                                                     false ) ) ),
                       array( array( 3,
                                    0,
                                    127 ),
                             array( 3,
                                    28,
                                    155 ),
                             "design/forum/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    <div id=\"usermenu\">\n        <div class=\"design\">\n\n        <h3 class=\"invisible\">User menu</h3>\n        <ul>\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "current_user" );
$show1 = "is_logged_in";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "            <li><a href=";

$var = "/notification/settings";
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

$var = "Notifications";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/forum/layout",
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

$text .= "</a></li>\n            <li><a href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/content/edit/",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "current_user" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "contentobject_id",
                                                          false ) ),
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

$var = "Edit account";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/forum/layout",
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

$text .= "</a></li>\n        ";

}

$text .= "        ";

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

$text .= "            <li><a href=";

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

$var = "Login";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/forum/layout",
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

$text .= "</a></li>\n        ";

}
else
{

unset( $show );

$text .= "            <li><a href=";

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

$var = "Logout";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/forum/layout",
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

$text .= "</a></li>\n        ";

}

$text .= "        \n        </ul>\n\n        </div>\n    </div>";

$textElements = array();
$tpl->processFunction( "cache-block", $textElements,
                       array( array( 2,
                                    false,
                                    "\n    <div id=\"maincontent\">\n        <div class=\"design\">\n        \n    <div id=\"path\">\n        <div class=\"design\">\n\n           <p>\n           &gt;\n           ",
                                    array( array( 85,
                                                  28,
                                                  2927 ),
                                           array( 95,
                                                  11,
                                                  3086 ),
                                           "design/forum/templates/pagelayout.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "               ",
                                                  array( array( 95,
                                                                54,
                                                                3131 ),
                                                         array( 96,
                                                                15,
                                                                3147 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 4,
                                                  array( array( 2,
                                                                false,
                                                                "                   ",
                                                                array( array( 96,
                                                                              42,
                                                                              3176 ),
                                                                       array( 97,
                                                                              19,
                                                                              3196 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 4,
                                                                array( array( 2,
                                                                              false,
                                                                              "                      <a href=",
                                                                              array( array( 97,
                                                                                            60,
                                                                                            3239 ),
                                                                                     array( 98,
                                                                                            30,
                                                                                            3270 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
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
                                                                              array( array( 98,
                                                                                            30,
                                                                                            3271 ),
                                                                                     array( 98,
                                                                                            50,
                                                                                            3291 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 2,
                                                                              false,
                                                                              ">",
                                                                              array( array( 98,
                                                                                            50,
                                                                                            3292 ),
                                                                                     array( 98,
                                                                                            51,
                                                                                            3293 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
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
                                                                              array( array( 98,
                                                                                            51,
                                                                                            3294 ),
                                                                                     array( 98,
                                                                                            71,
                                                                                            3314 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ),
                                                                       array( 2,
                                                                              false,
                                                                              "</a> /\n                   ",
                                                                              array( array( 98,
                                                                                            71,
                                                                                            3315 ),
                                                                                     array( 99,
                                                                                            19,
                                                                                            3341 ),
                                                                                     "design/forum/templates/pagelayout.tpl" ) ) ),
                                                                "section",
                                                                array( "show" => array( array( 4,
                                                                                               array( "",
                                                                                                      3,
                                                                                                      "item" ),
                                                                                               false ),
                                                                                        array( 5,
                                                                                               array( array( 3,
                                                                                                             "node_id",
                                                                                                             false ) ),
                                                                                               false ),
                                                                                        array( 6,
                                                                                               array( "eq",
                                                                                                      array( array( 2,
                                                                                                                    152,
                                                                                                                    false ) ) ),
                                                                                               false ),
                                                                                        array( 6,
                                                                                               array( "not" ),
                                                                                               false ) ) ),
                                                                array( array( 97,
                                                                              19,
                                                                              3197 ),
                                                                       array( 97,
                                                                              60,
                                                                              3238 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "               ",
                                                                array( array( 99,
                                                                              26,
                                                                              3351 ),
                                                                       array( 100,
                                                                              15,
                                                                              3367 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 4,
                                                                false,
                                                                "section-else",
                                                                array(),
                                                                array( array( 100,
                                                                              15,
                                                                              3368 ),
                                                                       array( 100,
                                                                              27,
                                                                              3380 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "    	      ",
                                                                array( array( 100,
                                                                              27,
                                                                              3381 ),
                                                                       array( 101,
                                                                              11,
                                                                              3393 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
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
                                                                array( array( 101,
                                                                              11,
                                                                              3394 ),
                                                                       array( 101,
                                                                              31,
                                                                              3414 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "\n               ",
                                                                array( array( 101,
                                                                              31,
                                                                              3415 ),
                                                                       array( 102,
                                                                              15,
                                                                              3431 ),
                                                                       "design/forum/templates/pagelayout.tpl" ) ) ),
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
                                                  array( array( 96,
                                                                15,
                                                                3148 ),
                                                         array( 96,
                                                                42,
                                                                3175 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "    \n            ",
                                                  array( array( 102,
                                                                22,
                                                                3441 ),
                                                         array( 104,
                                                                12,
                                                                3459 ),
                                                         "design/forum/templates/pagelayout.tpl" ) ) ),
                                    "section",
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
                                    array( array( 95,
                                                  11,
                                                  3087 ),
                                           array( 95,
                                                  54,
                                                  3130 ),
                                           "design/forum/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "           </p>\n\n        </div>\n    </div>",
                                    array( array( 104,
                                                  19,
                                                  3469 ),
                                           array( 109,
                                                  0,
                                                  3513 ),
                                           "design/forum/templates/pagelayout.tpl" ) ) ),
                       array( "keys" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "uri_string" ),
                                                     false ) ) ),
                       array( array( 85,
                                    0,
                                    2898 ),
                             array( 85,
                                    28,
                                    2926 ),
                             "design/forum/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n            ";

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

$text .= "\n        \n        </div>\n    </div>";

$textElements = array();
$tpl->processFunction( "cache-block", $textElements,
                       array( array( 2,
                                    false,
                                    "    <div id=\"footer\">\n        <div class=\"design\">\n            <address>\n		 ",
                                    array( array( 115,
                                                  11,
                                                  3614 ),
                                           array( 119,
                                                  3,
                                                  3691 ),
                                           "design/forum/templates/pagelayout.tpl" ) ),
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
                                    array( array( 119,
                                                  3,
                                                  3692 ),
                                           array( 119,
                                                  61,
                                                  3750 ),
                                           "design/forum/templates/pagelayout.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n		 <br /><a href=\"http://ez.no/\">Powered by eZ publish Content Management System</a>\n            </address>\n        </div>\n    </div>",
                                    array( array( 119,
                                                  61,
                                                  3751 ),
                                           array( 124,
                                                  0,
                                                  3886 ),
                                           "design/forum/templates/pagelayout.tpl" ) ) ),
                       array(),
                       array( array( 115,
                                    0,
                                    3602 ),
                             array( 115,
                                    11,
                                    3613 ),
                             "design/forum/templates/pagelayout.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n</div>\n\n</body>\n</html>\n\n";


?>
