<?php
// URI:       design:node/view/line.tpl
// Filename:  design/blog/override/templates/logline.tpl
// Timestamp: 1069775360 (Tue Nov 25 16:49:20 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/blog/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "archive_view" => array( array( 2,
                                                                                                        false,
                                                                                                        false ) ) ),
                                                                 array( array( 1,
                                                                               0,
                                                                               1 ),
                                                                        array( 1,
                                                                               28,
                                                                               29 ),
                                                                        "design/blog/override/templates/logline.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

$text .= "<div class=\"log\">\n";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "archive_view" );

if ( $show )
{

unset( $show );

$text .= "\n    <h2><a href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "content/view/full/",
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

$text .= " >";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
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

$text .= "</a>\n        <em class=\"byline\">";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "published";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "datetime",
                       array( array( array( 3,
                                            "custom",
                                            false ) ),
                              array( array( 1,
                                            "%d %M %Y",
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

$text .= "\n        ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "node" );
$show1 = "object";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "data_map";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "enable_comments";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "content";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "            <a class=\"comments\" href=";

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

$tpl->processOperator( "fetch",
                       array( array( array( 1,
                                            "content",
                                            false ) ),
                              array( array( 1,
                                            "list_count",
                                            false ) ),
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 3,
                                                                 "parent_node_id",
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

$text .= " comments</a>\n        ";

}
else
{

unset( $show );

$text .= "            ";

$var = "Comments disabled";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/blog/layout",
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

$text .= "\n        ";

}

$text .= "</em>\n    </h2>\n    <div class=\"logentry\">\n        ";

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
                                                                        "data_map",
                                                                        false ) ),
                                                          false ),
                                                   array( 5,
                                                          array( array( 3,
                                                                        "log",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 15,
                                    8,
                                    620 ),
                             array( 15,
                                    55,
                                    667 ),
                             "design/blog/override/templates/logline.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    </div>\n";

}
else
{

unset( $show );

$text .= "\n  <h2>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "published";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "datetime",
                       array( array( array( 1,
                                            "custom",
                                            false ) ),
                              array( array( 1,
                                            "%l | %j %F %Y",
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

$text .= "</h2>\n  <div class=\"logentry\">\n    <h3><a href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "content/view/full/",
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

$text .= ">";

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

$text .= "</a></h3>\n    ";

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
                                                                        "log",
                                                                        false ) ),
                                                          false ) ) ),
                       array( array( 23,
                                    4,
                                    891 ),
                             array( 23,
                                    58,
                                    945 ),
                             "design/blog/override/templates/logline.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "    <div class=\"byline\">\n       <p>\n          ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "object";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "published";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "datetime",
                       array( array( array( 1,
                                            "custom",
                                            false ) ),
                              array( array( 1,
                                            "%g:%i%a",
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

$text .= " in ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "node" );
$var1 = "parent";
$var = compiledFetchAttribute( $var, $var1 );
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

$text .= " | \n          ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "node" );
$show1 = "object";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "data_map";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "enable_comments";
$show = compiledFetchAttribute( $show, $show1 );
$show1 = "content";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "              <a href=";

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

$tpl->processOperator( "fetch",
                       array( array( array( 1,
                                            "content",
                                            false ) ),
                              array( array( 1,
                                            "list_count",
                                            false ) ),
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 3,
                                                                 "parent_node_id",
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

$text .= " comments</a>\n          ";

}
else
{

unset( $show );

$text .= "              ";

$var = "Comments disabled";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/blog/layout",
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

$text .= "\n          ";

}

$text .= "  \n       </p>\n    </div>\n  </div>\n";

}

$text .= "\n</div>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );


?>
