<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "eZNotification",
                 "variable_params" => true );


$ViewList = array();
$ViewList["settings"] = array(
    "functions" => array( 'use' ),
    "script" => "settings.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezmynavigationpart',
    "params" => array( ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList["runfilter"] = array(
    "functions" => array( 'administrate' ),
    "script" => "runfilter.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( ) );

$ViewList["addtonotification"] = array(
    "functions" => array( 'use' ),
    "script" => "addtonotification.php",
    'ui_context' => 'administration',
    "default_navigation_part" => 'ezcontentnavigationpart',
    "params" => array( 'ContentNodeID' ) );

$FunctionList['use'] = array( );
$FunctionList['administrate'] = array( );


?>
