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
define( "EZ_TRIGGER_FETCH_TEMPLATE", 4 );


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
                                         'workflow_id' => 'WorkflowID',
                                         'name' => 'Name'
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
        if ( array_key_exists('name', $parameters ) && $parameters[ 'name' ] != '' )
        {
            $filterArray['name'] = $parameters['name'];
        }
        $triggers =& eZPersistentObject::fetchObjectList( eZTrigger::definition(),
                                                          null, $filterArray, array( 'module_name' , 'function_name', 'connect_type'), null,
                                                          true );
        return $triggers;
    }



    function runTrigger( $name, $moduleName, $function, $parameters, $keys = null )
    {
        $trigger = eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                    null,
                                                    array( 'name' => $name,
                                                           'module_name' => $moduleName,
                                                           'function_name' => $function ),
                                                    true);
        if ( $trigger !== NULL )
        {
            $workflowID = $trigger->attribute( 'workflow_id' );
            $workflow =& eZWorkflow::fetch( $workflowID );
            eZDebug::writeNotice( $workflowID, "we are going to start workflow:" );
            if ( $keys != null )
            {
                $keys[] = 'workflow_id';
            }

            $parameters['workflow_id'] = $workflowID;
            $processKey = eZWorkflowProcess::createKey( $parameters, $keys );

//            $searchKey = eZWorkflowProcess::createKey( $keyArray );

            $workflowProcessList =& eZWorkflowProcess::fetchListByKey( $processKey );

            if ( count( $workflowProcessList ) > 0 )
            {
                $existingWorkflowProcess =& $workflowProcessList[0];
                $existingWorkflowStatus = $existingWorkflowProcess->attribute( 'status' );


                switch( $existingWorkflowStatus )
                {
                    case EZ_WORKFLOW_STATUS_FAILED:
                    case EZ_WORKFLOW_STATUS_CANCELLED:
                    case EZ_WORKFLOW_STATUS_NONE:
                    case EZ_WORKFLOW_STATUS_BUSY:
                    {
                        return array( 'Status' => EZ_TRIGGER_WORKFLOW_CANCELED,
                                      'Result' => null );
                    } break;
                    case EZ_WORKFLOW_STATUS_FETCH_TEMPLATE:
                    {
                        return eZTrigger::runWorkflow( $existingWorkflowProcess );
//                        return EZ_TRIGGER_FETCH_TEMPLATE;
                    } break;
                    case EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON:
                    {
                        return eZTrigger::runWorkflow( $existingWorkflowProcess );
/*                        return array( 'Status' => EZ_TRIGGER_STATUS_CRON_JOB,
                                      
                                      'Result' => array( 'content' => 'Operation halted during execution.<br/>Refresh page to continue<br/><br/><b>Note: The halt is just a temporary test</b><br/>',
                                                         'path' => array( array( 'text' => 'Operation halt',
                                                                            'url' => false ) ) ) );
*/                  } break;
                    case EZ_WORKFLOW_STATUS_DONE:
                    {
                        return array( 'Status' => EZ_TRIGGER_WORKFLOW_DONE,
                                      'Result' => null );
                    }
                }
                return array( 'Status' => EZ_TRIGGER_WORKFLOW_CANCELED,
                              'Result' => null );
            }else
            {
//                print( "\n starting new workflow process \n");
//                var_dump( $keyArray );
//                print( " $workflowID, $userID, $objectID, $version, $nodeID, \n ");
            }
            $workflowProcess =& eZWorkflowProcess::create( $processKey, $parameters );
            eZDebug::writeDebug( $workflowProcess, "We have created new workflow process" );

            $workflowProcess->store();

            return eZTrigger::runWorkflow( $workflowProcess );

        }
        else
        {
            eZDebug::writeNotice( $name.$moduleName.$function, "there is no connected workflow for:" );
            return array( 'Status' => EZ_TRIGGER_NO_CONNECTED_WORKFLOWS,
                          'Result' => null );
        }
    }


    function runWorkflow( &$workflowProcess )
    {
        $workflow =& eZWorkflow::fetch( $workflowProcess->attribute( "workflow_id" ) );
        $workflowEvent = null;

        $workflowStatus = $workflowProcess->run( $workflow, $workflowEvent, $eventLog );
        $workflowProcess->store();

        switch ( $workflowStatus )
        {
            case EZ_WORKFLOW_STATUS_FAILED:
            case EZ_WORKFLOW_STATUS_CANCELLED:
            case EZ_WORKFLOW_STATUS_NONE:
            case EZ_WORKFLOW_STATUS_BUSY:
            {
                return array( 'Status' => EZ_TRIGGER_WORKFLOW_CANCELED,
                              'Result' => null );
            } break;
            case EZ_WORKFLOW_STATUS_FETCH_TEMPLATE:
            {
                $tpl =& templateInit();
                $result = array();
                foreach ( array_keys( $workflowProcess->Template['templateVars'] ) as $key )
                {
                    $value =& $workflowProcess->Template['templateVars'][$key];
                    $tpl->setVariable( $key, $value );
                }
                $result['content'] =& $tpl->fetch( $workflowProcess->Template['templateName'] );
                return array( 'Status' => EZ_TRIGGER_FETCH_TEMPLATE,
                              'WorkflowProcess' => &$workflowProcess,
                              'Result' => $result['content'] );
            } break;
            case EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON:
            {
                return array( 'Status' => EZ_TRIGGER_STATUS_CRON_JOB,
                              'WorkflowProcess' => &$workflowProcess,
                              'Result' => array( 'content' => 'Operation halted during execution.<br/>Refresh page to continue<br/><br/><b>Note: The halt is just a temporary test</b><br/>',
                                                 'path' => array( array( 'text' => 'Operation halt',
                                                                         'url' => false ) ) ) );
/*
                return array( 'Status' => EZ_TRIGGER_STATUS_CRON_JOB,
                              'Result' => $workflowProcess->attribute( 'id') );
*/
            } break;
            case EZ_WORKFLOW_STATUS_DONE:
            {
                return array( 'Status' => EZ_TRIGGER_WORKFLOW_DONE,
                              'Result' => null );
            }
        }

        return array( 'Status' => EZ_TRIGGER_WORKFLOW_CANCELED,
                      'Result' => null );



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
