<?php
// URI:       design:package/create/changelog.tpl
// Filename:  design/standard/templates/package/create/changelog.tpl
// Timestamp: 1069675391 (Mon Nov 24 13:03:11 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "current_user" => array( array( 6,
                                                                                             array( "fetch",
                                                                                                    array( array( 3,
                                                                                                                  "user",
                                                                                                                  false ) ),
                                                                                                    array( array( 3,
                                                                                                                  "current_user",
                                                                                                                  false ) ) ),
                                                                                             false ) ) ),
                                                      array( array( 1,
                                                                    0,
                                                                    1 ),
                                                             array( 1,
                                                                    44,
                                                                    45 ),
                                                             "design/standard/templates/package/create/changelog.tpl" ),
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

$text .= "\">\n\n    <form method=\"post\" action=";

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
                                    218 ),
                             array( 7,
                                    49,
                                    263 ),
                             "design/standard/templates/package/create/changelog.tpl" ),
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
                                    271 ),
                             array( 9,
                                    43,
                                    310 ),
                             "design/standard/templates/package/create/changelog.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <p>";

$var = "Please provide information on the changes.";
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

$text .= "</p>\n\n    <div class=\"changelog_person\">\n        <label>";

$var = "Name";
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

$text .= "</label>\n        <input class=\"textline\" type=\"text\" name=\"PackageChangelogPerson\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "changelog_person";
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

$text .= "\" />\n    </div>\n\n    <div class=\"changelog_email\">\n        <label>";

$var = "E-Mail";
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

$text .= "</label>\n        <input class=\"textline\" type=\"text\" name=\"PackageChangelogEmail\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "changelog_email";
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

$text .= "\" />\n    </div>\n\n    <div class=\"changelog_text\">\n        <label>";

$var = "Changes";
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

$text .= "</label>\n        <p>";

$var = "Start an entry with a marker ( %emstart-%emend (dash) or %emstart*%emend (asterix) ) at the beginning of the line.\nThe change will continue to the next change marker.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 1,
                                                                 "%emstart",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "<em>",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "%emend",
                                                                 false ) ),
                                                   array( array( 1,
                                                                 "</em>",
                                                                 false ) ) ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "break",
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

$text .= "</p>\n        <textarea class=\"description\" name=\"PackageChangelogText\">";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "changelog_text";
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

$text .= "</textarea>\n    </div>\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/navigator.tpl",
                                                    false ) ) ),
                       array( array( 30,
                                    4,
                                    1384 ),
                             array( 30,
                                    46,
                                    1426 ),
                             "design/standard/templates/package/create/changelog.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    </form>\n\n    </div>\n</div>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
