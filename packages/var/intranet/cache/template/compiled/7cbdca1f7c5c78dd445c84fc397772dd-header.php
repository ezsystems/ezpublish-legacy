<?php
// URI:       design:package/header.tpl
// Filename:  design/standard/templates/package/header.tpl
// Timestamp: 1069675392 (Mon Nov 24 13:03:12 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "    <input type=\"hidden\" name=\"CreatorItemID\" value=\"";

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

$text .= "\" />\n    <input type=\"hidden\" name=\"CreatorStepID\" value=\"";

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

$text .= "\" />\n    <input type=\"hidden\" name=\"PackageStep\" value=\"1\" />\n\n    <h1>";

$var = "Package wizard: %wizardname";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/package",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "hash",
                                                   array( array( 1,
                                                                 "%wizardname",
                                                                 false ) ),
                                                   array( array( 4,
                                                                 array( "",
                                                                        2,
                                                                        "creator" ),
                                                                 false ),
                                                          array( 5,
                                                                 array( array( 3,
                                                                               "name",
                                                                               false ) ),
                                                                 false ),
                                                          array( 6,
                                                                 array( "wash" ),
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

$text .= "</h1>\n\n    ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$show1 = "thumbnail";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "        <img src=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "thumbnail";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "url";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "ezroot",
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

$text .= " alt=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "persistent_data" );
$var1 = "thumbnail";
$var = compiledFetchAttribute( $var, $var1 );
$var1 = "filename";
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

$text .= "\" />\n    ";

}

$text .= "\n    <h2>";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "current_step" );
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

$text .= "</h2>\n";


?>
