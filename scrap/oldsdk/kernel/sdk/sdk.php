<?php
//
// Created on: <17-Apr-2002 15:01:32 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/version.php" );
include_once( "kernel/sdk/parser.php" );
include_once( "lib/ezutils/classes/ezsys.php" );

/*!
 Contains all the SDK elements, add a new array and edit the templates to get more elements.
*/
function sdkElements()
{
    return array( array( "dir" => "lib",
                         "variable" => "lib_items",
                         "sub_dir" => "sdk",
                         "name" => "Libraries" ),
                  array( "dir" => "sdk",
                         "variable" => "doc_items",
                         "root" => "doc",
                         "name" => "Getting Started" ),
                  array( "dir" => "sdk",
                         "variable" => "changes_items",
                         "root" => "changes",
                         "name" => "What's New" ),
                  array( "dir" => "sdk",
                         "variable" => "tutorials_items",
                         "root" => "tutorials",
                         "name" => "Tutorials" ),
                  array( "dir" => "sdk",
                         "variable" => "kernel_items",
                         "root" => "kernel",
                         "name" => "Kernel" ),
                  array( "dir" => "sdk",
                         "root" => "ref",
                         "variable" => "ref_items",
                         "name" => "API Reference" ) );
}

/*!
 Returns the SDK element structure which matched the $name.
*/

function sdkElement( $name )
{
    $elements = sdkElements();
    foreach( $elements as $element )
    {
        $path = $element["dir"] . "/$name";
        $catalog = $element["dir"] . "/catalog.php";
        if ( file_exists( $path ) )
        {
            return $element;
        }
        else if ( file_exists( $catalog ) )
        {
            return $element;
        }
    }
    return null;
}

function processList( &$module )
{
    // Settings for NVH setup
    $pathPrepend = eZSys::wwwDir();
    $indexPathPrepend = eZSys::indexDir();

    // Template for SDK list page, uses the following variables:
    // $lib_items
    // $ref_items
    $TemplateVars = array();
    $TemplateData = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>eZ publish&trade; SDK</title>
<link rel="stylesheet" type="text/css" href="%path_prepend%/design/standard/stylesheets/core.css" />
<link rel="stylesheet" type="text/css" href="%path_prepend%/design/admin/stylesheets/admin.css" />
<link rel="stylesheet" type="text/css" href="%path_prepend%/kernel/sdk/style.css" />

<link rel="home" href="%index_path_prepend%/" title="eZ publish&trade; SDK" />
<link rel="index" href="%index_path_prepend%/" />
<link rel="top"  href="%index_path_prepend%/" title="eZ publish&trade; SDK" />
<link rel="search" href="%index_path_prepend%/content/advancedsearch" title="Search" />
<link rel="shortcut icon" href="%path_prepend%/design/standard/images/favicon.ico" type="image/x-icon" />
<link rel="help" href="%index_path_prepend%/manual" />
<link rel="glossary" href="%index_path_prepend%/sdk" />

</head>

<body>



<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="560">
    <img src="%path_prepend%/design/standard/images/ezpublish_logo_blue.gif" alt="eZ publish" /></td>
    <td class="headlink" width="66">

<table width="66" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadgraygfx" width="3">
    <img src="%path_prepend%/design/standard/images/dark-left-corner.gif" alt=""/></td>
    <td class="menuheadgraytopline" width="60">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="60" height="1" /></td>
    <td class="menuheadgraygfx" width="3">
    <img src="%path_prepend%/design/standard/images/dark-right-corner.gif" alt=""/></td>
</tr>
<tr>
    <td class="menuheadgrayleftline" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="15" /></td>
    <td class="menuheadgray">
    <p class="menuheadgray">
    <a class="menuheadlink" href="/manual/">Manual</a>
    </p>
    </td>
    <td class="menuheadgrayrightline" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="15" /></td>
</tr>
</table>

</td>

    <td class="menuheadspacer" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">


<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadselectedgfx">
    <img src="%path_prepend%/design/standard/images/light-left-corner.gif" alt=""/></td>
    <td class="menuheadselectedtopline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="60" height="1" /></td>
    <td class="menuheadselectedgfx">
    <img src="%path_prepend%/design/standard/images/light-right-corner.gif" alt=""/></td>
</tr>
<tr>
    <td class="menuheadselectedleftline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="19" /></td>
    <td class="menuheadselected">
    <p class="menuheadselected">
    <a class="menuheadlink" href="/sdk/">SDK</a>
    </p>
    </td>
    <td class="menuheadselectedrightline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="19" /></td>
</tr>
</table>

    </td>
   <td class="headlogo" width="50%">
   &nbsp;</td>
</tr>
    <td colspan="11" class="menuheadtoolbar">
    &nbsp;
    </td>
</tr>
</table>


<table width="100%" cellpadding="5">
<tr>
    <td valign="top" width="15%">
<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="3">
    <p class="menuhead">eZ publish SDK</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src="%path_prepend%/design/standard/images/bullet.gif" width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="60%">
    <p class="menuitem">Version:</p>
    </td>
    <td class="menu" width="49%">
    <p class="menuitem"><nobr>%sdk_version%</nobr></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src="%path_prepend%/design/standard/images/bullet.gif" width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="60%">
    <p class="menuitem">Alias:</p>
    </td>
    <td class="menu" width="49%">
    <p class="menuitem"><nobr>%sdk_alias%</nobr></p>
    </td>
</tr>
</table>

    </td>
    <td>


<table width="100%">
<tr>
	<td valign="top"><h2>%doc_items_name%</h2>
	<ul>
	%doc_items%
	</ul>
	</td>
	<td valign="top"><h2>%tutorials_items_name%</h2>
	<ul>
	%tutorials_items%
	</ul>
	</td>
	<td valign="top"><h2>%changes_items_name%</h2>
	<ul>
	%changes_items%
	</ul>
	</td>
</tr>
<tr>
	<td valign="top"><h2>%lib_items_name%</h2>
	<ul>
	%lib_items%
	</ul>
	</td>
	<td valign="top"><h2>%kernel_items_name%</h2>
	<ul>
	%kernel_items%
	</ul>
	</td>
	<td valign="top"><h2>%ref_items_name%</h2>
	<ul>
	%ref_items%
	</ul>
    </td>
</tr>
</table>

</td>
</tr>
</table>
<br />
<center>
<a href="http://ez.no/developer">eZ publish&trade;</a> copyright &copy; 1999-2004 <a href="http://ez.no">eZ systems as</a>
</center>
<br />
</body></html>';

    // Template for SDK items, uses the following variables:
    // $baseURI
    // $indexIdentifier
    // $indexName
    // $tradeMark
    $TemplateItemVars = array();
    $TemplateItemData_a = '<li><a href="%index_path_prepend%%baseURI%/%indexRoot%%indexIdentifier%">%indexName%%tradeMark%</a></li>' . "\n";
    $TemplateItemData_b = '<li>%indexName%%tradeMark% [not documented]</li>' . "\n";

    $baseURI = $module->uri();

    $sdk_elements = sdkElements();

    foreach( $sdk_elements as $sdk_element )
    {
        $root = "";
        if ( isset( $sdk_element["root"] ) )
            $root = "/" . $sdk_element["root"];
        $catalog = $sdk_element["dir"] . "$root/catalog.php";
        if ( file_exists( $catalog ) )
        {
            unset( $indexes );
            include( $catalog );
        }
        else
        {
            $handle = opendir( $sdk_element["dir"] );
            $files = array();
            while ( ( $file = readdir( $handle ) ) !== false )
            {
                if ( $file == "." or
                     $file == ".." or
                     $file[0] == "." )
                    continue;
                if ( !is_dir( $sdk_element["dir"] . "/$file" ) )
                    continue;
                $files[] = $file;
            }
            sort( $files );
            $indexes = array();
            foreach ( $files as $file )
            {
                $index = $sdk_element["dir"] . "/" . $file . "/" . $sdk_element["sub_dir"] . "/index.php";
                $indexes[] = array( "identifier" => $file,
                                    "file" => $index,
                                    "trademark" => true,
                                    "documented" => file_exists( $index ) );
            }
        }
        $var_name = $sdk_element["variable"];
        $sdk_items = "";
        $TemplateVars[$var_name . "_name"] = $sdk_element["name"];
        $TemplateItemVars["indexRoot"] = "";
        if ( isset( $sdk_element["root"] ) )
            $TemplateItemVars["indexRoot"] = $sdk_element["root"] . "/";

        foreach ( $indexes as $index )
        {
            $indexIdentifier = "";
            if ( isset( $index["identifier"] ) )
                $indexIdentifier = $index["identifier"];
            $indexPath =& $index["file"];
            unset( $infoArray );
            if ( isset( $indexPath ) and isset( $index["documented"] ) and $index["documented"] )
            {
                include( $indexPath );
                if ( isset( $infoArray ) )
                    $indexName = $infoArray["name"];
                else
                    $indexName = $indexIdentifier;
            }
            else if ( isset( $indexPath ) )
            {
                $indexName = $indexIdentifier;
            }
            else
            {
                $indexName = $index["name"];
            }
            $tradeMark = "";
            if ( isset( $index["trademark"] ) and $index["trademark"] )
                $tradeMark = "&trade;";
            $TemplateItemVars["baseURI"] = $baseURI;
            $TemplateItemVars["indexIdentifier"] = $indexIdentifier;
            $TemplateItemVars["indexName"] = $indexName;
            $TemplateItemVars["tradeMark"] = $tradeMark;
            $TemplateItemVars["path_prepend"] = $pathPrepend;
            $TemplateItemVars["index_path_prepend"] = $indexPathPrepend;
            if ( !isset( $indexPath ) or isset( $infoArray ) )
            {
//                 $exampleName = $infoArray["name"];
//                 $TemplateItemVars["exampleName"] = $exampleName;
                $sdk_items .= simpleParse( $TemplateItemData_a, $TemplateItemVars );
            }
            else
            {
                $sdk_items .= simpleParse( $TemplateItemData_b, $TemplateItemVars );
            }
        }
        $TemplateVars[$var_name] = $sdk_items;
    }

    $TemplateVars["sdk_version"] = eZPublishSDK::version();
    $TemplateVars["sdk_alias"] = eZPublishSDK::alias();
    $TemplateVars["path_prepend"] = $pathPrepend;
    $TemplateVars["index_path_prepend"] = $indexPathPrepend;

    return simpleParse( $TemplateData, $TemplateVars );
}

/*!
 Process an SDK file and outputs HTML.
 \note This code will probably change to make it more maintainable, this also includes
       any files they include for SDK info. We do not advice to use these functions at
       it's current state.
*/
function process( &$module, $component, $command, $part, $rest )
{
    // Settings for NVH setup
    $pathPrepend = eZSys::wwwDir();
    $indexPathPrepend = eZSys::indexDir();

    $exampleURI = $module->uri();

    $sdk_element = sdkElement( $component );
    if ( $sdk_element === null )
    {
        return processList( $module );
    }

    $sub_dir = "";
    if ( isset( $sdk_element["sub_dir"] ) )
        $sub_dir = "/" . $sdk_element["sub_dir"];

    $index = $sdk_element["dir"] . "/" . $component . $sub_dir . "/index.php";

    $out = null;
    if ( file_exists( $index ) )
    {
        // Template for SDK page, uses the following variables:
        // $nameTM
        // $exampleURI
        // $baseURI_link
        // $baseURI
        // $name
        // $example_data
        $TemplateVars = array();
        $TemplateData = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>eZ publish&trade; SDK - %nameTM%</title>
</head>
<head>
<title>eZ publish&trade; SDK</title>
<link rel="stylesheet" type="text/css" href="%path_prepend%/design/standard/stylesheets/core.css" />
<link rel="stylesheet" type="text/css" href="%path_prepend%/design/admin/stylesheets/admin.css" />
<link rel="stylesheet" type="text/css" href="%path_prepend%/kernel/sdk/style.css" />
</head>

<body style="background: url(/design/standard/images/grid-background.gif);">


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="560">
    <img src="%path_prepend%/design/standard/images/ezpublish_logo_blue.gif" alt="eZ publish" /></td>
    <td class="headlink" width="66">

<table width="66" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadgraygfx" width="3">
    <img src="%path_prepend%/design/standard/images/dark-left-corner.gif" alt=""/></td>
    <td class="menuheadgraytopline" width="60">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="60" height="1" /></td>
    <td class="menuheadgraygfx" width="3">
    <img src="%path_prepend%/design/standard/images/dark-right-corner.gif" alt=""/></td>
</tr>
<tr>
    <td class="menuheadgrayleftline" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="15" /></td>
    <td class="menuheadgray">
    <p class="menuheadgray">
    <a class="menuheadlink" href="/manual/">Manual</a>
    </p>
    </td>
    <td class="menuheadgrayrightline" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="15" /></td>
</tr>
</table>

</td>

    <td class="menuheadspacer" width="3">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">


<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadselectedgfx">
    <img src="%path_prepend%/design/standard/images/light-left-corner.gif" alt=""/></td>
    <td class="menuheadselectedtopline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="60" height="1" /></td>
    <td class="menuheadselectedgfx">
    <img src="%path_prepend%/design/standard/images/light-right-corner.gif" alt=""/></td>
</tr>
<tr>
    <td class="menuheadselectedleftline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="19" /></td>
    <td class="menuheadselected">
    <p class="menuheadselected">
    <a class="menuheadlink" href="/sdk/">SDK</a>
    </p>
    </td>
    <td class="menuheadselectedrightline">
    <img src="%path_prepend%/design/standard/images/1x1.gif" alt="" width="1" height="19" /></td>
</tr>
</table>

    </td>
   <td class="headlogo" width="50%">
   &nbsp;</td>
</tr>
    <td colspan="11" class="menuheadtoolbar">
    &nbsp;
    </td>
</tr>
</table>


<table class="path" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" width="50%">
    %back_data%&nbsp;
    </td>
    <td class="pathline" align="right" width="50%">
    %forward_data%&nbsp;
    </td>
</tr>
</table>


<table width="100%" bgcolor="white" cellpadding="5" cellspacing="0" border="0">
<tr>
<td valign="top" width="15%">

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">%baseURI_link%%nameTM%</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    &nbsp;
    </td>
    <td class="menu" width="99%">
    <p class="menuitem">%features_data%</p>
    </td>
</tr>
</table>
</td>

<td valign="top" width="85%">
<table width="100%" border="0">
<tr><td colspan="2">%content_data%</td></tr>
</table>
</td>

</tr>
</table>

<br />
<center>
<a href="http://ez.no/developer">eZ publish&trade;</a> copyright &copy; 1999-2004 <a href="http://ez.no">eZ systems as</a>
</center>
<br />
</body></html>';

        unset( $infoArray );
        include( $index );
        if ( isset( $infoArray ) )
        {
            // $featureName
            // $featureURI
            // $featureLevel
            // $baseURI
            // $base
            // $component
            $TemplateFeaturesVars = array();
            $TemplateFeaturesData = '%featureLevelStart%<img src="%path_prepend%/design/standard/images/bullet.gif" width="12" height="12" alt="" border="0"/> <a href="%index_path_prepend%%base%/%component%/%command%/%featureURI%/">%featureName%</a>%featureLevelEnd%<br />';
            $TemplateFeaturesData_nolink = '<p class="groupname">%featureName%</p>';

            // $nameTM
            // $depend_items
            $TemplateDependVars = array();
            $TemplateDependData = '<h1>Dependencies</h1>
<p>
%nameTM% uses the following eZ publish libraries.
</p>
<ul>
%depend_items%
</ul>';

            // $index_path_prepend
            // $base
            // $dependURI
            // $name
            // $trademark
            $TemplateDependItemVars = array();
            $TemplateDependItemData = '<li><a href="%index_path_prepend%%base%/%dependURI%/">%name%%trademark%</a></li>';

            $TemplateBackVars = array();
            $TemplateForwardVars = array();
            $TemplateBackData = '<a class="path" href="%index_path_prepend%%back_uri%"">&lt;&lt; %back_name%</a>';
            $TemplateForwardData = '<a class="path" href="%index_path_prepend%%forward_uri%"">%forward_name% &gt;&gt;</a>';

            $showFrame = true;
            $showSummary = true;
            if ( $command == "view" or
                 $command == "source" )
                $showSummary = false;

            $baseURI = "$exampleURI/$component";
            $name =& $infoArray["name"];
            $nameTM = $name;
            if ( !isset( $infoArray["trademark"] ) or $infoArray["trademark"] )
                $nameTM .= "&trade;";
            $desc =& $infoArray["description"];

            $features =& $infoArray["features"];

            $TemplateVars["nameTM"] = $nameTM;
            $TemplateVars["exampleURI"] = $exampleURI;

            $baseURI_link =& $TemplateVars["baseURI_link"];
            $TemplateVars["baseURI"] = $baseURI;
            $TemplateVars["base"] = $exampleURI;
            $TemplateVars["component"] = $component;
            $TemplateVars["name"] = $name;

            $content_data =& $TemplateVars["content_data"];
            $features_data =& $TemplateVars["features_data"];
            $content_data = "";
            $features_data = "";

            $back_data =& $TemplateVars["back_data"];
            $forward_data =& $TemplateVars["forward_data"];
            $back_data = "";
            $forward_data = "";

            $back_name =& $TemplateBackVars["back_name"];
            $forward_name =& $TemplateForwardVars["forward_name"];
            $back_uri =& $TemplateBackVars["back_uri"];
            $forward_uri =& $TemplateForwardVars["forward_uri"];
            $back_name = "";
            $forward_name = "";
            $back_uri = "";
            $forward_uri = "";

            $baseURI_link = "";
            if ( $command != "" )
                $baseURI_link = "<a class=\"menuhead\" href=\"%index_path_prepend%$baseURI\">";

            $showFile = $part;

            $lastURI = "";
            $lastName = "";
            $partName = $part;


            if ( isset( $infoArray["doc"] ) )
            {
                $docs = $infoArray["doc"];
                $features[] = array( "name" => "API Reference" );
                foreach( $docs as $doc )
                {
                    if ( !isset( $doc["component"] ) )
                        $doc["component"] = "ref";
                    if ( !isset( $doc["part"] ) )
                        $doc["part"] = "class";
                    $features[] = $doc;
                }
            }

            if ( $showSummary )
            {
                $content_data = $desc;

                if ( isset( $infoArray["dependencies"] ) )
                {
                    $TemplateDependVars["nameTM"] = $nameTM;
                    $depend_items =& $TemplateDependVars["depend_items"];
                    $depend_items = "";

                    $TemplateDependItemVars["index_path_prepend"] = $indexPathPrepend;
                    $TemplateDependItemVars["base"] = $module->uri();

                    $depends = $infoArray["dependencies"];
                    foreach( $depends as $depend )
                    {
                        $TemplateDependItemVars["dependURI"] = $depend["uri"];
                        $TemplateDependItemVars["name"] = $depend["name"];
                        $TemplateDependItemVars["trademark"] = "";
                        if ( !isset( $depend["trademark"] ) or $depend["trademark"] )
                            $TemplateDependItemVars["trademark"] = "&trade;";
                        $depend_items .= simpleParse( $TemplateDependItemData, $TemplateDependItemVars );
                    }

                    $content_data .= simpleParse( $TemplateDependData, $TemplateDependVars );
                }
            }
            else
            {
                $exampleFile = $part;
                if ( isset( $infoArray["partfile"] ) )
                    $part_file = $infoArray["partfile"];
                else
                    $part_file = $part . ".php";
                $partPath = $sdk_element["dir"] . "/" . $component . $sub_dir . "/" . $part . ".php";
                $examplePath = $sdk_element["dir"] . "/" . $component . $sub_dir . "/" . $part_file;

                if ( file_exists( $examplePath ) )
                {
                    if ( $command == "view" )
                    {
                        $TemplateNonSummaryVars["baseURI"] = $baseURI;
                        $TemplateNonSummaryVars["exampleFile"] = $exampleFile;

                        // removed to avoid [source] link (gl)
//                         $content_data = "<table><tr><td><h1>%part_name%</h1></td>
// <td><a href=\"%index_path_prepend%$baseURI/source/$part\">[source]</a></td></tr></table>";

//                        $content_data = "<table><tr><td><h1>%part_name%</h1></td>
//<td>&nbsp;</td></tr></table>";
                        ob_start();
                        $TemplateVars["part_name"] = $partName;

                        include_once( "lib/ezutils/classes/ezprocess.php" );
                        $params = array( "base_uri" => $module->uri(),
                                         "component" => $component,
                                         "part" => $part,
                                         "rest" => $rest );
                        $ret =& eZProcess::run( $examplePath,
                                                $params );

                        if ( isset( $ret["content"] ) and $ret["content"] != "" )
                            $content_data .= $ret["content"];
                        else
                            $content_data .= ob_get_contents();
                        ob_end_clean();
                        if ( isset( $ret["navigation"] ) )
                        {
                            $com = $command;
                            if ( $com == "" )
                                $com = "view";
                            if ( isset( $ret["navigation"]["forward"] ) )
                            {
                                $forward = $ret["navigation"]["forward"];
                                $forward_uri = "$baseURI/$com/" . $forward["uri"];
                                $forward_name = $forward["name"];
                            }
                            if ( isset( $ret["navigation"]["back"] ) )
                            {
                                $back = $ret["navigation"]["back"];
                                $back_uri = "$baseURI/$com/" . $back["uri"];
                                $back_name = $back["name"];
                            }
                        }
                        if ( isset( $ret["features"] ) )
                        {
                            if ( isset( $ret["feature_placement"] ) )
                            {
                                $ret_features =& $ret["features"];
                                $i = 0;
                                $level = 0;
                                for ( ; $i < count( $features ); ++$i )
                                {
                                    $feature =& $features[$i];
                                    if ( isset( $feature["uri"] ) and
                                         $feature["uri"] == $ret["feature_placement"] )
                                    {
                                        $level = isset( $feature["level"] ) ? $feature["level"] : 0;
                                        ++$level;
                                        ++$i;
                                        break;
                                    }
                                    next( $features );
                                }
                                for ( $j = 0; $j < count( $ret_features ); ++$j )
                                {
                                    $ret_feature =& $ret_features[$j];
                                    $cur_level = 0;
                                    if ( isset( $ret_feature["level"] ) )
                                        $cur_level = $ret_feature["level"];
                                    $ret_feature["level"] = $cur_level + $level;
                                }
                                array_splice( $features, $i, 0, $ret_features );
                            }
                            else
                            {
                                $ret_features =& $ret["features"];
                                foreach( $ret_features as $ret_feature )
                                {
                                    $features[] = $ret_feature;
                                }
                            }
                        }
                        if ( isset( $ret["title"] ) )
                        {
                            $ret_title =& $ret["title"];
                            $TemplateVars["part_name"] = $ret_title;
                        }
                    }
                    else if ( $command == "source" )
                    {
                        $content_data = "<table><tr><td><h2>$partName</h2></td>
<td><a href=\"%index_path_prepend%$baseURI/view/$part\">[view]</a></td></tr></table>";
                        $sourceFile = $examplePath;
                        if ( isset( $infoArray["part_source"] ) and
                             $infoArray["part_source"] )
                            $sourceFile = $partPath;
                        $content_data .= "<p>$sourceFile</p>\n";
                        ob_start();
                        show_source( $sourceFile );
                        $content_data .= ob_get_contents();
                        ob_end_clean();
                    }
                }
                else
                {
                    $content_data = "<p>Unable to load $part</p>";
                }
            }

            foreach ( $features as $featureItem )
            {
                if ( isset( $featureItem["uri"] ) )
                {
                    $featureItemURI = $featureItem["uri"];
                    if ( isset( $featureItem["name"] ) )
                        $featureItemName = $featureItem["name"];
                    else
                        $featureItemName = $featureItem["uri"];
                    $featureItemLevel = 0;
                    if ( isset( $featureItem["level"] ) )
                        $featureItemLevel = $featureItem["level"];

                    if ( $part != "" and $featureItemURI == $part )
                    {
                        $partName = $featureItemName;
                        $com = $command;
                        if ( $com == "" )
                            $com = "view";
                        $back_uri = "$baseURI/$com/$lastURI";
                        if ( $lastName != "" )
                            $back_name = $lastName;
                        else
                        {
                            $back_uri = "$baseURI/$lastURI";
                            $back_name = $nameTM;
                        }
                    }
                    else if ( $lastURI == $part and !isset( $featureItem["part"] ) )
                    {
                        $com = $command;
                        if ( $com == "" )
                            $com = "view";
                        $forward_uri = "$baseURI/$com/$featureItemURI";
                        $forward_name = $featureItemName;
                    }

                    $TemplateFeaturesVars["featureName"] = $featureItemName;
                    if ( isset( $featureItem["part"] ) )
                        $TemplateFeaturesVars["featureURI"] = $featureItem["part"] . "/$featureItemURI";
                    else
                        $TemplateFeaturesVars["featureURI"] = $featureItemURI;
                    $TemplateFeaturesVars["base"] = $exampleURI;
                    $TemplateFeaturesVars["component"] = $component;
                    if ( isset( $featureItem["component"] ) )
                        $TemplateFeaturesVars["component"] = $featureItem["component"];
                    $TemplateFeaturesVars["command"] = ( $command == "" ? "view" : $command );
                    $TemplateFeaturesVars["featureLevelStart"] = str_repeat( "&nbsp;", $featureItemLevel );
                    $TemplateFeaturesVars["featureLevelEnd"] = str_repeat( "", $featureItemLevel );
                    $TemplateFeaturesVars["baseURI"] = $baseURI;

                    $features_data .= simpleParse( $TemplateFeaturesData, $TemplateFeaturesVars );

                    $lastURI = $featureItemURI;
                    $lastName = $featureItemName;
                }
                else
                {
                    $featureItemName = $featureItem["name"];
                    $featureItemLevel = 0;
                    if ( isset( $featureItem["level"] ) )
                        $featureItemLevel = $featureItem["level"];

                    $TemplateFeaturesVars["featureName"] = $featureItemName;
                    $TemplateFeaturesVars["featureLevelStart"] = str_repeat( "&nbsp;", $featureItemLevel );
                    $TemplateFeaturesVars["featureLevelEnd"] = str_repeat( "", $featureItemLevel );
                    $TemplateFeaturesVars["baseURI"] = $baseURI;

                    $features_data .= simpleParse( $TemplateFeaturesData_nolink, $TemplateFeaturesVars );
                }
            }

            if ( $back_uri != "" )
            {
                $back_data .= simpleParse( $TemplateBackData, $TemplateBackVars );
            }
            if ( $forward_uri != "" )
            {
                $forward_data .= simpleParse( $TemplateForwardData, $TemplateForwardVars );
            }
        }
        $TemplateVars["sdk_version"] = eZPublishSDK::version();
        $TemplateVars["path_prepend"] = $pathPrepend;
        $TemplateVars["index_path_prepend"] = $indexPathPrepend;

        $out = simpleParse( $TemplateData, $TemplateVars );
    }
    else
        eZDebug::writeWarning( "Undefined SDK component: $component ($index)" );
    return $out;
}

$contents = "";
if ( $Component == "" )
{
    $contents = processList( $Module );
}
// else if ( isset( $Parameters["Result"] ) )
// {
//     $result = processReference( $Module, $Parameters, $Parameters["ReferencePage"] );
//     if ( $result !== null )
//         $contents = $result;
// }
else
{
    $result = process( $Module, $Component, $Command, $Part,
                       array_slice( $Parameters, 3 ) );
    if ( $result !== null )
        $contents = $result;
}

$Result = array();
$Result["content"] = $contents;
$Result["pagelayout"] = false;
$Result["external_css"] = true;

?>
