<?php
//
// Created on: <28-Oct-2002 12:44:43 bf>
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

include_once( "lib/ezutils/classes/ezsys.php" );

$indexPathPrepend = eZSys::wwwDir() . eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "Tutorials";
$infoArray["description"] = '<h1>Introduction</h1>
<p>
These tutorials will help you get started with eZ publish.
</p>
';
$infoArray["partfile"] = "tutorials.php";
$infoArray["trademark"] = false;

$featureArray = array();

$featureArray[] = array( "uri" => "main_concepts",
                         "level" => 0,
                         "name" => "Main concepts" );

$featureArray[] = array( "uri" => "content_classes",
                         "level" => 0,
                         "name" => "Custom content" );

$featureArray[] = array( "uri" => "templateissues",
                         "level" => 0,
                         "name" => "Common template issues" );

$featureArray[] = array( "uri" => "workflows",
                         "level" => 0,
                         "name" => "Custom workflows" );

$featureArray[] = array( "uri" => "wrappingworkflow",
                         "level" => 0,
                         "name" => "Example of wrapping workflow" );

$featureArray[] = array( "uri" => "publishworkflow",
                         "level" => 0,
                         "name" => "Approval workflow" );

$featureArray[] = array( "uri" => "datatypes",
                         "level" => 0,
                         "name" => "Datatypes" );

$featureArray[] = array( "uri" => "permissions",
                         "level" => 0,
                         "name" => "Permissions and sections" );

$featureArray[] = array( "uri" => "forms",
                         "level" => 0,
                         "name" => "Creating forms" );

$featureArray[] = array( "uri" => "translation",
                         "level" => 0,
                         "name" => "Translation and i18n" );


$infoArray["features"] =& $featureArray;

?>
