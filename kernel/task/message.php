<?php
//
// Created on: <11-Sep-2002 16:40:04 amos>
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

include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];
$TaskID =& $Params["TaskID"];

include_once( 'kernel/classes/eztask.php' );
$task = eZTask::fetch( $TaskID );
if ( !is_object( $task ) )
    return $Module->redirectToView( 'view' );

include_once( 'kernel/classes/eztaskmessage.php' );

$MessageID =& $Params["MessageID"];
$message =& eZTaskMessage::fetch( $MessageID );
if ( get_class( $message ) != 'eztaskmessage' or
     $message->attribute( 'task_id' ) != $TaskID )
{
    include_once( 'kernel/classes/ezcontentobject.php' );
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

    $classID = $Params['ClassID'];
    $contentObject =& eZContentObject::createNew( $classID, 2 );

    $currentUser =& eZUser::currentUser();
    $currentUserID =& $currentUser->attribute( 'contentobject_id' );

    $creatorType = EZ_TASK_MESSAGE_CREATOR_SENDER;
    if ( $currentUserID == $task->attribute( 'receiver_id' ) )
        $creatorType = EZ_TASK_MESSAGE_CREATOR_RECEIVER;

    $message =& eZTaskMessage::create( $TaskID, $creatorType, $currentUserID, $contentObject->attribute( 'id' ) );
    $message->store();
    return $Module->redirectToView( '', array( $TaskID, $message->attribute( 'id' ) ) );
}

$Params['ObjectID'] = $message->attribute( 'contentobject_id' );

function messageInitializeTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, &$tpl )
{
}

// $Module->addHook( 'pre_module_init', 'messageInitializeTemplate' );

function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion )
{
//     if ( $module->isCurrentAction( 'Preview' ) )
//     {
//         $module->redirectToView( 'versionview', array( $ObjectID, $EditVersion ) );
//         return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
//     }

    if ( $module->isCurrentAction( 'Apply' ) )
    {
//         $module->redirectToView( 'versionview', array( $ObjectID, $EditVersion ) );
//         return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }

//     if ( $module->isCurrentAction( 'Publish' ) )
//     {
//         $object->setAttribute( 'current_version', $EditVersion );
//         $object->store();

//         $status = $module->runHooks( 'post_publish', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) );
//         if ( $status )
//             return $status;

// //         eZDebug::writeNotice( $object, 'object' );
//         $module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );
//         return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
//     }
}

$Module->addHook( 'action_check', 'checkContentActions' );

$Params['TemplateName'] = "design:task/message.tpl";
$EditVersion = 1;

include_once( "kernel/common/template.php" );
$tpl = templateInit();

$tpl->setVariable( 'task_id', $TaskID );
$tpl->setVariable( 'task', $task );
$tpl->setVariable( 'message', $message );
$tpl->setVariable( 'module', $Module );
$Params['TemplateObject'] =& $tpl;

include( 'kernel/content/attribute_edit.php' );

// $http =& eZHTTPTool::instance();

// $Result =& $tpl->fetch( "design:task/message.tpl" );


?>
