<?php
// URI:       design:node/view/full.tpl
// Filename:  design/forum/override/templates/forum_frontpage.tpl
// Timestamp: 1068820878 (Fri Nov 14 15:41:18 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/forum/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div id=\"folder\">\n\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "node" );
$show1 = "object";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "can_edit";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"editbutton\">\n   <input class=\"button\" type=\"submit\" name=\"EditButton\" value=\"";

$var = "Edit";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/node/view",
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

$text .= "\" />\n</div>";

}

$text .= "\n<h1>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "name";
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

$text .= "</h1>\n\n\n<div class=\"object_content\">";

$textElements = array();
$tpl->processFunction( "attribute_view_gui", $textElements,
                       false,
                       array( "attribute" => array( array( 4,
                                                          array( "",
                                                                 2,
                                                                 "node" ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "object",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "data_map",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "description",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 14,
                                    0,
                                    266 ),
                             array( 14,
                                    62,
                                    328 ),
                             "design/forum/override/templates/forum_frontpage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</div>\n\n</div>";


?>
