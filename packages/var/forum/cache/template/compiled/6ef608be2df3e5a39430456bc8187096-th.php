<?php
// URI:       design:content/datatype/view/ezxmltags/th.tpl
// Filename:  design/standard/templates/content/datatype/view/ezxmltags/th.tpl
// Timestamp: 1062422394 (Mon Sep 1 15:19:54 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/forum/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<th align=\"top\" ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "colspan" );

if ( $show )
{

unset( $show );

$text .= " colspan=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "colspan" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\"";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "rowspan" );

if ( $show )
{

unset( $show );

$text .= " rowspan=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "rowspan" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\"";

}

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "width" );

if ( $show )
{

unset( $show );

$text .= " width=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "width" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\"";

}

$text .= ">";

$textElements = array();
$tpl->processFunction( "switch", $textElements,
                       array( array( 2,
                                    false,
                                    "  ",
                                    array( array( 2,
                                                  29,
                                                  205 ),
                                           array( 3,
                                                  2,
                                                  208 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "  &nbsp;\n  ",
                                                  array( array( 3,
                                                                22,
                                                                230 ),
                                                         array( 5,
                                                                2,
                                                                242 ),
                                                         "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ) ),
                                    "case",
                                    array( "match" => array( array( 1,
                                                                    "<p></p>",
                                                                    false ) ) ),
                                    array( array( 3,
                                                  2,
                                                  209 ),
                                           array( 3,
                                                  22,
                                                  229 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                             array( 2,
                                    false,
                                    "  ",
                                    array( array( 5,
                                                  6,
                                                  249 ),
                                           array( 6,
                                                  2,
                                                  252 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "  &nbsp;\n  ",
                                                  array( array( 6,
                                                                15,
                                                                267 ),
                                                         array( 8,
                                                                2,
                                                                279 ),
                                                         "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ) ),
                                    "case",
                                    array( "match" => array( array( 1,
                                                                    "",
                                                                    false ) ) ),
                                    array( array( 6,
                                                  2,
                                                  253 ),
                                           array( 6,
                                                  15,
                                                  266 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                             array( 2,
                                    false,
                                    "  ",
                                    array( array( 8,
                                                  6,
                                                  286 ),
                                           array( 9,
                                                  2,
                                                  289 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "  ",
                                                  array( array( 9,
                                                                6,
                                                                295 ),
                                                         array( 10,
                                                                2,
                                                                298 ),
                                                         "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                                           array( 3,
                                                  false,
                                                  array( array( 4,
                                                                array( "",
                                                                       2,
                                                                       "content" ),
                                                                false ) ),
                                                  array( array( 10,
                                                                2,
                                                                299 ),
                                                         array( 10,
                                                                10,
                                                                307 ),
                                                         "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ),
                                           array( 2,
                                                  false,
                                                  "\n  ",
                                                  array( array( 10,
                                                                10,
                                                                308 ),
                                                         array( 11,
                                                                2,
                                                                311 ),
                                                         "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ) ),
                                    "case",
                                    array(),
                                    array( array( 9,
                                                  2,
                                                  290 ),
                                           array( 9,
                                                  6,
                                                  294 ),
                                           "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Sw",
                                                     false ) ),
                             "match" => array( array( 4,
                                                      array( "",
                                                             2,
                                                             "content" ),
                                                      false ) ) ),
                       array( array( 2,
                                    0,
                                    175 ),
                             array( 2,
                                    29,
                                    204 ),
                             "design/standard/templates/content/datatype/view/ezxmltags/th.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</th>";


?>
