<?php
// URI:       design:content/draft.tpl
// Filename:  design/standard/templates/content/draft.tpl
// Timestamp: 1062422382 (Mon Sep 1 15:19:42 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/intranet/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$text .= "<SCRIPT LANGUAGE=\"JavaScript\" type=\"text/javascript\">\n<!--\n\nfunction checkAll()\n{\n\n    if ( document.draftaction.selectall.value == \"";

$var = "Select all";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\" )\n\n    {\n\n        document.draftaction.selectall.value = \"";

$var = "Deselect all";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\";\n\n        with (document.draftaction) \n	{\n            for (var i=0; i < elements.length; i++) \n	    {\n                if (elements[i].type == 'checkbox' && elements[i].name == 'DeleteIDArray[]')\n                     elements[i].checked = true;\n	    }\n        }\n     }\n     else\n     {\n\n         document.draftaction.selectall.value = \"";

$var = "Select all";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\";\n\n         with (document.draftaction) \n	 {\n            for (var i=0; i < elements.length; i++) \n	    {\n                if (elements[i].type == 'checkbox' && elements[i].name == 'DeleteIDArray[]')\n                     elements[i].checked = false;\n	    }\n         }\n     }\n}\n\n//-->\n</SCRIPT>";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "page_limit" => array( array( 2,
                                                                                           30,
                                                                                           false ) ),
                                                             "list_count" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 1,
                                                                                                                "content",
                                                                                                                false ) ),
                                                                                                  array( array( 1,
                                                                                                                "draft_count",
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 12,
                                                                    0,
                                                                    1055 ),
                                                             array( 13,
                                                                    46,
                                                                    1119 ),
                                                             "design/standard/templates/content/draft.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "\n<form name=\"draftaction\" action=";

$tpl->processOperator( "concat",
                       array( array( array( 1,
                                            "content/draft/",
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

$text .= " method=\"post\" >\n\n<div class=\"maincontentheader\">\n<h1>";

$var = "My drafts";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</h1>\n</div>\n";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult2 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "draft_list" => array( array( 6,
                                                                                           array( "fetch",
                                                                                                  array( array( 1,
                                                                                                                "content",
                                                                                                                false ) ),
                                                                                                  array( array( 1,
                                                                                                                "draft_version_list",
                                                                                                                false ) ),
                                                                                                  array( array( 6,
                                                                                                                array( "hash",
                                                                                                                       array( array( 3,
                                                                                                                                     "limit",
                                                                                                                                     false ) ),
                                                                                                                       array( array( 4,
                                                                                                                                     array( "",
                                                                                                                                            2,
                                                                                                                                            "page_limit" ),
                                                                                                                                     false ) ),
                                                                                                                       array( array( 3,
                                                                                                                                     "offset",
                                                                                                                                     false ) ),
                                                                                                                       array( array( 4,
                                                                                                                                     array( "",
                                                                                                                                            2,
                                                                                                                                            "view_parameters" ),
                                                                                                                                     false ),
                                                                                                                              array( 5,
                                                                                                                                     array( array( 3,
                                                                                                                                                   "offset",
                                                                                                                                                   false ) ),
                                                                                                                                     false ) ) ),
                                                                                                                false ) ) ),
                                                                                           false ) ) ),
                                                      array( array( 21,
                                                                    0,
                                                                    1305 ),
                                                             array( 21,
                                                                    107,
                                                                    1412 ),
                                                             "design/standard/templates/content/draft.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "draft_list" );

if ( $show )
{

unset( $show );

$text .= "\n<div class=\"buttonblock\">\n<input type=\"submit\" name=\"EmptyButton\" value=\"";

$var = "Empty Draft";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\" />\n</div>\n\n<p>\n    ";

$var = "These are the current objects you are working on. The drafts are owned by you and can only be seen by you.\n      You can either edit the drafts or remove them if you don't need them anymore.";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
$tpl->processOperator( "nl2br",
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

$text .= "\n</p>\n\n<table class=\"list\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n    <th></th>\n    <th>";

$var = "Name";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n    <th>";

$var = "Class";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n    <th>";

$var = "Section";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n    <th>";

$var = "Version";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n    <th>";

$var = "Last modified";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n    <th>";

$var = "Edit";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</th>\n</tr>\n";

$textElements = array();
$tpl->processFunction( "section", $textElements,
                       array( array( 2,
                                    false,
                                    "<tr class=\"",
                                    array( array( 45,
                                                  66,
                                                  2374 ),
                                           array( 46,
                                                  11,
                                                  2386 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "sequence" ),
                                                  false ) ),
                                    array( array( 46,
                                                  11,
                                                  2387 ),
                                           array( 46,
                                                  26,
                                                  2402 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\">\n    <td align=\"left\" width=\"1\">\n        <input type=\"checkbox\" name=\"DeleteIDArray[]\" value=\"",
                                    array( array( 46,
                                                  26,
                                                  2403 ),
                                           array( 48,
                                                  61,
                                                  2499 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "id",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 48,
                                                  61,
                                                  2500 ),
                                           array( 48,
                                                  75,
                                                  2514 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\" />\n    </td>\n    <td>\n        <a href=",
                                    array( array( 48,
                                                  75,
                                                  2515 ),
                                           array( 51,
                                                  16,
                                                  2555 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 6,
                                                  array( "concat",
                                                         array( array( 1,
                                                                       "/content/versionview/",
                                                                       false ) ),
                                                         array( array( 4,
                                                                       array( "Draft",
                                                                              2,
                                                                              "item" ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "contentobject",
                                                                                     false ) ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "id",
                                                                                     false ) ),
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "/",
                                                                       false ) ),
                                                         array( array( 4,
                                                                       array( "Draft",
                                                                              2,
                                                                              "item" ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "version",
                                                                                     false ) ),
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "/",
                                                                       false ) ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 51,
                                                  16,
                                                  2556 ),
                                           array( 51,
                                                  110,
                                                  2650 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    ">\n            ",
                                    array( array( 51,
                                                  110,
                                                  2651 ),
                                           array( 52,
                                                  12,
                                                  2665 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "contentobject",
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
                                    array( array( 52,
                                                  12,
                                                  2666 ),
                                           array( 52,
                                                  47,
                                                  2701 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n        </a>\n    </td>\n    <td>\n        ",
                                    array( array( 52,
                                                  47,
                                                  2702 ),
                                           array( 56,
                                                  8,
                                                  2743 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "contentobject",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "content_class",
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
                                    array( array( 56,
                                                  8,
                                                  2744 ),
                                           array( 56,
                                                  57,
                                                  2793 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n    </td>\n    <td>\n        ",
                                    array( array( 56,
                                                  57,
                                                  2794 ),
                                           array( 59,
                                                  8,
                                                  2822 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "contentobject",
                                                                false ) ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "section_id",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 59,
                                                  8,
                                                  2823 ),
                                           array( 59,
                                                  44,
                                                  2859 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n    </td>\n    <td>\n        ",
                                    array( array( 59,
                                                  44,
                                                  2860 ),
                                           array( 62,
                                                  8,
                                                  2888 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "Draft",
                                                         2,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "version",
                                                                false ) ),
                                                  false ) ),
                                    array( array( 62,
                                                  8,
                                                  2889 ),
                                           array( 62,
                                                  27,
                                                  2908 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n    </td>\n    <td>\n        ",
                                    array( array( 62,
                                                  27,
                                                  2909 ),
                                           array( 65,
                                                  8,
                                                  2937 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 4,
                                                  array( "",
                                                         3,
                                                         "item" ),
                                                  false ),
                                           array( 5,
                                                  array( array( 3,
                                                                "modified",
                                                                false ) ),
                                                  false ),
                                           array( 6,
                                                  array( "l10n",
                                                         array( array( 3,
                                                                       "datetime",
                                                                       false ) ) ),
                                                  false ) ),
                                    array( array( 65,
                                                  8,
                                                  2938 ),
                                           array( 65,
                                                  38,
                                                  2968 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "\n    </td>\n    <td width=\"1\">\n        <a href=",
                                    array( array( 65,
                                                  38,
                                                  2969 ),
                                           array( 68,
                                                  16,
                                                  3015 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 6,
                                                  array( "concat",
                                                         array( array( 1,
                                                                       "/content/edit/",
                                                                       false ) ),
                                                         array( array( 4,
                                                                       array( "Draft",
                                                                              2,
                                                                              "item" ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "contentobject",
                                                                                     false ) ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "id",
                                                                                     false ) ),
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "/",
                                                                       false ) ),
                                                         array( array( 4,
                                                                       array( "Draft",
                                                                              2,
                                                                              "item" ),
                                                                       false ),
                                                                array( 5,
                                                                       array( array( 3,
                                                                                     "version",
                                                                                     false ) ),
                                                                       false ) ),
                                                         array( array( 1,
                                                                       "/",
                                                                       false ) ) ),
                                                  false ),
                                           array( 6,
                                                  array( "ezurl" ),
                                                  false ) ),
                                    array( array( 68,
                                                  16,
                                                  3016 ),
                                           array( 68,
                                                  103,
                                                  3103 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    "><img src=",
                                    array( array( 68,
                                                  103,
                                                  3104 ),
                                           array( 68,
                                                  113,
                                                  3114 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 3,
                                    false,
                                    array( array( 1,
                                                  "edit.png",
                                                  false ),
                                           array( 6,
                                                  array( "ezimage" ),
                                                  false ) ),
                                    array( array( 68,
                                                  113,
                                                  3115 ),
                                           array( 68,
                                                  131,
                                                  3133 ),
                                           "design/standard/templates/content/draft.tpl" ) ),
                             array( 2,
                                    false,
                                    " border=\"0\"></a>\n    </td>\n</tr>",
                                    array( array( 68,
                                                  131,
                                                  3134 ),
                                           array( 71,
                                                  0,
                                                  3167 ),
                                           "design/standard/templates/content/draft.tpl" ) ) ),
                       array( "name" => array( array( 3,
                                                     "Draft",
                                                     false ) ),
                             "loop" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "draft_list" ),
                                                     false ) ),
                             "sequence" => array( array( 6,
                                                         array( "array",
                                                                array( array( 3,
                                                                              "bglight",
                                                                              false ) ),
                                                                array( array( 3,
                                                                              "bgdark",
                                                                              false ) ) ),
                                                         false ) ) ),
                       array( array( 45,
                                    0,
                                    2307 ),
                             array( 45,
                                    66,
                                    2373 ),
                             "design/standard/templates/content/draft.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "<tr>\n    <td colspan=\"3\" align=\"left\">\n        <input type=\"image\" name=\"RemoveButton\" value=\"";

$var = "Remove";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\" src=";

$var = "trash.png";
$tpl->processOperator( "ezimage",
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

$text .= " />\n	<input name=\"selectall\" onclick=checkAll() type=\"button\" value=\"";

$var = "Select all";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "\">\n    </td>\n    <td colspan=\"4\">\n    </td>\n</tr>\n</table>";

$textElements = array();
$tpl->processFunction( "include", $textElements,
                       false,
                       array( "name" => array( array( 3,
                                                     "navigator",
                                                     false ) ),
                             "uri" => array( array( 1,
                                                    "design:navigator/google.tpl",
                                                    false ) ),
                             "page_uri" => array( array( 6,
                                                         array( "concat",
                                                                array( array( 1,
                                                                              "/content/draft/",
                                                                              false ) ) ),
                                                         false ) ),
                             "item_count" => array( array( 4,
                                                           array( "",
                                                                  2,
                                                                  "list_count" ),
                                                           false ) ),
                             "view_parameters" => array( array( 4,
                                                                array( "",
                                                                       2,
                                                                       "view_parameters" ),
                                                                false ) ),
                             "item_limit" => array( array( 4,
                                                           array( "",
                                                                  2,
                                                                  "page_limit" ),
                                                           false ) ) ),
                       array( array( 81,
                                    0,
                                    3526 ),
                             array( 86,
                                    31,
                                    3741 ),
                             "design/standard/templates/content/draft.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

}
else
{

unset( $show );

$text .= "\n<div class=\"feedback\">\n<h2>";

$var = "You have no drafts";
$tpl->processOperator( "i18n",
                       array( array( array( 1,
                                            "design/standard/content/view",
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

$text .= "</h2>\n</div>\n";

}

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult2 );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );


?>
