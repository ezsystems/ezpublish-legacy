<?php
// URI:       design:package/creators/ezstyle/imagefiles.tpl
// Filename:  design/standard/templates/package/creators/ezstyle/imagefiles.tpl
// Timestamp: 1069675389 (Mon Nov 24 13:03:09 CET 2003)
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
                                                             "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ),
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
                             "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ),
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
                             "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    <p>";

$var = "Select an image file to be included in the package and click Next.\nWhen you are done with adding images click Next without choosing an image.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
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

$text .= "</p>\n    \n    ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$show1 = "imagefiles";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "    <div class=\"files\">\n        <h3>";

$var = "Currently added image files";
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

$text .= "</h3>\n        <ul>\n            ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "            <li>",
                                    array( array( 18,
                                                  66,
                                                  767 ),
                                           array( 19,
                                                  16,
                                                  784 ),
                                           "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "imagefile" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "filename",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 19,
                                                  16,
                                                  785 ),
                                           array( 19,
                                                  40,
                                                  809 ),
                                           "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ) ),
                             array( 2,
                                    false,
                                    "</li>\n            ",
                                    array( array( 19,
                                                  40,
                                                  810 ),
                                           array( 20,
                                                  12,
                                                  828 ),
                                           "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ) ) ),
                       array( "var" => array( array( 3,
                                                    "imagefile",
                                                    false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "persistent_data" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "imagefiles",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 18,
                                    12,
                                    712 ),
                             array( 18,
                                    66,
                                    766 ),
                             "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "        </ul>\n    </div>\n    ";

}

$text .= "\n    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"32000000\" />\n    <input class=\"file\" name=\"PackageImageFile\" type=\"file\" />\n\n    ";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "uri" => array( array( 1,
                                                    "design:package/navigator.tpl",
                                                    false ) ) ),
                       array( array( 28,
                                    4,
                                    1015 ),
                             array( 28,
                                    46,
                                    1057 ),
                             "design/standard/templates/package/creators/ezstyle/imagefiles.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n    </form>\n\n    </div>\n</div>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
