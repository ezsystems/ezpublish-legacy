<?php
//
// Definition of Runcronworflows class
//
// Created on: <02-ïËÔ-2002 14:04:21 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*! \file runcronworflows.php
*/

$runInBrowser = true;
if ( isset( $webOutput ) )
    $runInBrowser = $webOutput;

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

include_once( "kernel/classes/ezworkflowprocess.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( "lib/ezutils/classes/ezoperationmemento.php" );
include_once( "lib/ezutils/classes/ezoperationhandler.php" );
include_once( "lib/ezutils/classes/ezsession.php" );

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezdebugsetting.php" );

$workflowProcessList = & eZWorkflowProcess::fetchForStatus( EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON );
//var_dump( $workflowProcessList  );
//$user =& eZUser::instance( 14 );
eZModule::setGlobalPathList( array( "kernel" ) );
if ( !$isQuiet )
    $cli->output( "Checking for workflow processes"  );
$removedProcessCount = 0;
$processCount = 0;
$statusMap = array();
foreach( array_keys( $workflowProcessList ) as $key )
{
    $process =& $workflowProcessList[ $key ];
    $workflow =& eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

    if ( $process->attribute( "event_id" ) != 0 )
        $workflowEvent =& eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );
    $process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process

    ++$processCount;
    $status = $process->attribute( 'status' );
    if ( !isset( $statusMap[$status] ) )
        $statusMap[$status] = 0;
    ++$statusMap[$status];
    if ( $process->attribute( 'status' ) != EZ_WORKFLOW_STATUS_DONE )
    {
        if ( $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_CANCELLED )
        {
            ++$removedProcessCount;
            $process->remove();
            continue;
        }
        $process->store();
        if ( $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_RESET )
        {
            $bodyMemento =& eZOperationMemento::fetchMain( $process->attribute( 'memento_key' ) );
            $mementoList =& eZOperationMemento::fetchList( $process->attribute( 'memento_key' ) );
            $bodyMemento->remove();
            for ( $i = 0; $i < count( $mementoList ); ++$i )
            {
                $memento =& $mementoList[$i];
                $memento->remove();
            }
        }
    }
    else
    {   //restore memento and run it
        $bodyMemento =& eZOperationMemento::fetchChild( $process->attribute( 'memento_key' ) );
        if ( is_null( $bodyMemento ) )
        {
            eZDebug::writeError( $bodyMemento, "Empty body memento in workflow.php" );
            continue;
        }
        $bodyMementoData = $bodyMemento->data();
        $mainMemento =& $bodyMemento->attribute( 'main_memento' );
        if ( !$mainMemento )
            continue;

        $mementoData = $bodyMemento->data();
        $mainMementoData = $mainMemento->data();
        $mementoData['main_memento'] =& $mainMemento;
        $mementoData['skip_trigger'] = true;
        $mementoData['memento_key'] = $process->attribute( 'memento_key' );
        $bodyMemento->remove();
        $operationParameters = array();
        if ( isset( $mementoData['parameters'] ) )
            $operationParameters = $mementoData['parameters'];
        $operationResult =& eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
        ++$removedProcessCount;
        $process->remove();
    }

}
if ( !$isQuiet )
{
    $cli->output( $cli->stylize( 'emphasize', "Status list" ) );
    $statusTextList = array();
    $maxStatusTextLength = 0;
    foreach ( $statusMap as $statusID => $statusCount )
    {
        $statusName = eZWorkflow::statusName( $statusID );
        $statusText = "$statusName($statusID)";
        $statusTextList[] = array( 'text' => $statusText,
                                   'count' => $statusCount );
        if ( strlen( $statusText ) > $maxStatusTextLength )
            $maxStatusTextLength = strlen( $statusText );
    }
    foreach ( $statusTextList as $item )
    {
        $text = $item['text'];
        $count = $item['count'];
        $cli->output( $cli->stylize( 'success', $text ) . ': ' . str_repeat( ' ', $maxStatusTextLength - strlen( $text ) ) . $cli->stylize( 'emphasize', $count )  );
    }
    $cli->output();
    $cli->output( $cli->stylize( 'emphasize', $removedProcessCount ) . " out of " . $cli->stylize( 'emphasize', $processCount ) . " processes was finished"  );
}

?>
