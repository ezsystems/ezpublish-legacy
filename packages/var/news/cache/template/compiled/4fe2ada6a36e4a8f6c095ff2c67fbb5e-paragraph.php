<?php
// URI:       design:content/datatype/view/ezxmltags/paragraph.tpl
// Filename:  design/standard/templates/content/datatype/view/ezxmltags/paragraph.tpl
// Timestamp: 1062422393 (Mon Sep 1 15:19:53 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<p>\n";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "content" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\n</p>\n";


?>
