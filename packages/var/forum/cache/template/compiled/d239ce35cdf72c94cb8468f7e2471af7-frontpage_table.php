<?php
// URI:       design:content/datatype/view/ezxmltags/table.tpl
// Filename:  design/forum/override/templates/frontpage_table.tpl
// Timestamp: 1068813064 (Fri Nov 14 13:31:04 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/forum/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<table class=\"frontpagelist\" cellspacing=\"0\">\n";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "rows" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\n</table>\n";


?>
