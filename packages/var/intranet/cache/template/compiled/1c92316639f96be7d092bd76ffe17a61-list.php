<?php
// URI:       design:package/list.tpl
// Filename:  design/standard/templates/package/list.tpl
// Timestamp: 1069675393 (Mon Nov 24 13:03:13 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "page_limit" => array( array( 2,
                                                                                           15,
                                                                                           false ) ),
                                                             "package_list" => array( array( 6,
                                                                                             array( "fetch",
                                                                                                    array( array( 3,
                                                                                                                  "package",
                                                                                                                  false ) ),
                                                                                                    array( array( 3,
                                                                                                                  "list",
                                                                                                                  false ) ),
                                                                                                    array( array( 6,
                                                                                                                  array( "hash",
                                                                                                                         array( array( 3,
                                                                                                                                       "offset",
                                                                                                                                       false ) ),
                                                                                                                         array( array( 4,
                                                                                                                                       array( "",
                                                                                                                                              2,
                                                                                                                                              "view_parameters" ),
                                                                                                                                       false ),
                                                                                                                                array( 5,
                                                                                                                                       array( array( 3,
                                                                                                                                                     "offset",
                                                                                                                                                     false ) ),
                                                                                                                                       false ) ),
                                                                                                                         array( array( 3,
                                                                                                                                       "limit",
                                                                                                                                       false ) ),
                                                                                                                         array( array( 4,
                                                                                                                                       array( "",
                                                                                                                                              2,
                                                                                                                                              "page_limit" ),
                                                                                                                                       false ) ) ),
                                                                                                                  false ) ) ),
                                                                                             false ) ),
                                                             "can_remove" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 3,
                                                                                                                "package",
                                                                                                                false ) ),
                                                                                                  array( array( 3,
                                                                                                                "can_remove",
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 1,
                                                                    0,
                                                                    1 ),
                                                             array( 5,
                                                                    44,
                                                                    211 ),
                                                             "design/standard/templates/package/list.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "<form method=\"post\" action=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "package/list",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "view_parameters" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "offset",
                                                          false ) ),
                                            false ),
                                     array( 6,
                                            array( "gt",
                                                   array( array( 2,
                                                                 0,
                                                                 false ) ) ),
                                            false ),
                                     array( 6,
                                            array( "choose",
                                                   array( array( 1,
                                                                 "",
                                                                 false ) ),
                                                   array( array( 6,
                                                                 array( "concat",
                                                                        array( array( 1,
                                                                                      "/offset/",
                                                                                      false ) ),
                                                                        array( array( 4,
                                                                                      array( "",
                                                                                             2,
                                                                                             "view_parameters" ),
                                                                                      false ),
                                                                               array( 5,
                                                                                      array( array( 3,
                                                                                                    "offset",
                                                                                                    false ) ),
                                                                                      false ) ) ),
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

$text .= ">\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "remove_list" );

if ( $show )
{

unset( $show );

$text .= "\n<div class=\"warning\">\n<h2>";

$var = "Removal of packages";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</h2>\n<p>";

$var = "Are you sure you wish to remove the following packages?\nThe packages will be lost forever.\nNote: The packages will not be uninstalled.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "break",
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

$text .= "</p>\n<ul>";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "    <li>\n        <input type=\"hidden\" name=\"PackageSelection[]\" value=\"",
                                    array( array( 19,
                                                  37,
                                                  796 ),
                                           array( 21,
                                                  62,
                                                  868 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "package" ),
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
                                                  62,
                                                  869 ),
                                           array( 21,
                                                  80,
                                                  887 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" />\n        ",
                                    array( array( 21,
                                                  80,
                                                  888 ),
                                           array( 22,
                                                  8,
                                                  901 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "package" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "name",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 22,
                                                  8,
                                                  902 ),
                                           array( 22,
                                                  26,
                                                  920 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    " (",
                                    array( array( 22,
                                                  26,
                                                  921 ),
                                           array( 22,
                                                  28,
                                                  923 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "package" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "summary",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 22,
                                                  28,
                                                  924 ),
                                           array( 22,
                                                  49,
                                                  945 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    ")\n    </li>",
                                    array( array( 22,
                                                  49,
                                                  946 ),
                                           array( 24,
                                                  0,
                                                  958 ),
                                           "design/standard/templates/package/list.tpl" ) ) ),
                       array( "var" => array( array( 3,
                                                    "package",
                                                    false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "remove_list" ),
                                                     false ) ) ),
                       array( array( 19,
                                    0,
                                    758 ),
                             array( 19,
                                    37,
                                    795 ),
                             "design/standard/templates/package/list.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</ul>\n</div>\n\n<div class=\"buttonblock\">\n    <input class=\"button\" type=\"submit\" name=\"ConfirmRemovePackageButton\" value=\"";

$var = "Confirm removal";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "\" />\n    <input class=\"defaultbutton\" type=\"submit\" name=\"CancelRemovePackageButton\" value=\"";

$var = "Keep packages";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "\" />\n</div>\n";

}
else
{

unset( $show );

$text .= "\n<h2>";

$var = "Packages";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</h2>\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "module_action" );
$tpl->processOperator( "eq",
                       array( array( array( 1,
                                            "CancelRemovePackage",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "<div class=\"feedback\">\n    <p>";

$var = "Package removal was cancelled.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</p>\n</div>";

}

$text .= "\n\n<p>";

$var = "The following packages are available on this system";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</p>\n\n<table class=\"list\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n<tr>\n    ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "can_remove" );

if ( $show )
{

unset( $show );

$text .= "    <th width=\"1\">";

$var = "Selection";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</th>\n    ";

}

$text .= "    <th>";

$var = "Name";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</th>\n    <th>";

$var = "Version";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</th>\n    <th>";

$var = "Summary";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</th>\n    <th>";

$var = "Status";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</th>\n</tr>";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<tr class=\"",
                                    array( array( 56,
                                                  70,
                                                  2140 ),
                                           array( 57,
                                                  11,
                                                  2152 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "sequence" ),
                                                  false ) ),
                                    array( array( 57,
                                                  11,
                                                  2153 ),
                                           array( 57,
                                                  21,
                                                  2163 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">\n    ",
                                    array( array( 57,
                                                  21,
                                                  2164 ),
                                           array( 58,
                                                  4,
                                                  2171 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "    <td width=\"1\"><input type=\"checkbox\" name=\"PackageSelection[]\" value=\"",
                                                  array( array( 58,
                                                                28,
                                                                2197 ),
                                                         array( 59,
                                                                74,
                                                                2272 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
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
                                                  array( array( 59,
                                                                74,
                                                                2273 ),
                                                         array( 59,
                                                                90,
                                                                2289 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\" /></td>\n    ",
                                                  array( array( 59,
                                                                90,
                                                                2290 ),
                                                         array( 60,
                                                                4,
                                                                2304 ),
                                                         "design/standard/templates/package/list.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          2,
                                                                          "can_remove" ),
                                                                   false ) ) ),
                                    array( array( 58,
                                                  4,
                                                  2172 ),
                                           array( 58,
                                                  28,
                                                  2196 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "    <td><a href=",
                                    array( array( 60,
                                                  11,
                                                  2314 ),
                                           array( 61,
                                                  16,
                                                  2331 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 6,
                                                  array( "concat",
                                                         array( array( 1,
                                                                       "package/view/full/",
                                                                       false ) ),
                                                         array( array( 4,
                                                                       array( "",
                                                                              3,
                                                                              "item" ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "name",
                                                                                     false ) ),
                                                                       false ) ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 61,
                                                  16,
                                                  2332 ),
                                           array( 61,
                                                  62,
                                                  2378 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    ">",
                                    array( array( 61,
                                                  62,
                                                  2379 ),
                                           array( 61,
                                                  63,
                                                  2380 ),
                                           "design/standard/templates/package/list.tpl" ) ),
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
                                    array( array( 61,
                                                  63,
                                                  2381 ),
                                           array( 61,
                                                  79,
                                                  2397 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "</a></td>\n    <td>",
                                    array( array( 61,
                                                  79,
                                                  2398 ),
                                           array( 62,
                                                  8,
                                                  2416 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "version-number",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 62,
                                                  8,
                                                  2417 ),
                                           array( 62,
                                                  29,
                                                  2438 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "-",
                                    array( array( 62,
                                                  29,
                                                  2439 ),
                                           array( 62,
                                                  30,
                                                  2440 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "release-number",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 62,
                                                  30,
                                                  2441 ),
                                           array( 62,
                                                  51,
                                                  2462 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "(",
                                                  array( array( 62,
                                                                88,
                                                                2502 ),
                                                         array( 62,
                                                                89,
                                                                2503 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "",
                                                                       3,
                                                                       "item" ),
                                                                false ),
                                                         array( 5,
                                                                array( array( 3,
                                                                              "release-timestamp",
                                                                              false ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "l10n",
                                                                       array( array( 3,
                                                                                     "shortdatetime",
                                                                                     false ) ) ),
                                                                false ) ),
                                                  array( array( 62,
                                                                89,
                                                                2504 ),
                                                         array( 62,
                                                                135,
                                                                2550 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  ")",
                                                  array( array( 62,
                                                                135,
                                                                2551 ),
                                                         array( 62,
                                                                136,
                                                                2552 ),
                                                         "design/standard/templates/package/list.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          3,
                                                                          "item" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "release-timestamp",
                                                                                 false ) ),
                                                                   false ) ) ),
                                    array( array( 62,
                                                  51,
                                                  2464 ),
                                           array( 62,
                                                  88,
                                                  2501 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  " [",
                                                  array( array( 62,
                                                                167,
                                                                2588 ),
                                                         array( 62,
                                                                169,
                                                                2590 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "",
                                                                       3,
                                                                       "item" ),
                                                                false ),
                                                         array( 5,
                                                                array( array( 3,
                                                                              "type",
                                                                              false ) ),
                                                                false ),
                                                         array( 6,
                                                                array( "wash" ),
                                                                false ) ),
                                                  array( array( 62,
                                                                169,
                                                                2591 ),
                                                         array( 62,
                                                                185,
                                                                2607 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "]",
                                                  array( array( 62,
                                                                185,
                                                                2608 ),
                                                         array( 62,
                                                                186,
                                                                2609 ),
                                                         "design/standard/templates/package/list.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          3,
                                                                          "item" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "type",
                                                                                 false ) ),
                                                                   false ) ) ),
                                    array( array( 62,
                                                  143,
                                                  2563 ),
                                           array( 62,
                                                  167,
                                                  2587 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "</td>\n    <td>",
                                    array( array( 62,
                                                  193,
                                                  2619 ),
                                           array( 63,
                                                  8,
                                                  2633 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "summary",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 63,
                                                  8,
                                                  2634 ),
                                           array( 63,
                                                  27,
                                                  2653 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "</td>\n    <td>\n        ",
                                    array( array( 63,
                                                  27,
                                                  2654 ),
                                           array( 65,
                                                  8,
                                                  2677 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "            ",
                                                  array( array( 65,
                                                                56,
                                                                2727 ),
                                                         array( 66,
                                                                12,
                                                                2740 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 4,
                                                  array( array( 2,
                                                                false,
                                                                "                ",
                                                                array( array( 66,
                                                                              44,
                                                                              2774 ),
                                                                       array( 67,
                                                                              16,
                                                                              2791 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 3,
                                                                false,
                                                                array( array( 1,
                                                                              "Installed",
                                                                              false ),
                                                                       array( 6,
                                                                              array( "i18n",
                                                                                     array( array( 1,
                                                                                                   "design/standard/package",
                                                                                                   false ) ) ),
                                                                              false ) ),
                                                                array( array( 67,
                                                                              16,
                                                                              2792 ),
                                                                       array( 67,
                                                                              59,
                                                                              2835 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "\n            ",
                                                                array( array( 67,
                                                                              59,
                                                                              2836 ),
                                                                       array( 68,
                                                                              12,
                                                                              2849 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 4,
                                                                false,
                                                                "section-else",
                                                                array(),
                                                                array( array( 68,
                                                                              12,
                                                                              2850 ),
                                                                       array( 68,
                                                                              24,
                                                                              2862 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "                ",
                                                                array( array( 68,
                                                                              24,
                                                                              2863 ),
                                                                       array( 69,
                                                                              16,
                                                                              2880 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 3,
                                                                false,
                                                                array( array( 1,
                                                                              "Not installed",
                                                                              false ),
                                                                       array( 6,
                                                                              array( "i18n",
                                                                                     array( array( 1,
                                                                                                   "design/standard/package",
                                                                                                   false ) ) ),
                                                                              false ) ),
                                                                array( array( 69,
                                                                              16,
                                                                              2881 ),
                                                                       array( 69,
                                                                              63,
                                                                              2928 ),
                                                                       "design/standard/templates/package/list.tpl" ) ),
                                                         array( 2,
                                                                false,
                                                                "\n            ",
                                                                array( array( 69,
                                                                              63,
                                                                              2929 ),
                                                                       array( 70,
                                                                              12,
                                                                              2942 ),
                                                                       "design/standard/templates/package/list.tpl" ) ) ),
                                                  "section",
                                                  array( "show" => array( array( 4,
                                                                                 array( "",
                                                                                        3,
                                                                                        "item" ),
                                                                                 false ),
                                                                          array( 5,
                                                                                 array( array( 3,
                                                                                               "is_installed",
                                                                                               false ) ),
                                                                                 false ) ) ),
                                                  array( array( 66,
                                                                12,
                                                                2741 ),
                                                         array( 66,
                                                                44,
                                                                2773 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "        ",
                                                  array( array( 70,
                                                                19,
                                                                2952 ),
                                                         array( 71,
                                                                8,
                                                                2961 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 4,
                                                  false,
                                                  "section-else",
                                                  array(),
                                                  array( array( 71,
                                                                8,
                                                                2962 ),
                                                         array( 71,
                                                                20,
                                                                2974 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "            ",
                                                  array( array( 71,
                                                                20,
                                                                2975 ),
                                                         array( 72,
                                                                12,
                                                                2988 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 1,
                                                                "Imported",
                                                                false ),
                                                         array( 6,
                                                                array( "i18n",
                                                                       array( array( 1,
                                                                                     "design/standard/package",
                                                                                     false ) ) ),
                                                                false ) ),
                                                  array( array( 72,
                                                                12,
                                                                2989 ),
                                                         array( 72,
                                                                54,
                                                                3031 ),
                                                         "design/standard/templates/package/list.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\n        ",
                                                  array( array( 72,
                                                                54,
                                                                3032 ),
                                                         array( 73,
                                                                8,
                                                                3041 ),
                                                         "design/standard/templates/package/list.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          3,
                                                                          "item" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "install_type",
                                                                                 false ) ),
                                                                   false ),
                                                            array( 6,
                                                                   array( "eq",
                                                                          array( array( 1,
                                                                                        "install",
                                                                                        false ) ) ),
                                                                   false ) ) ),
                                    array( array( 65,
                                                  8,
                                                  2678 ),
                                           array( 65,
                                                  56,
                                                  2726 ),
                                           "design/standard/templates/package/list.tpl" ) ),
                             array( 2,
                                    false,
                                    "    </td>\n</tr>",
                                    array( array( 73,
                                                  15,
                                                  3051 ),
                                           array( 76,
                                                  0,
                                                  3068 ),
                                           "design/standard/templates/package/list.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Package",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "package_list" ),
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
                       array( array( 56,
                                    0,
                                    2069 ),
                             array( 56,
                                    70,
                                    2139 ),
                             "design/standard/templates/package/list.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$tpl->processOperator( "and",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "package_list" ),
                                            false ),
                                     array( 6,
                                            array( "gt",
                                                   array( array( 2,
                                                                 0,
                                                                 false ) ) ),
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "can_remove" ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "<tr>\n    <td colspan=\"5\">\n    <div class=\"buttonblock\">\n        <input class=\"button\" type=\"submit\" name=\"RemovePackageButton\" value=\"";

$var = "Remove package";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "\" />\n    </div>\n    </td>\n</tr>";

}

$text .= "</table>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult2 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "can_create" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 3,
                                                                                                                "package",
                                                                                                                false ) ),
                                                                                                  array( array( 3,
                                                                                                                "can_create",
                                                                                                                false ) ) ),
                                                                                           false ) ),
                                                             "can_import" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 3,
                                                                                                                "package",
                                                                                                                false ) ),
                                                                                                  array( array( 3,
                                                                                                                "can_import",
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 89,
                                                                    0,
                                                                    3393 ),
                                                             array( 90,
                                                                    44,
                                                                    3481 ),
                                                             "design/standard/templates/package/list.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "\n<div class=\"buttonblock\">";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "can_import" );

if ( $show )
{

unset( $show );

$text .= "    <input class=\"button\" type=\"submit\" name=\"InstallPackageButton\" value=\"";

$var = "Import package";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "can_create" );

if ( $show )
{

unset( $show );

$text .= "    <input class=\"button\" type=\"submit\" name=\"CreatePackageButton\" value=\"";

$var = "Create package";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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
                                         $letResult2 );

$text .= "\n";

}

$text .= "\n\n</form>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
