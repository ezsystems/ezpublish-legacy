<?php
// URI:       design:node/view/line.tpl
// Filename:  design/news/override/templates/article_line.tpl
// Timestamp: 1069775381 (Tue Nov 25 16:49:41 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div class=\"article_line\">\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "content_object" => array( array( 4,
                                                                                                          array( "",
                                                                                                                 2,
                                                                                                                 "node" ),
                                                                                                          false ),
                                                                                                   array( 5,
                                                                                                          array( array( 3,
                                                                                                                        "object",
                                                                                                                        false ) ),
                                                                                                          false ) ),
                                                                        "content_version" => array( array( 4,
                                                                                                           array( "",
                                                                                                                  2,
                                                                                                                  "node" ),
                                                                                                           false ),
                                                                                                    array( 5,
                                                                                                           array( array( 3,
                                                                                                                         "contentobject_version_object",
                                                                                                                         false ) ),
                                                                                                           false ) ) ),
                                                                 array( array( 3,
                                                                               0,
                                                                               29 ),
                                                                        array( 4,
                                                                               59,
                                                                               124 ),
                                                                        "design/news/override/templates/article_line.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

$text .= "\n<div class=\"imageright\">\n    <a class=\"small\" href=";

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

$text .= ">\n      ";

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
                                                                        "thumbnail",
                                                                        false ) ),
                                                          false ) ),
                             "image_class" => array( array( 3,
                                                            "small",
                                                            false ) ) ),
                       array( array( 8,
                                    6,
                                    210 ),
                             array( 8,
                                    84,
                                    288 ),
                             "design/news/override/templates/article_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "     </a>\n</div>\n\n<h2>";

$textElements = array();
$tpl->processFunction( "attribute_view_gui", $textElements,
                       false,
                       array( "attribute" => array( array( 4,
                                                          array( "",
                                                                 2,
                                                                 "content_version" ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "data_map",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "title",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 12,
                                    4,
                                    313 ),
                             array( 12,
                                    64,
                                    373 ),
                             "design/news/override/templates/article_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</h2>\n\n<div class=\"byline\">\n  <p>\n  (";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "published";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "add",
                       array( array( array( 2,
                                            21600,
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "l10n",
                       array( array( array( 3,
                                            "datetime",
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

$text .= ")\n  </p>\n</div>\n\n\n<div class=\"object_brief\">\n  ";

$textElements = array();
$tpl->processFunction( "attribute_view_gui", $textElements,
                       false,
                       array( "attribute" => array( array( 4,
                                                          array( "",
                                                                 2,
                                                                 "content_version" ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "data_map",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "intro",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 22,
                                    2,
                                    511 ),
                             array( 22,
                                    62,
                                    571 ),
                             "design/news/override/templates/article_line.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "</div>\n\n\n<div class=\"readmore\">\n<a href=";

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
                                            "design/news/layout",
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

$text .= "</a>\n</div>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );

$text .= "\n</div>\n";


?>
