<?php
// URI:       design:content/datatype/view/ezstring.tpl
// Filename:  design/standard/templates/content/datatype/view/ezstring.tpl
// Timestamp: 1062422389 (Mon Sep 1 15:19:49 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "data_text";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "wash",
                       array( array( array( 3,
                                            "xhtml",
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


?>
