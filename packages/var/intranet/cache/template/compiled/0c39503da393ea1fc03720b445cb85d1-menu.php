<?php
// URI:       design:parts/my/menu.tpl
// Filename:  design/admin/templates/parts/my/menu.tpl
// Timestamp: 1062422508 (Mon Sep 1 15:21:48 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/content/draft/";
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

$var = "My drafts";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/content/pendinglist/";
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

$var = "My pending list";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/notification/settings/";
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

$var = "My notification settings";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/content/bookmark/";
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

$var = "My bookmarks";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$var = "/collaboration/view/summary";
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

$var = "Collaboration";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n\n<div style=\"width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('";

$var = "bgtiledark.gif";
$tpl->processOperator( "ezimage",
                       array( array( array( 3,
                                            "no",
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

$text .= "'); background-repeat: repeat;\">\n<a class=\"leftmenuitem\" href=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "/user/password/",
                                            false ) ),
                              array( array( 4,
                                            array( "",
                                                   2,
                                                   "current_user" ),
                                            false ),
                                     array( 5,
                                            array( array( 3,
                                                          "contentobject_id",
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

$text .= ">";

$var = "Change password";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/admin/layout",
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

$text .= "</a>\n</div>\n";


?>
