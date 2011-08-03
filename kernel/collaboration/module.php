<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( 'name' => 'eZCollaboration' );

$ViewList = array();
$ViewList['action'] = array(
    'script' => 'action.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'default_action' => array( array( 'name' => 'Custom',
                                      'type' => 'post',
                                      'parameters' => array( 'CollaborationActionCustom',
                                                             'CollaborationTypeIdentifier',
                                                             'CollaborationItemID' ) ) ),
    'post_action_parameters' => array( 'Custom' => array( 'TypeIdentifer' => 'CollaborationTypeIdentifier',
                                                          'ItemID' => 'CollaborationItemID' ) ),
    'params' => array() );
$ViewList['view'] = array(
    'script' => 'view.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['item'] = array(
    'script' => 'item.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'ItemID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['group'] = array(
    'script' => 'group.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'GroupID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );

?>
