<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "eZWorkflow" );

$ViewList = array();
$ViewList["view"] = array(
    "script" => "view.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID" ) );
$ViewList["edit"] = array(
    "script" => "edit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "GroupID", "GroupName" ) );
$ViewList["groupedit"] = array(
    "script" => "groupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowGroupID" ) );
$ViewList["down"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["up"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["workflowlist"] = array(
    "script" => "workflowlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["grouplist"] = array(
    "script" => "grouplist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["process"] = array(
    "script" => "process.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowProcessID" ) );
$ViewList["run"] = array(
    "script" => "run.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowProcessID" ) );
$ViewList["event"] = array(
    "script" => "event.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["processlist"] = array(
    "script" => "processlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'unordered_params' => array( 'offset' => 'Offset' ),
    "params" => array( ) );

?>
