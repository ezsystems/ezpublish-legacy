<?php
// URI:       design:error/kernel/1.tpl
// Filename:  design/standard/templates/error/kernel/1.tpl
// Timestamp: 1067520225 (Thu Oct 30 14:23:45 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div class=\"warning\">\n<h2>";

$var = "Access denied";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</h2>\n<p>";

$var = "You don't have permission to access this area.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</p>\n<p>";

$var = "Possible reasons for this is.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</p>\n<ul>\n    ";

$tpl->processOperator( "ne",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "current_user" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "contentobject_id",
                                                          false ) ),
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "anonymous_user_id" ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "    <li>";

$var = "Your current user does not have the proper privileges to access this page.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</li>\n    ";

}
else
{

unset( $show );

$text .= "    <li>";

$var = "You are currently not logged in to the site, to get proper access create a new user or login with an existing user.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</li>\n    ";

}

$text .= "    <li>";

$var = "You misspelled some parts of your url, try changing it.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</li>\n</ul>\n</div>\n";

$tpl->processOperator( "eq",
                       array( array( array( 4,
                                            array( "",
                                                   2,
                                                   "current_user" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "contentobject_id",
                                                          false ) ),
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "anonymous_user_id" ),
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "\n    ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "embed_content" );

if ( $show )
{

unset( $show );

$text .= "\n    ";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "embed_content" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\n    ";

}
else
{

unset( $show );

$text .= "\n        <form method=\"post\" action=";

$var = "/user/login/";
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

$text .= ">\n\n        <p>";

$var = "Click the Login button to login.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/error/kernel",
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

$text .= "</p>\n        <div class=\"buttonblock\">\n        <input class=\"button\" type=\"submit\" name=\"LoginButton\" value=\"";

$var = "Login";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
                                            false ) ),
                              array( array( 1,
                                            "Button",
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

$text .= "\" />\n        </div>\n\n        <input type=\"hidden\" name=\"Login\" value=\"\" />\n        <input type=\"hidden\" name=\"Password\" value=\"\" />\n        <input type=\"hidden\" name=\"RedirectURI\" value=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "redirect_uri" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n        </form>\n\n    ";

}

}


?>
