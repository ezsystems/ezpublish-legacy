<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

$Module = array( "name" => "eZSetup",
                 "variable_params" => true,
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
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ClearCacheButton' => 'ClearCache',
                                    'ClearAllCacheButton' => 'ClearAllCache',
                                    'ClearContentCacheButton' => 'ClearContentCache',
                                    'ClearINICacheButton' => 'ClearINICache',
                                    'ClearTemplateCacheButton' => 'ClearTemplateCache' ),
    'post_action_parameters' => array( 'ClearCache' => array( 'CacheList' => 'CacheList' ) ),
    "params" => array( ) );

$ViewList["info"] = array(
    "script" => "info.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );


$ViewList["rad"] = array(
    "script" => "rad.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );

$ViewList["datatype"] = array(
    "script" => "datatype.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride'
                                    ),
    "params" => array( ) );

$ViewList["templateoperator"] = array(
    "script" => "templateoperator.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride'
                                    ),
    "params" => array( ) );

$ViewList["templatelist"] = array(
    "script" => "templatelist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ),
    "unordered_params" => array( "offset" => "Offset" ) );

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
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'SaveButton' => 'Save',
                                    'DiscardButton' => 'Discard' ),
    "params" => array( ) );

$ViewList["templatecreate"] = array(
    "script" => "templatecreate.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride'
                                    ),
    "params" => array( ) );

$ViewList["extensions"] = array(
    "script" => "extensions.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ActivateExtensionsButton' => 'ActivateExtensions' ),
    "params" => array( ) );

$ViewList['menu'] = array(
    'script' => 'setupmenu.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( ) );

?>
