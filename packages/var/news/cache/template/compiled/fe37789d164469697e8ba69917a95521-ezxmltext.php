<?php
// URI:       design:content/datatype/view/ezxmltext.tpl
// Filename:  design/standard/templates/content/datatype/view/ezxmltext.tpl
// Timestamp: 1069664410 (Mon Nov 24 10:00:10 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "content";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "output";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "output_text";
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


?>
