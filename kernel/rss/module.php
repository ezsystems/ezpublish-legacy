<?php
//
// Created on: <18-Sep-2002 10:05:08 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'Update_Item_Class' => 'UpdateItem',
                                    'AddSourceButton' => 'AddItem',
                                    'RemoveButton' => 'Cancel',
                                    'BrowseImageButton' => 'BrowseImage',
                                    'RemoveImageButton' => 'RemoveImage' ),
    'params' => array( 'RSSExportID', 'RSSExportItemID', 'BrowseType' ) );

$ViewList['edit_import'] = array(
    'script' => 'edit_import.php',
    'functions' => array( 'edit' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'RemoveButton' => 'Cancel',
                                    'AnalyzeFeedButton' => 'AnalyzeFeed',
                                    'Update_Class' => 'UpdateClass',
                                    'DestinationBrowse' => 'BrowseDestination',
                                    'UserBrowse' => 'BrowseUser' ),
    'params' => array( 'RSSImportID', 'BrowseType' ) );


$ViewList['feed'] = array(
    'script' => 'feed.php',
    'functions' => array( 'feed' ),
    'params' => array ( 'RSSFeed' ) );


$FunctionList = array( );
$FunctionList['feed'] = array();
$FunctionList['edit'] = array();

?>
