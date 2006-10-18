<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

$Module = array( "name" => "eZSetup",
                 "variable_params" => true,
                 'ui_component_match' => 'view',
                 "function" => array(
                     "script" => "setup.php",
                     "params" => array( ) ) );

$ViewList = array();
$ViewList["init"] = array(
    "script" => "ezsetup.php",
    'single_post_actions' => array( 'ChangeStepAction' => 'ChangeStep' ),
    'post_value_action_parameters' => array( 'ChangeStep' => array( 'Step' => 'StepButton' ) ),
    "params" => array() );

$ViewList["cache"] = array(
    "script" => "cache.php",
    'functions' => array( 'managecache' ),
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ClearCacheButton' => 'ClearCache',
                                    'ClearAllCacheButton' => 'ClearAllCache',
                                    'ClearContentCacheButton' => 'ClearContentCache',
                                    'ClearINICacheButton' => 'ClearINICache',
                                    'ClearTemplateCacheButton' => 'ClearTemplateCache',
                                    'RegenerateStaticCacheButton' => 'RegenerateStaticCache' ),
    'post_action_parameters' => array( 'ClearCache' => array( 'CacheList' => 'CacheList' ) ),
    "params" => array() );

$ViewList['cachetoolbar'] = array(
    'script' => 'cachetoolbar.php',
    'functions' => array( 'managecache' ),
    'single_post_actions' => array( 'ClearCacheButton' => 'ClearCache' ),
    'post_action_parameters' => array( 'ClearCache' => array( 'CacheType' => 'CacheTypeValue',
                                                              'NodeID' => 'NodeID',
                                                              'ObjectID' => 'ObjectID' ) ),
    'params' => array() );

$ViewList['settingstoolbar'] = array(
    'script' => 'settingstoolbar.php',
    'single_post_actions' => array( 'SetButton' => 'Set' ),
    'post_action_parameters' => array( 'Set' => array( 'SiteAccess' => 'SiteAccess',
                                                       'AllSettingsList' => 'AllSettingsList',
                                                       'SelectedList' => 'SelectedList' ) ),
    'params' => array() );

$ViewList['session'] = array(
    'script'                  => 'session.php',
    'ui_context'              => 'administration',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions'     => array( 'RemoveAllSessionsButton' => 'RemoveAllSessions',
                                        'ShowAllUsersButton' => 'ShowAllUsers',
                                        'ChangeFilterButton' => 'ChangeFilter',
                                        'RemoveTimedOutSessionsButton' => 'RemoveTimedOutSessions',
                                        'RemoveSelectedSessionsButton' => 'RemoveSelectedSessions' ),
    'post_action_parameters' => array( 'ChangeFilter' => array( 'FilterType' => 'FilterType',
                                                                'ExpirationFilterType' => 'ExpirationFilterType',
                                                                'InactiveUsersCheck' => 'InactiveUsersCheck',
                                                                'InactiveUsersCheckExists' => 'InactiveUsersCheckExists' ) ),
    'params' => array( 'UserID' ) );

$ViewList["info"] = array(
    "script" => "info.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );

$ViewList["rad"] = array(
    "script" => "rad.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );

$ViewList["datatype"] = array(
    "script" => "datatype.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride'
                                    ),
    "params" => array( ) );

$ViewList["templateoperator"] = array(
    "script" => "templateoperator.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride'
                                    ),
    "params" => array( ) );

$ViewList["extensions"] = array(
    "script" => "extensions.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ActivateExtensionsButton' => 'ActivateExtensions' ),
    "params" => array( ) );

$ViewList['menu'] = array(
    'script' => 'setupmenu.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( ) );

$ViewList['systemupgrade'] = array(
    'script' => 'systemupgrade.php',
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'MD5CheckButton' => 'MD5Check',
                                    'DBCheckButton' => 'DBCheck' ),
    'params' => array( ) );


/*! Provided for backwards compatability */
$ViewList["toolbarlist"] = array(
    "script" => "toolbarlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( 'SiteAccess' ) );

$ViewList["toolbar"] = array(
    "script" => "toolbar.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    "params" => array( 'SiteAccess', 'Position' ) );

$ViewList["menuconfig"] = array(
    "script" => "menuconfig.php",
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess' ),
    "params" => array() );

$ViewList["templatelist"] = array(
    'script' => 'templatelist.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList["templateview"] = array(
    "script" => "templateview.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess',
                                    'RemoveOverrideButton' => 'RemoveOverride',
                                    'UpdateOverrideButton' => 'UpdateOverride',
                                    'NewOverrideButton' => 'NewOverride' ),
    "params" => array( ) );

$ViewList["templateedit"] = array(
    "script" => "templateedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'SaveButton' => 'Save',
                                    'DiscardButton' => 'Discard' ),
    "params" => array( ) );

$ViewList["templatecreate"] = array(
    "script" => "templatecreate.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride',
                                    'CancelOverrideButton' => 'CancelOverride' ),
    "params" => array( ) );



$FunctionList['managecache'] = array();

?>
