<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = array( 'name' => 'eZInfoCollector' );

$ViewList = array();
$ViewList['overview'] = array(
    'script' => 'overview.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'RemoveObjectCollectionButton' => 'RemoveObjectCollection',
                                    'ConfirmRemoveButton' => 'ConfirmRemoval',
                                    'CancelRemoveButton' => 'CancelRemoval' ) );

$ViewList['collectionlist'] = array(
    'script' => 'collectionlist.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'params' => array( 'ObjectID' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'RemoveCollectionsButton' => 'RemoveCollections',
                                    'ConfirmRemoveButton' => 'ConfirmRemoval',
                                    'CancelRemoveButton' => 'CancelRemoval' ) );

$ViewList['view'] = array(
    'script' => 'view.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'params' => array( 'CollectionID' ) );


$FunctionList = array();
$FunctionList['read'] = array();

?>
