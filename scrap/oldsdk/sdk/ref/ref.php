<?php
//
// Created on: <18-Apr-2002 10:04:48 amos>
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

/*!
 Reads the doxygen generated HTML file and replaces hrefs and CSS classes
 with eZ publish&trade; SDK related ones.
*/
function &referenceReadFile( $file, $base )
{
    include_once( "lib/ezutils/classes/ezsys.php" );

    // Settings for NVH setup
    $pathPrepend = eZSys::wwwDir();
    $indexPathPrepend = eZSys::indexDir();

    $fd = fopen( $file, "r" );
    $data = fread( $fd, filesize( $file ) );
    fclose( $fd );
    $pos = strpos( $data, "<hr>" );
    if ( $pos !== false )
    {
        $data = substr( $data, $pos + 4 );
    }
    $id = "[a-zA-Z0-9_]+";
    $data =& preg_replace( array( "/class=\"([^\"]+)\"/",
                                  "/<h1/",
                                  "/<caption/",
                                  "/<body/",
                                  "/src=\"(class($id)\.png)\"/",
                                  "/href=\"class($id)-members\.html(#$id)?\"/",
                                  "/href=\"class($id)\.html(#$id)?\"/",
                                  "/href=\"group__($id)\.html(#$id)?\"/",
                                  "/href=\"todo\.html(#$id)?\"/",
                                  "/href=\"($id)_8php\.html(#$id)?\"/",
                                  "/href=\"($id)_8php-source\.html(#$id)?\"/"
                                  ),
                           array( "class=\"dox_$1\"",
                                  "<h1 class=\"dox\"",
                                  "<caption class=\"dox\"",
                                  "<body class=\"dox\"",
                                  "src=\"$pathPrepend/doc/generated/html/$1\"",
                                  "href=\"$indexPathPrepend$base/ref/view/members/$1$2\"",
                                  "href=\"$indexPathPrepend$base/ref/view/class/$1$2\"",
                                  "href=\"$indexPathPrepend$base/ref/view/module/$1$2\"",
                                  "href=\"$indexPathPrepend$base/ref/view/todo$1\"",
                                  "href=\"$indexPathPrepend$base/ref/view/file/$1$2\"",
                                  "href=\"$indexPathPrepend$base/ref/view/source/$1$2\""
                                  ),
                           $data );
    return $data;
}

$content = "";

$baseURI = $Params["base_uri"];
$ReferenceType = $Params["part"];
$ReferenceName = "";
if ( isset( $Params["rest"][0] ) )
    $ReferenceName = $Params["rest"][0];

$error = false;

$Result = array();

switch ( $ReferenceType )
{
    case "modules":
    {
        $refFile = "doc/generated/html/modules.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Modules";
    } break;
    case "module":
    {
        $refFile = "doc/generated/html/group__" . $ReferenceName . ".html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Module $ReferenceName";
    } break;
    case "file":
    {
        $refFile = "doc/generated/html/" . $ReferenceName . "_8php.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "File $ReferenceName.php";
    } break;
    case "source":
    {
        $refFile = "doc/generated/html/" . $ReferenceName . "_8php-source.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Source file $ReferenceName.php";
    } break;
    case "todo":
    {
        $refFile = "doc/generated/html/todo.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Todo";
    } break;
    case "hierarchy":
    {
        $refFile = "doc/generated/html/hierarchy.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Hierarchy";
    } break;
    case "annotated":
    {
        $refFile = "doc/generated/html/annotated.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Annotated";
    } break;
    case "files":
    {
        $refFile = "doc/generated/html/files.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Files";
    } break;
    case "functions":
    {
        $refFile = "doc/generated/html/functions.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Functions";
    } break;
    case "related":
    {
        $refFile = "doc/generated/html/pages.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Related Pages";
    } break;
    case "class":
    {
        $refFile = "doc/generated/html/class" . $ReferenceName . ".html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Class $ReferenceName";
    } break;
    case "members":
    {
        $refFile = "doc/generated/html/class" . $ReferenceName . "-members.html";
        if ( file_exists( $refFile ) )
        {
            $refData =& referenceReadFile( $refFile, $baseURI );
            $content .= $refData;
        }
        else
            $error = true;
        $Result["title"] = "Members of $ReferenceName";
    } break;
    default:
    {
        $content = '<h1>Introduction</h1>
<p>The Reference Documentation for eZ publish&trade; consists of multiple sections which
each have a different view on the documentation. The sections can be accessed at
menu to the left. </p>
<p>The documentation will give an overview of the API of eZ publish&trade;. For user guides
you should visit the <a href="/sdk">SDK area</a></p>

<p class="footnote">All reference documentation has been made with <a href="http://www.doxygen.org">doxygen</a></p>

';
    } break;
}

if ( $error )
{
    $content .= "<p>Undefined API reference $ReferenceName</p>";
}

$Result["content"] = $content;
$Result["pagelayout"] = false;
$Result["external_css"] = true;

?>
