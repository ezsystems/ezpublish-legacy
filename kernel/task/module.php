<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Module = array( 'name' => 'eZTask' );

$ViewList = array();
$ViewList['edit'] = array(
    'script' => 'edit.php',
    'params' => array( 'TaskID' ) );
$ViewList['view'] = array(
    'script' => 'view.php',
    'single_post_actions' => array( 'CancelTaskButton' => 'CancelTask',
                                    'CloseTaskButton' => 'CloseTask',
                                    'NewTaskButton' => 'NewTask',
                                    'NewAssignmentButton' => 'NewAssignment',
                                    'NewMessageButton' => 'NewMessage' ),
    'post_action_parameters' => array( 'CancelTask' => array( 'SelectedIDList' => 'Task_id_checked' ),
                                       'CloseTask' => array( 'SelectedIDList' => 'Task_id_checked' ),
                                       'NewMessage' => array( 'SelectedIDList' => 'Task_id_checked',
                                                              'ClassID' => 'ClassID' ) ),
    'params' => array( 'TaskID' ) );
$ViewList['message'] = array(
    'script' => 'message.php',
    'params' => array( 'TaskID', 'MessageID' ),
    'single_post_actions' => array( 'PreviewButton' => 'Preview',
                                    'ApplyButton' => 'Apply',
                                    'PublishButton' => 'Publish',
                                    'CancelButton' => 'Cancel' ) );
?>
