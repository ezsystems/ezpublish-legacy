<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
