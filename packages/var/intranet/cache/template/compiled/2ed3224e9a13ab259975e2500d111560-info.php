<?php
// URI:       design:package/create/info.tpl
// Filename:  design/standard/templates/package/create/info.tpl
// Timestamp: 1069675391 (Mon Nov 24 13:03:11 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

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
                       array( array( 6,
                                    4,
                                    171 ),
                             array( 6,
                                    49,
                                    216 ),
                             "design/standard/templates/package/create/info.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/header.tpl",
                                                    false ) ) ),
                       array( array( 8,
                                    4,
                                    224 ),
                             array( 8,
                                    43,
                                    263 ),
                             "design/standard/templates/package/create/info.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <p>";

$var = "Please provide some basic information for your package.";
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

$text .= "</p>\n\n    <table>\n    <tr>\n\n    <td valign=\"top\">\n        <div class=\"name\">\n            <label>";

$var = "Package name";
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

$text .= "</label>\n            <input class=\"textline\" type=\"text\" name=\"PackageName\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "name";
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

$text .= "\" />\n        </div>\n    \n        <div class=\"summary\">\n            <label>";

$var = "Summary";
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

$text .= "</label>\n            <input class=\"textline\" type=\"text\" name=\"PackageSummary\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "summary";
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

$text .= "\" />\n        </div>\n    \n        <div class=\"description\">\n            <label>";

$var = "Description";
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

$text .= "</label>\n            <textarea class=\"description\" name=\"PackageDescription\">";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "description";
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

$text .= "</textarea>\n        </div>\n    \n        <div class=\"version\">\n            <label>";

$var = "Version";
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

$text .= "</label>\n            <input class=\"shorttextline\" type=\"text\" name=\"PackageVersion\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "version";
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

$text .= "\" />\n        </div>\n    \n        <div class=\"licence\">\n            <label>";

$var = "Licence";
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

$text .= "</label>\n            <input type=\"hidden\" name=\"PackageLicence\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "licence";
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

$text .= "\" />\n            <p>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "licence";
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

$text .= "</p>\n        </div>\n    </td>\n\n    <td valign=\"top\">\n        <div class=\"name\">\n            <label>";

$var = "Package host";
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

$text .= "</label>\n            <input class=\"textline\" type=\"text\" name=\"PackageHost\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "host";
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

$text .= "\" />\n        </div>\n    \n        <div class=\"summary\">\n            <label>";

$var = "Packager";
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

$text .= "</label>\n            <input class=\"textline\" type=\"text\" name=\"PackagePackager\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "packager";
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

$text .= "\" />\n        </div>\n    </td>\n    \n    </tr>\n    </table>\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/navigator.tpl",
                                                    false ) ) ),
                       array( array( 58,
                                    4,
                                    2165 ),
                             array( 58,
                                    46,
                                    2207 ),
                             "design/standard/templates/package/create/info.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    </form>\n\n    </div>\n</div>\n";


?>
