<?php
//
// Created on: <03-Jun-2002 12:50:05 bf>
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

$indexPathPrepend = eZSys::wwwDir() .  eZSys::indexFile();

$infoArray = array();
$infoArray["name"] = "SDK documents";
$infoArray["description"] = '<h1>Introduction</h1>
<p>
The SDK documents are a collection of documentation on the various parts of the SDK.
</p>
';
$infoArray["partfile"] = "doc.php";
$infoArray["trademark"] = false;
$infoArray["part_source"] = true;

$featureArray = array();

$featureArray[] = array( "level" => 0,
                         "name" => "Start here" );
$featureArray[] = array( "uri" => "introduction",
                         "level" => 1,
                         "name" => "Introduction" );
$featureArray[] = array( "uri" => "dir_layout",
                         "level" => 1,
                         "name" => "Directory Layout" );



$featureArray[] = array( "level" => 1,
                         "name" => "Diagrams" );
$featureArray[] = array( "uri" => "overview",
                         "level" => 1,
                         "name" => "Overview" );
$featureArray[] = array( "uri" => "database_diagram",
                         "level" => 1,
                         "name" => "Database" );
$featureArray[] = array( "uri" => "contentclass_database_diagram",
                         "level" => 2,
                         "name" => "Content Class" );
$featureArray[] = array( "uri" => "class_diagram",
                         "level" => 1,
                         "name" => "Kernel classes" );



$featureArray[] = array( "level" => 0,
                         "name" => "Coding standards" );
$featureArray[] = array( "uri" => "php_coding_standard",
                         "level" => 1,
                         "name" => "PHP" );
$featureArray[] = array( "uri" => "sql_coding_standard",
                         "level" => 1,
                         "name" => "SQL" );
$featureArray[] = array( "uri" => "template_coding_standard",
                         "level" => 1,
                         "name" => "Template" );
$featureArray[] = array( "uri" => "xhtml_coding_standard",
                         "level" => 1,
                         "name" => "XHTML" );
$featureArray[] = array( "uri" => "security_standard",
                         "level" => 1,
                         "name" => "Security" );
$featureArray[] = array( "uri" => "security_handling",
                         "level" => 1,
                         "name" => "Security handling" );
// $featureArray[] = array( "uri" => "uri_coding_standard",
//                          "level" => 1,
//                          "name" => "URI" );
$featureArray[] = array( "uri" => "subversion_standard",
                         "level" => 1,
                         "name" => "Subversion" );



// $featureArray[] = array(
//                          "level" => 1,
//                          "name" => "eZ publish libraries" );
// $featureArray[] = array( "uri" => "ezdb",
//                          "level" => 1,
//                          "name" => "eZ db" );
// $featureArray[] = array( "uri" => "eztemplate",
//                          "level" => 1,
//                          "name" => "eZ template" );
// $featureArray[] = array( "uri" => "ezxml",
//                          "level" => 1,
//                          "name" => "eZ xml" );
// $featureArray[] = array( "uri" => "ezsoap",
//                          "level" => 1,
//                          "name" => "eZ soap" );



// $featureArray[] = array( "level" => 0,
//                          "name" => "eZ publish kernel" );

// $featureArray[] = array( "level" => 1,
//                          "name" => "Content" );
// $featureArray[] = array( "uri" => "tree_concept",
//                          "level" => 2,
//                          "name" => "Tree concept" );
// $featureArray[] = array( "uri" => "xml_handling",
//                          "level" => 2,
//                          "name" => "XML handling" );
// $featureArray[] = array( "uri" => "content_classes",
//                          "level" => 2,
//                          "name" => "Content classes" );
// $featureArray[] = array( "uri" => "information_collector",
//                          "level" => 2,
//                          "name" => "Information collector" );
// $featureArray[] = array( "uri" => "content_objects",
//                          "level" => 2,
//                          "name" => "Content objects" );
// $featureArray[] = array( "uri" => "site_access",
//                          "level" => 2,
//                          "name" => "Site access" );
// $featureArray[] = array( "uri" => "search",
//                          "level" => 2,
//                          "name" => "Search" );
// $featureArray[] = array( "uri" => "permissions",
//                          "level" => 2,
//                          "name" => "Permissions" );
// $featureArray[] = array( "uri" => "workflows",
//                          "level" => 2,
//                          "name" => "Workflows" );
// $featureArray[] = array( "uri" => "notification_system",
//                          "level" => 2,
//                          "name" => "Notification system" );

// $featureArray[] = array( "level" => 1,
//                          "name" => "Shopping" );
// $featureArray[] = array( "uri" => "shopping_intro",
//                          "level" => 2,
//                          "name" => "Intro" );
// $featureArray[] = array( "uri" => "shopping_products",
//                          "level" => 2,
//                          "name" => "Products" );
// $featureArray[] = array( "uri" => "shopping_basket",
//                          "level" => 2,
//                          "name" => "Basket" );
// $featureArray[] = array( "uri" => "shopping_wish_list",
//                          "level" => 2,
//                          "name" => "Wish list" );
// $featureArray[] = array( "uri" => "shopping_checkout",
//                          "level" => 2,
//                          "name" => "Checkout" );
// $featureArray[] = array( "uri" => "shopping_orders",
//                          "level" => 2,
//                          "name" => "Orders" );
// $featureArray[] = array( "uri" => "shopping_calculations",
//                          "level" => 2,
//                          "name" => "Calculations" );
// $featureArray[] = array( "uri" => "shopping_admin",
//                          "level" => 2,
//                          "name" => "Admin" );

$infoArray["features"] =& $featureArray;

?>
