<?php
// URI:       design:user/login.tpl
// Filename:  design/admin/templates/user/login.tpl
// Timestamp: 1062422509 (Mon Sep 1 15:21:49 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/news/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<form method=\"post\" action=";

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

$text .= ">\n\n<div class=\"maincontentheader\">\n   <h2>";

$var = "Welcome to eZ publish administration";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
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

$text .= "</h2>\n</div>\n\n    <p>";

$var = "To log in enter a valid login and password.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/layout",
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

$text .= "</p>\n";

$namespace = $rootNamespace;
if ( $namespace == '' )
    $namespace = "User";
else
    $namespace .= ':User';
$show = compiledFetchVariable( $vars, $namespace, "warning" );
$show1 = "bad_login";
$show = compiledFetchAttribute( $show, $show1 );

if ( $show )
{

unset( $show );

$text .= "<div class=\"warning\">\n<h2>";

$var = "Could not login";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
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

$text .= "</h2>\n<ul>\n    <li>";

$var = "A valid username and password is required to login.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
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

$text .= "</li>\n</ul>\n</div>";

}
else
{

unset( $show );

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "site_access" );
$show1 = "allowed";
$show = compiledFetchAttribute( $show, $show1 );
$tpl->processOperator( "not",
                       array(),
                       $rootNamespace, $currentNamespace, $show, false, false );

if ( $show )
{

unset( $show );

$text .= "<div class=\"warning\">\n<h2>";

$var = "Access not allowed";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
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

$text .= "</h2>\n<ul>\n    <li>";

$var = "You are not allowed to access %1.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
                                            false ) ),
                              null,
                              array( array( 6,
                                            array( "array",
                                                   array( array( 4,
                                                                 array( "",
                                                                        2,
                                                                        "site_access" ),
                                                                 false ),
                                                          array( 5,
                                                                 array( array( 3,
                                                                               "name",
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

$text .= "</li>\n</ul>\n</div>";

}

}

$text .= "\n<div class=\"block\">\n<label for=\"id1\">";

$var = "Login";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
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

$text .= "</label><div class=\"labelbreak\"></div>\n<input class=\"halfbox\" type=\"text\" size=\"10\" name=\"Login\" id=\"id1\" value=\"";

$namespace = $rootNamespace;
if ( $namespace == '' )
    $namespace = "User";
else
    $namespace .= ':User';
$var = compiledFetchVariable( $vars, $namespace, "login" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />\n</div>\n<div class=\"block\">\n<label for=\"id2\">";

$var = "Password";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/user",
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

$text .= "</label><div class=\"labelbreak\"></div>\n<input class=\"halfbox\" type=\"password\" size=\"10\" name=\"Password\" id=\"id2\" value=\"\" />\n</div>\n\n<div class=\"buttonblock\">\n<input class=\"defaultbutton\" type=\"submit\" name=\"LoginButton\" value=\"";

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

$text .= "\" />\n<input class=\"button\" type=\"submit\" name=\"RegisterButton\" value=\"";

$var = "Sign Up";
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

$text .= "\" />\n</div>\n\n<input type=\"hidden\" name=\"RedirectURI\" value=\"";

$namespace = $rootNamespace;
if ( $namespace == '' )
    $namespace = "User";
else
    $namespace .= ':User';
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

$text .= "\" />\n\n</form>\n";


?>
