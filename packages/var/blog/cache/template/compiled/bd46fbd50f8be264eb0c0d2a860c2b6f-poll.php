<?php
// URI:       design:node/view/full.tpl
// Filename:  design/blog/override/templates/poll.tpl
// Timestamp: 1069686113 (Mon Nov 24 16:01:53 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/blog/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div id=\"poll\">\n<form method=\"post\" action=";

$var = "content/action";
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

$text .= ">\n\n<input type=\"hidden\" name=\"ContentNodeID\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "node_id";
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

$text .= "\" />\n<input type=\"hidden\" name=\"ContentObjectID\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "id";
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

$text .= "\" />\n<input type=\"hidden\" name=\"ViewMode\" value=\"full\" />\n\n<h4>";

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

$text .= "</h4>";

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
                                                                        "option",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 9,
                                    0,
                                    289 ),
                             array( 9,
                                    57,
                                    346 ),
                             "design/blog/override/templates/poll.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "      <div class=\"block\">\n      <input class=\"button\" type=\"submit\" name=\"",
                                    array( array( 10,
                                                  105,
                                                  455 ),
                                           array( 12,
                                                  48,
                                                  530 ),
                                           "design/blog/override/templates/poll.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "ContentAction",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "action",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 12,
                                                  48,
                                                  531 ),
                                           array( 12,
                                                  74,
                                                  557 ),
                                           "design/blog/override/templates/poll.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" value=\"Vote\" />\n      </div>",
                                    array( array( 12,
                                                  74,
                                                  558 ),
                                           array( 14,
                                                  0,
                                                  589 ),
                                           "design/blog/override/templates/poll.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "ContentAction",
                                                     false ) ),
                             "loop" => array( array( 4,
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
                                                                   "content_action_list",
                                                                   false ) ),
                                                     false ) ),
                             "show" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "content_object" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "content_action_list",
                                                                   false ) ),
                                                     false ) ) ),
                       array( array( 10,
                                    0,
                                    349 ),
                             array( 10,
                                    105,
                                    454 ),
                             "design/blog/override/templates/poll.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "<div class=\"block\">\n    <a href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/content/collectedinfo/",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "node" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "node_id",
                                                          false ) ),
                                            false ) ),
                              array( array( 1,
                                            "/",
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

$text .= ">Result</a>\n</div>\n</form>\n\n<a href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/content/view/full/",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "node" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "parent_node_id",
                                                          false ) ),
                                            false ) ),
                              array( array( 1,
                                            "/",
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

$text .= "><h4>View all polls</h4></a>\n</div>\n\n";


?>
