<?php
// URI:       design:page_menuheadselected.tpl
// Filename:  design/admin/templates/page_menuheadselected.tpl
// Timestamp: 1062422506 (Mon Sep 1 15:21:46 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "\n<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n    <td class=\"menuheadselectedgfx\">\n    <img src=";

$var = "light-left-corner.gif";
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

$text .= " alt=\"\"/></td>\n    <td style=\"background-color: #c1d5ef; background-image:url('";

$var = "light-top.gif";
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

$text .= "'); background-repeat: repeat;\">\n    <img src=";

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

$text .= " alt=\"\" width=\"60\" height=\"1\" /></td>\n    <td class=\"menuheadselectedgfx\">\n    <img src=";

$var = "light-right-corner.gif";
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

$text .= " alt=\"\"/></td>\n</tr>\n<tr>\n    <td style=\"background-color: #c1d5ef; background-image:url('";

$var = "bgtilelight.gif";
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

$text .= "'); background-position: left bottom; background-repeat: repeat;\">\n    <img src=";

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

$text .= " alt=\"\" width=\"1\" height=\"19\" /></td>\n    <td style=\"background-color: #c1d5ef; color: #4373b4; text-align: center;  background-image:url('";

$var = "bgtilelight.gif";
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

$text .= "'); background-position: left bottom; background-repeat: repeat;\">\n    <p class=\"menuheadselected\">\n    <a class=\"menuheadlink\" href=";

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

$text .= "</a>\n    </p>\n    </td>\n    <td style=\"background-color: #c1d5ef;background-image:url('";

$var = "bgtilelight.gif";
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

$text .= "'); background-position: left bottom; background-repeat: repeat;\">\n    <img src=";

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

$text .= " alt=\"\" width=\"1\" height=\"19\" /></td>\n</tr>\n</table>\n";


?>
