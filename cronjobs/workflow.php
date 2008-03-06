<?php
//
// Definition of Runcronworflows class
//
// Created on: <02-ïËÔ-2002 14:04:21 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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
include_once( "lib/ezutils/classes/ezoperationmemento.php" );
include_once( "lib/ezutils/classes/ezoperationhandler.php" );
include_once( "lib/ezutils/classes/ezsession.php" );

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezdebugsetting.php" );

$workflowProcessList = eZWorkflowProcess::fetchForStatus( EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON );
//var_dump( $workflowProcessList  );
//$user =& eZUser::instance( 14 );

if ( !$isQuiet )
    $cli->output( "Checking for workflow processes"  );
$removedProcessCount = 0;
$processCount = 0;
$statusMap = array();
foreach( array_keys( $workflowProcessList ) as $key )
{
    $process =& $workflowProcessList[ $key ];
    $workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

    if ( $process->attribute( "event_id" ) != 0 )
        $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );
    $process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process

    ++$processCount;
    $status = $process->attribute( 'status' );
    if ( !isset( $statusMap[$status] ) )
        $statusMap[$status] = 0;
    ++$statusMap[$status];

    if ( $process->attribute( 'status' ) != EZ_WORKFLOW_STATUS_DONE )
    {
        if ( $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_RESET ||
             $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_FAILED ||
             $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_NONE ||
             $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_CANCELLED ||
             $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_BUSY
           )
        {
            $bodyMemento = eZOperationMemento::fetchMain( $process->attribute( 'memento_key' ) );
            $mementoList = eZOperationMemento::fetchList( $process->attribute( 'memento_key' ) );
            $bodyMemento->remove();
            for ( $i = 0; $i < count( $mementoList ); ++$i )
            {
                $memento =& $mementoList[$i];
                $memento->remove();
            }
        }

        if ( $process->attribute( 'status' ) == EZ_WORKFLOW_STATUS_CANCELLED )
        {
            ++$removedProcessCount;
            $process->remove();
        }
        else
        {
            $process->store();
        }
    }
    else
    {   //restore memento and run it
        $bodyMemento = eZOperationMemento::fetchChild( $process->attribute( 'memento_key' ) );
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
        $operationResult = eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
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
