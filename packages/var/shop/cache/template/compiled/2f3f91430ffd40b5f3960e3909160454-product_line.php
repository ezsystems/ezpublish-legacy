<?php
// URI:       design:node/view/line.tpl
// Filename:  design/shop/override/templates/product_line.tpl
// Timestamp: 1069775370 (Tue Nov 25 16:49:30 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div class=\"productline\">\n\n<h2><a href=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "url_alias";
$var = compiledFetchAttribute( $var, $var1 );
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

$text .= "</a></h2>\n\n<div class=\"listimage\">\n<a href=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "url_alias";
$var = compiledFetchAttribute( $var, $var1 );
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
                                                                        "image",
                                                                        false ) ),
                                                          false ) ),
                             "image_class" => array( array( 3,
                                                            "small",
                                                            false ) ) ),
                       array( array( 6,
                                    30,
                                    143 ),
                             array( 6,
                                    104,
                                    217 ),
                             "design/shop/override/templates/product_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</a>\n</div>\n";

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
                       array( array( 9,
                                    0,
                                    232 ),
                             array( 9,
                                    62,
                                    294 ),
                             "design/shop/override/templates/product_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n<div class=\"price\">\n    <p>";

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
                                                                        "price",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 12,
                                    7,
                                    325 ),
                             array( 12,
                                    63,
                                    381 ),
                             "design/shop/override/templates/product_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</p>\n</div>\n\n<div class=\"readmore\">\n    <p><a href=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "url_alias";
$var = compiledFetchAttribute( $var, $var1 );
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

$var = "Read more";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/shop/layout",
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

$text .= "</a></p>\n</div>\n\n</div>\n";


?>
