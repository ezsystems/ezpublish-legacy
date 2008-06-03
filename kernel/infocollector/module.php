<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
