<?php
//
// Created on: <11-Sep-2002 16:40:04 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/eztaskmessage.php' );

$MessageID =& $Params["MessageID"];
$message =& eZTaskMessage::fetch( $MessageID );
if ( get_class( $message ) != 'eztaskmessage' )
{
    $Result =& $Module->handleError( EZ_ERROR_KERNEL_NOT_FOUND, 'kernel' );
    return;
}

if ( $message->attribute( 'is_published' ) )
{
    $Result =& $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    return;
}

if ( $Module->isCurrentAction( 'Edit' ) )
{
    return $Module->redirectToView( 'message', array( $message->attribute( 'task_id' ),
                                                      $MessageID ) );
}
else if ( $Module->isCurrentAction( 'Publish' ) )
{
    $object =& $message->attribute( 'contentobject' );
    if ( $object === null )
    {
        $Result =& $Module->handleError( EZ_ERROR_KERNEL_NOT_FOUND, 'kernel' );
        return;
    }
    $object->store();
    $message->setAttribute( 'is_published', true );
    $message->store();

    $task =& $message->attribute( 'task' );
    include_once( 'lib/ezlocale/classes/ezdatetime.php' );
    $task->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
    $task->store();

    $status = $Module->runHooks( 'post_publish', array( &$message, &$object ) );
    if ( $status )
        return $status;
    return $Module->redirectToView( 'view', array( $message->attribute( 'task_id' ) ) );
}

include_once( "kernel/common/template.php" );
$tpl = templateInit();

$tpl->setVariable( 'message', $message );
$tpl->setVariable( 'module', $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:task/messageview.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/task', 'Task message' ),
                                'url' => false ) );

?>
