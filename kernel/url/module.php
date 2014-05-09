<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = array( 'name' => 'eZURL' );

$ViewList = array();
$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'SetValid' => 'SetValid',
                                    'SetInvalid' => 'SetInvalid' ),
    'post_action_parameters' => array( 'SetValid' => array( 'URLSelection' => 'URLSelection' ),
                                       'SetInvalid' => array( 'URLSelection' => 'URLSelection' ) ),
    'params' => array( 'ViewMode' ),
    "unordered_params" => array( "offset" => "Offset" ) );
$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'EditObject' => 'EditObject' ),
    'params' => array( 'ID' ),
    'unordered_params'=> array( 'offset' => 'Offset' ) );
$ViewList['edit'] = array(
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'Cancel' => 'Cancel',
                                    'Store' => 'Store' ),
    'params' => array( 'ID' ) );
?>
