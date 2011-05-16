<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
    'default_navigation_part' => 'ezsetupnavigationpart',
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
