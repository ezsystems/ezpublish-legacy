<?php
// URI:       design:error/kernel/3.tpl
// Filename:  design/standard/templates/error/kernel/3.tpl
// Timestamp: 1062422405 (Mon Sep 1 15:20:05 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div class=\"warning\">\n<h2>";

$var = "Object is unavailable";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$var = "The object you requested is not currently available.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</p>\n<p>";

$var = "Possible reasons for this is.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</p>\n<ul>\n    <li>";

$var = "The id or name of the object was misspelled, try changing it.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</li>\n    <li>";

$var = "The object is no longer available on the site.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</li>\n</ul>\n</div>\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "embed_content" );

if ( $show )
{

unset( $show );

$text .= "\n";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "embed_content" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

}


?>
