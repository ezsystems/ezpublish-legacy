<?php
//
// Definition of eZTrigger class
//
// Created on: <11-аущ-2002 13:11:15 sp>
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

/*! \file eztrigger.php
*/

/*!
  \class eZTrigger eztrigger.php
  \brief The class eZTrigger does

*/
include_once( 'kernel/classes/ezworkflowprocess.php' );
include_once( 'kernel/classes/ezworkflow.php' );
include_once( 'kernel/classes/ezmodulerun.php' );
define( "EZ_TRIGGER_STATUS_CRON_JOB", 0 );
define( "EZ_TRIGGER_WORKFLOW_DONE", 1 );
define( "EZ_TRIGGER_WORKFLOW_CANCELED", 2 );

define( "EZ_TRIGGER_NO_CONNECTED_WORKFLOWS", 3 );


class eZTrigger extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTrigger( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( 'id' => 'ID',
                                         'module_name' => 'ModuleName',
                                         'function_name' => 'FunctionName',
                                         'connect_type' => 'ConnectType',
                                         'workflow_id' => 'WorkflowID'
                                          ),
                      "class_name" => "eZTrigger",
                      "name" => "eztrigger" );
    }


    function & attribute( $attr )
    {

        return eZPersistentObject::attribute( $attr );
    }
    function & fetch( $triggerID )
    {
        return eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                null,
                                                array( 'id' => $triggerID ),
                                                true);
    }
    function & fetchList( $parameters = array() )
    {
        $filterArray = array();
        if ( array_key_exists('module', $parameters ) && $parameters[ 'module' ] != '*' )
        {
            $filterArray['module_name'] = $parameters['module'];
        }
        if ( array_key_exists('function', $parameters ) && $parameters[ 'function' ] != '*' )
        {
            $filterArray['function_name'] = $parameters['function'];
        }
        if ( array_key_exists('connectType', $parameters ) && $parameters[ 'connectType' ] != '*' )
        {
            $filterArray['connect_type'] = $parameters['connectType'];
        }
        $triggers =& eZPersistentObject::fetchObjectList( eZTrigger::definition(),
                                                          null, $filterArray, array( 'module_name' , 'function_name', 'connect_type'), null,
                                                          true );
        return $triggers;
    }

    function runTrigger( $moduleName, $function, $type, $parameters = array(), $module )
    {
        $trigger = eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                null,
                                                array( 'module_name' => $moduleName,
                                                       'function_name' => $function,
                                                       'connect_type' => $type
                                                       ),
                                                true);
        if ( $trigger !== NULL )
        {
            $workflowID = $trigger->attribute( 'workflow_id' );
            eZDebug::writeNotice( $workflowID, "we are going to start workflow:" );
            $currentUser = eZUser::currentUser();
            $userID = $currentUser->id();
            $contentObject =& $parameters[ 'object' ];
            $version = $parameters[ 'version' ];
            $nodeID= $parameters[ 'parent_node_id' ];
            $objectID = $contentObject->attribute( 'id' );
            $workflowProcessList =& eZWorkflowProcess::fetchForContent( $workflowID, $userID,
                                                                         $objectID, $version, $nodeID,
                                                                        true );
            if ( count( $workflowProcessList ) > 0 )
            {
//                var_dump ( $workflowProcessList );
                if ( $workflowProcessList[0]->attribute( 'status' ) == EZ_WORKFLOW_STATUS_DONE )
                {
                    print( "already running trigger");
                    eZDebug::writeNotice( "already running trigger" );
                    return EZ_TRIGGER_NO_CONNECTED_WORKFLOWS;
                }else
                    return EZ_TRIGGER_STATUS_CRON_JOB;
            }else
            {
                print( "\n starting new workflow process \n");
                print( " $workflowID, $userID,
                                                                         $objectID, $version, $nodeID, \n ");
            }

            $workflowProcess =& eZWorkflowProcess::create( $workflowID , $userID, $objectID, $version, $nodeID );
            $workflowProcess->store();
            eZModuleRun::createFromModule( $workflowProcess->attribute( 'id' ), $module );

            $workflow =& eZWorkflow::fetch( $workflowProcess->attribute( "workflow_id" ) );
            $workflowEvent = null;

            if ( $workflowProcess->attribute( "event_id" ) != 0 )
                $workflowEvent =& eZWorkflowEvent::fetch( $workflowProcess->attribute( "event_id" ) );
            $workflowProcess->run( $workflow, $workflowEvent, $eventLog );
            $workflowProcess->store();
            return EZ_TRIGGER_STATUS_CRON_JOB;


        }else
        {
            eZDebug::writeNotice( $moduleName.$function.$type, "there is no connected workflow for:" );
            return EZ_TRIGGER_NO_CONNECTED_WORKFLOWS;
        }
    }

    function &createNew( $parameters=array() )
    {
        $trigger =& new eZTrigger( );
        $trigger->setAttribute( 'module_name' , $moduleName );
        $trigger->setAttribute( 'functionName',  $functionName );
        $trigger->store();
        return $trigger;
    }

}

?>
