<?php
// URI:       design:content/datatype/view/ezprice.tpl
// Filename:  design/standard/templates/content/datatype/view/ezprice.tpl
// Timestamp: 1062422389 (Mon Sep 1 15:19:49 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "attribute" );
$show1 = "content";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "has_discount";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$var = "Price:";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/datatype",
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

$text .= " <strike>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "content";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "inc_vat_price";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "currency",
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

$text .= "</strike> <br/>\n";

$var = "Your price:";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/datatype",
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

$text .= " ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "content";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "discount_price_inc_vat";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "currency",
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

$text .= "<br />\n";

$var = "You save:";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/datatype",
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

$text .= " ";

$tpl->processOperator( "sub",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "attribute" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "content",
                                                          false ) ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "inc_vat_price",
                                                          false ) ),
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "attribute" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "content",
                                                          false ) ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "discount_price_inc_vat",
                                                          false ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "currency",
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

$text .= " ( ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "content";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "discount_percent";
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

$text .= " % )";

}
else
{

unset( $show );

$var = "Price";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/datatype",
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

$text .= " ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "attribute" );
$var1 = "content";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "inc_vat_price";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "currency",
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

$text .= " <br/>";

}


?>
