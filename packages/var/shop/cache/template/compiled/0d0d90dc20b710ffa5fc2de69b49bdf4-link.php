<?php
// URI:       design:link.tpl
// Filename:  design/standard/templates/link.tpl
// Timestamp: 1065702791 (Thu Oct 9 14:33:11 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "enable_print" => array( array( 2,
                                                                                                        true,
                                                                                                        false ) ) ),
                                                                 array( array( 2,
                                                                               0,
                                                                               31 ),
                                                                        array( 2,
                                                                               27,
                                                                               58 ),
                                                                        "design/standard/templates/link.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

$text .= "\n<link rel=\"Home\" href=";

$var = "/";
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

$text .= " title=\"";

$var = "%sitetitle front page";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 1,
                                                                 "%sitetitle",
                                                                 false ) ),
                                                   array( array( 4,
                                                                 array( "",
                                                                        2,
                                                                        "site" ),
                                                                 false ),
                                                          array( 5,
                                                                 array( array( 3,
                                                                               "title",
                                                                               false ) ),
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

$text .= "\" />\n<link rel=\"Index\" href=";

$var = "/";
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

$text .= " />\n<link rel=\"Top\"  href=";

$var = "/";
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

$text .= " title=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "site_title" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n<link rel=\"Search\" href=";

$var = "content/advancedsearch";
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

$text .= " title=\"";

$var = "Search %sitetitle";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 1,
                                                                 "%sitetitle",
                                                                 false ) ),
                                                   array( array( 4,
                                                                 array( "",
                                                                        2,
                                                                        "site" ),
                                                                 false ),
                                                          array( 5,
                                                                 array( array( 3,
                                                                               "title",
                                                                               false ) ),
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

$text .= "\" />\n<link rel=\"Shortcut icon\" href=";

$var = "favicon.ico";
$tpl->processOperator( "ezimage",
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

$text .= " type=\"image/x-icon\" />\n<link rel=\"Copyright\" href=";

$var = "/ezinfo/copyright";
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

$text .= " />\n<link rel=\"Author\" href=";

$var = "/ezinfo/about";
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

$text .= " />\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "enable_print" );

if ( $show )
{

unset( $show );

$text .= "<link rel=\"Alternate\" href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "layout/set/print/",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "site" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "uri",
                                                          false ) ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "tail",
                                                          false ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
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

$text .= " media=\"print\" title=\"";

$var = "Printable version";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
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

$text .= "\" />";

}

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );


?>
