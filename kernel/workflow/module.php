<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
