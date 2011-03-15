<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "Settings management",
                 "variable_params" => true );

$ViewList = array();
$ViewList["view"] = array(
    "script" => "view.php",
    "default_navigation_part" => "ezsetupnavigationpart",
    "params" => array( 'SiteAccess' , 'INIFile' ) );
$ViewList["edit"] = array(
    "script" => "edit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => "ezsetupnavigationpart",
    "params" => array( 'SiteAccess', 'INIFile', 'Block', 'Setting', 'Placement' ) );

?>
