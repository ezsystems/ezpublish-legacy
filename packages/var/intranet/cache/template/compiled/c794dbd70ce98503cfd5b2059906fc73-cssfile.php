<?php
// URI:       design:package/creators/ezstyle/cssfile.tpl
// Filename:  design/standard/templates/package/creators/ezstyle/cssfile.tpl
// Timestamp: 1069675390 (Mon Nov 24 13:03:10 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "class_list" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 3,
                                                                                                                "class",
                                                                                                                false ) ),
                                                                                                  array( array( 3,
                                                                                                                "list",
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 1,
                                                                    0,
                                                                    1 ),
                                                             array( 1,
                                                                    35,
                                                                    36 ),
                                                             "design/standard/templates/package/creators/ezstyle/cssfile.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "<div id=\"package\" class=\"create\">\n    <div id=\"sid-";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "current_step" );
$var1 = "id";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "wash",
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

$text .= "\" class=\"pc-";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "creator" );
$var1 = "id";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "wash",
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

$text .= "\">\n\n    <form enctype=\"multipart/form-data\" method=\"post\" action=";

$var = "package/create";
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

$text .= ">\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/create/error.tpl",
                                                    false ) ) ),
                       array( array( 7,
                                    4,
                                    239 ),
                             array( 7,
                                    49,
                                    284 ),
                             "design/standard/templates/package/creators/ezstyle/cssfile.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/header.tpl",
                                                    false ) ) ),
                       array( array( 9,
                                    4,
                                    292 ),
                             array( 9,
                                    43,
                                    331 ),
                             "design/standard/templates/package/creators/ezstyle/cssfile.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <p>";

$var = "Please select a CSS file to be included in the package.";
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

$text .= "</p>\n\n    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"32000000\" />\n    <input class=\"file\" name=\"PackageCSSFile\" type=\"file\" />\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/navigator.tpl",
                                                    false ) ) ),
                       array( array( 16,
                                    4,
                                    571 ),
                             array( 16,
                                    46,
                                    613 ),
                             "design/standard/templates/package/creators/ezstyle/cssfile.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    </form>\n\n    </div>\n</div>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
