<?php
//
// Definition of Runcronworflows class
//
// Created on: <02-ïËÔ-2002 14:04:21 sp>
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

/*! \file runcronworflows.php
*/

include_once( "kernel/classes/ezworkflowprocess.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezmodulerun.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( "lib/ezutils/classes/ezoperationmemento.php" );
include_once( "lib/ezutils/classes/ezoperationhandler.php" );
include_once( "lib/ezutils/classes/ezsession.php" );

include_once( "lib/ezutils/classes/ezdebug.php" );

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

$workflowProcessList = & eZWorkflowProcess::fetchForStatus( EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON );
//var_dump( $workflowProcessList  );
$user =& eZUser::instance( 14 );
eZModule::setGlobalPathList( array( "kernel" ) );
foreach( array_keys( $workflowProcessList ) as $key )
{
    $process =& $workflowProcessList[ $key ];
    $workflow =& eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

    if ( $process->attribute( "event_id" ) != 0 )
        $workflowEvent =& eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );
    $process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process

    if ( $process->attribute( 'status' ) != EZ_WORKFLOW_STATUS_DONE )
    {
        $process->store();
    }
    else
    {   //restore memento and run it
        eZDebug::writeDebug( $process, '$process' );
        $bodyMemento =& eZOperationMemento::fetch( $process->attribute( 'memento_key' ) );
        if ( is_null( $bodyMemento ) )
        {
            eZDebug::printReport();
        }
        eZDebug::writeDebug( $bodyMemento->data(), '$bodyMementoData' );
        $mainMemento =& $bodyMemento->attribute( 'main_memento' );
        eZDebug::writeDebug( $mainMemento->data(), '$mainMementoData' );

        $mementoData = $bodyMemento->data();
        $mainMementoData = $mainMemento->data();
        $mementoData['main_memento'] =& $mainMemento;
        $mementoData['skip_trigger'] = true;
        $mementoData['memento_key'] = $process->attribute( 'memento_key' );
        $bodyMemento->remove();
        eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], array( ), $mementoData );
        $process->remove();
    }

    print( "\n" . $process->attribute( 'status' ) . " workflow process status\n"  );
    flush();
}

//eZDebug::printReport(false,false);
eZDebug::printReport( );




?>
