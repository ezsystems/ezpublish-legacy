<?php
// URI:       design:page_mainarea.tpl
// Filename:  design/standard/templates/page_mainarea.tpl
// Timestamp: 1062422344 (Mon Sep 1 15:19:04 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:page_warning.tpl",
                                                    false ) ) ),
                       array( array( 1,
                                    0,
                                    1 ),
                             array( 1,
                                    37,
                                    38 ),
                             "design/standard/templates/page_mainarea.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n";

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

$text .= "\n";


?>
