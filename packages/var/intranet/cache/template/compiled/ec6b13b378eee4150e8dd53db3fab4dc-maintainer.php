<?php
// URI:       design:package/create/maintainer.tpl
// Filename:  design/standard/templates/package/create/maintainer.tpl
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
                                                                                             false ) ),
                                                             "maintainer_role_list" => array( array( 6,
                                                                                                     array( "fetch",
                                                                                                            array( array( 3,
                                                                                                                          "package",
                                                                                                                          false ) ),
                                                                                                            array( array( 3,
                                                                                                                          "maintainer_role_list",
                                                                                                                          false ) ),
                                                                                                            array( array( 6,
                                                                                                                          array( "hash",
                                                                                                                                 array( array( 3,
                                                                                                                                               "check_roles",
                                                                                                                                               false ) ),
                                                                                                                                 array( array( 2,
                                                                                                                                               true,
                                                                                                                                               false ) ) ),
                                                                                                                          false ) ) ),
                                                                                                     false ) ) ),
                                                      array( array( 1,
                                                                    0,
                                                                    1 ),
                                                             array( 2,
                                                                    93,
                                                                    139 ),
                                                             "design/standard/templates/package/create/maintainer.tpl" ),
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
                       array( array( 8,
                                    4,
                                    312 ),
                             array( 8,
                                    49,
                                    357 ),
                             "design/standard/templates/package/create/maintainer.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/header.tpl",
                                                    false ) ) ),
                       array( array( 10,
                                    4,
                                    365 ),
                             array( 10,
                                    43,
                                    404 ),
                             "design/standard/templates/package/create/maintainer.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <p>";

$var = "Please provide information on the maintainer of the package.";
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

$text .= "</p>\n\n    <div class=\"maintainer_person\">\n        <label>";

$var = "Name";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ),
                              array( array( 1,
                                            "Maintainer name",
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

$text .= "</label>\n        <input class=\"textline\" type=\"text\" name=\"PackageMaintainerPerson\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "maintainer_person";
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

$text .= "\" />\n    </div>\n\n    <div class=\"maintainer_email\">\n        <label>";

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

$text .= "</label>\n        <input class=\"textline\" type=\"text\" name=\"PackageMaintainerEmail\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "maintainer_email";
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

$text .= "\" />\n    </div>\n\n    <div class=\"maintainer_role\">\n        <label>";

$var = "Role";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ),
                              array( array( 1,
                                            "Maintainer role",
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

$text .= "</label>\n        <select class=\"combobox\" name=\"PackageMaintainerRole\">\n        ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "            <option value=\"",
                                    array( array( 27,
                                                  51,
                                                  1248 ),
                                           array( 28,
                                                  27,
                                                  1276 ),
                                           "design/standard/templates/package/create/maintainer.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "role" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "id",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 28,
                                                  27,
                                                  1277 ),
                                           array( 28,
                                                  40,
                                                  1290 ),
                                           "design/standard/templates/package/create/maintainer.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">",
                                    array( array( 28,
                                                  40,
                                                  1291 ),
                                           array( 28,
                                                  42,
                                                  1293 ),
                                           "design/standard/templates/package/create/maintainer.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "role" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "name",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 28,
                                                  42,
                                                  1294 ),
                                           array( 28,
                                                  57,
                                                  1309 ),
                                           "design/standard/templates/package/create/maintainer.tpl" ) ),
                             array( 2,
                                    false,
                                    "</option>\n        ",
                                    array( array( 28,
                                                  57,
                                                  1310 ),
                                           array( 29,
                                                  8,
                                                  1328 ),
                                           "design/standard/templates/package/create/maintainer.tpl" ) ) ),
                       array( "var" => array( array( 3,
                                                    "role",
                                                    false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "maintainer_role_list" ),
                                                     false ) ) ),
                       array( array( 27,
                                    8,
                                    1204 ),
                             array( 27,
                                    51,
                                    1247 ),
                             "design/standard/templates/package/create/maintainer.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "        </select>\n    </div>\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/navigator.tpl",
                                                    false ) ) ),
                       array( array( 33,
                                    4,
                                    1374 ),
                             array( 33,
                                    46,
                                    1416 ),
                             "design/standard/templates/package/create/maintainer.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    </form>\n\n    </div>\n</div>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
