<?php
// URI:       design:page_menuheadgray.tpl
// Filename:  design/admin/templates/page_menuheadgray.tpl
// Timestamp: 1062422506 (Mon Sep 1 15:21:46 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "\n<table width=\"66\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n    <td class=\"menuheadgraygfx\" width=\"3\">\n    <img src=";

$var = "dark-left-corner.gif";
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

$text .= " alt=\"\"/></td>\n    <td class=\"menuheadgraytopline\" width=\"60\">\n    <img src=";

$var = "1x1.gif";
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

$text .= " alt=\"\" width=\"60\" height=\"1\" /></td>\n    <td class=\"menuheadgraygfx\" width=\"3\">\n    <img src=";

$var = "dark-right-corner.gif";
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

$text .= " alt=\"\"/></td>\n</tr>\n<tr>\n    <td class=\"menuheadgrayleftline\" width=\"3\">\n    <img src=";

$var = "1x1.gif";
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

$text .= " alt=\"\" width=\"1\" height=\"15\" /></td>\n    <td class=\"menuheadgray\">\n    <p class=\"menuheadselected\">\n    <a class=\"menuheadlinkgray\" href=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "menu_url" );
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

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "menu_text" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "</a>\n    </p>\n    </td>\n    <td class=\"menuheadgrayrightline\" width=\"3\">\n    <img src=";

$var = "1x1.gif";
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

$text .= " alt=\"\" width=\"1\" height=\"15\" /></td>\n</tr>\n</table>";


?>
