<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

$Module = array( "name" => "eZVisual",
                 "variable_params" => true,
                 'ui_component_match' => 'view' );

$ViewList = array();
$ViewList["toolbarlist"] = array(
    "script" => "toolbarlist.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    "params" => array( 'SiteAccess' ) );

$ViewList["toolbar"] = array(
    "script" => "toolbar.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezvisualnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'single_post_actions' => array( 'BackToToolbarsButton' => 'BackToToolbars',
                                    'NewToolButton' => 'NewTool',
                                    'UpdatePlacementButton' => 'UpdatePlacement',
                                    'BrowseButton' => 'Browse',
                                    'RemoveButton' => 'Remove',
                                    'StoreButton' => 'Store' ),
    "params" => array( 'SiteAccess', 'Position' ) );

$ViewList["menuconfig"] = array(
    "script" => "menuconfig.php",
    'default_navigation_part' => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess' ),
    "params" => array() );

$ViewList["templatelist"] = array(
    "script" => "templatelist.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    "params" => array( ),
    "unordered_params" => array( "offset" => "Offset" ) );

$ViewList["templateview"] = array(
    "script" => "templateview.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess',
                                    'RemoveOverrideButton' => 'RemoveOverride',
                                    'UpdateOverrideButton' => 'UpdateOverride',
                                    'NewOverrideButton' => 'NewOverride' ),
    "params" => array( ) );

$ViewList['templateedit'] = array(
    'script' => 'templateedit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'SaveButton' => 'Save',
                                    'DiscardButton' => 'Discard' ),
    'params' => array( ),
    'unordered_params' => array( 'siteAccess' => 'SiteAccess' ) );

$ViewList['templatecreate'] = array(
    'script' => 'templatecreate.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride',
                                    'CancelOverrideButton' => 'CancelOverride' ),
    'params' => array( ),
    'unordered_params' => array( 'siteAccess' => 'SiteAccess',
                                 'classID' => 'ClassID',
                                 'nodeID' => 'NodeID' ) );

?>
