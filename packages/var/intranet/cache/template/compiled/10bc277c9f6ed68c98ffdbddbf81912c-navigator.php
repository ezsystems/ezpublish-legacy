<?php
// URI:       design:package/navigator.tpl
// Filename:  design/standard/templates/package/navigator.tpl
// Timestamp: 1069675392 (Mon Nov 24 13:03:12 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "current_step" );
$show1 = "next_step";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "    <div class=\"navigator\">\n        <input class=\"button\" type=\"submit\" name=\"NextStepButton\" value=\"";

$var = "Next %arrowright";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 1,
                                                                 "%arrowright",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "&raquo;",
                                                                 false ) ) ),
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

$text .= "\" />\n    </div>";

}
else
{

unset( $show );

$text .= "    <div class=\"navigator\">\n        <input class=\"button\" type=\"submit\" name=\"NextStepButton\" value=\"";

$var = "Finish";
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

$text .= "\" />\n    </div>";

}


?>
