<?php
//
// Created on: <18-Sep-2002 10:05:08 kk>
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


$Module = array( 'name' => 'eZRSS' );

$ViewList = array();
$ViewList['list'] = array(
    'script' => 'list.php',
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'unordered_params' => array( 'language' => 'Language' ) );

$ViewList['edit_export'] = array(
    'script' => 'edit_export.php',
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'Update_Item_Class' => 'UpdateItem',
                                    'AddSourceButton' => 'AddItem',
                                    'RemoveButton' => 'Remove',
                                    'BrowseImageButton' => 'BrowseImage' ),
    'params' => array( 'RSSExportID', 'RSSExportItemID', 'BrowseType' ) );

$ViewList['edit_import'] = array(
    'script' => 'edit_import.php',
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'RemoveButton' => 'Remove',
                                    'Update_Class' => 'UpdateClass',
                                    'DestinationBrowse' => 'BrowseDestination',
                                    'UserBrowse' => 'BrowseUser' ),
    'params' => array( 'RSSImportID', 'BrowseType' ) );


$ViewList['feed'] = array(
    'script' => 'feed.php',
    'functions' => array( 'feed' ),
    'params' => array ( 'RSSFeed' ) );

$FunctionList['feed'] = array();

?>
