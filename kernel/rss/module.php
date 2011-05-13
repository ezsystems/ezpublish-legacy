<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
