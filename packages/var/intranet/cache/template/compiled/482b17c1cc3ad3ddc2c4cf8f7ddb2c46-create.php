<?php
// URI:       design:package/create.tpl
// Filename:  design/standard/templates/package/create.tpl
// Timestamp: 1069675388 (Mon Nov 24 13:03:08 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div id=\"package\">\n<form method=\"post\" action=";

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

$text .= ">\n\n    <h1>";

$var = "Create package";
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

$text .= "</h1>\n\n    <h2>";

$var = "Available wizards";
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

$text .= "</h2>\n    <p>";

$var = "Choose one of the following wizards for creating a package";
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

$text .= "</p>\n\n    <div class=\"creatorlist\">\n    <ul>\n    ";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "        <li><input type=\"radio\" name=\"CreatorItemID\" value=\"",
                                    array( array( 11,
                                                  42,
                                                  395 ),
                                           array( 12,
                                                  60,
                                                  456 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "creator" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "item",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "id",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 12,
                                                  60,
                                                  457 ),
                                           array( 12,
                                                  81,
                                                  478 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" ",
                                    array( array( 12,
                                                  81,
                                                  479 ),
                                           array( 12,
                                                  83,
                                                  481 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 4,
                                    array( array( 2,
                                                  false,
                                                  "checked=\"checked\"",
                                                  array( array( 12,
                                                                118,
                                                                518 ),
                                                         array( 12,
                                                                135,
                                                                535 ),
                                                         "design/standard/templates/package/create.tpl" ) ) ),
                                    "section",
                                    array( "show" => array( array( 4,
                                                                   array( "",
                                                                          2,
                                                                          "creator" ),
                                                                   false ),
                                                            array( 5,
                                                                   array( array( 3,
                                                                                 "index",
                                                                                 false ) ),
                                                                   false ),
                                                            array( 6,
                                                                   array( "eq",
                                                                          array( array( 2,
                                                                                        0,
                                                                                        false ) ) ),
                                                                   false ) ) ),
                                    array( array( 12,
                                                  83,
                                                  482 ),
                                           array( 12,
                                                  118,
                                                  517 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 2,
                                    false,
                                    " />",
                                    array( array( 12,
                                                  142,
                                                  545 ),
                                           array( 12,
                                                  145,
                                                  548 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         2,
                                                         "creator" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "item",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "name",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "wash" ),
                                                  false ) ),
                                    array( array( 12,
                                                  145,
                                                  549 ),
                                           array( 12,
                                                  168,
                                                  572 ),
                                           "design/standard/templates/package/create.tpl" ) ),
                             array( 2,
                                    false,
                                    " </li>\n    ",
                                    array( array( 12,
                                                  168,
                                                  573 ),
                                           array( 13,
                                                  4,
                                                  584 ),
                                           "design/standard/templates/package/create.tpl" ) ) ),
                       array( "var" => array( array( 3,
                                                    "creator",
                                                    false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "creator_list" ),
                                                     false ) ) ),
                       array( array( 11,
                                    4,
                                    356 ),
                             array( 11,
                                    42,
                                    394 ),
                             "design/standard/templates/package/create.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    </ul>\n    </div>\n\n    <div class=\"buttonblock\">\n        <input class=\"button\" type=\"submit\" name=\"CreatePackageButton\" value=\"";

$var = "Create package";
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

$text .= "\" />\n    </div>\n\n</form>\n</div>\n";


?>
