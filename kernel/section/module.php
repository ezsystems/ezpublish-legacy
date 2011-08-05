<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( 'name' => 'eZSection' );

$ViewList = array();
$ViewList['list'] = array(
    'functions' => array( 'view or edit or assign' ),
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    'params' => array( ) );

$ViewList['view'] = array(
    'functions' => array( 'view or assign' ),
    'script' => 'view.php',
    'ui_context' => 'view',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'SectionID' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['edit'] = array(
    'functions' => array( 'edit' ),
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'SectionID' ) );

$ViewList['assign'] = array(
    'functions' => array( 'assign' ),
    'script' => 'assign.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'SectionID' ),
    'functions' => array( 'assign' ) );



$ClassID = array(
    'name'=> 'Class',
    'values'=> array(),
    'class' => 'eZContentClass',
    'function' => 'fetchList',
    'parameter' => array( 0, false, false, array( 'name' => 'asc' ) )
    );

$NewSectionID = array(
    'name'=> 'NewSection',
    'values'=> array(),
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$SectionID = array(
    'name'=> 'Section',
    'values'=> array(),
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$Assigned = array(
    'name'=> 'Owner',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );

$FunctionList = array();
$FunctionList['assign'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned,
                                 'NewSection' => $NewSectionID );
$FunctionList['edit'] = array();
$FunctionList['view'] = array();

?>
