<?php
//
// Definition of eZTrigger class
//
// Created on: <11-аущ-2002 13:11:15 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file eztrigger.php
*/

/*!
  \class eZTrigger eztrigger.php
  \brief The class eZTrigger does

*/
//include_once( 'kernel/classes/ezworkflowprocess.php' );
//include_once( 'kernel/classes/ezworkflow.php' );

class eZTrigger extends eZPersistentObject
{
    const STATUS_CRON_JOB = 0;
    const WORKFLOW_DONE = 1;
    const WORKFLOW_CANCELLED = 2;
    const NO_CONNECTED_WORKFLOWS = 3;
    const FETCH_TEMPLATE = 4;
    const REDIRECT = 5;
    const WORKFLOW_RESET = 6;

    /*!
     Constructor
    */
    function eZTrigger( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'module_name' => array( 'name' => 'ModuleName',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'function_name' => array( 'name' => 'FunctionName',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'connect_type' => array( 'name' => 'ConnectType',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ),
                                         'workflow_id' => array( 'name' => 'WorkflowID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZWorkflow',
                                                                 'foreign_attribute' => 'id',
                                                                 'multiplicity' => '1..*' ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "class_name" => "eZTrigger",
                      "keys" => array( 'id' ),
                      'function_attributes' => array( 'allowed_workflows' => 'fetchAllowedWorkflows' ),
                      "increment_key" => "id",
                      "name" => "eztrigger" );
    }

    /*!
     Get array containing allowed workflows for this trigger.

     \return array containing allowed workflows
    */
    function fetchAllowedWorkflows()
    {
        $connectionType = '*';
        if ( $this->attribute( 'connect_type') == 'b' )
        {
            $connectionType = 'before';
        }
        else if ( $this->attribute( 'connect_type') == 'a' )
        {
            $connectionType = 'after';
        }

        return eZWorkflow::fetchLimited( $this->attribute( 'module_name' ),
                                         $this->attribute( 'function_name' ),
                                         $connectionType );
    }

    static function fetch( $triggerID )
    {
        return eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                null,
                                                array( 'id' => $triggerID ),
                                                true);
    }

    static function fetchList( $parameters = array(), $asObject = true )
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
        return eZPersistentObject::fetchObjectList( eZTrigger::definition(),
                                                    null,
                                                    $filterArray, array( 'module_name' => 'asc' ,
                                                                         'function_name' => 'asc',
                                                                         'connect_type' => 'asc' ),
                                                    null,
                                                    $asObject );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function runTrigger( $name, $moduleName, $function, $parameters, $keys = null )
    {
        $trigger = eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                    null,
                                                    array( 'name' => $name,
                                                           'module_name' => $moduleName,
                                                           'function_name' => $function ),
                                                    true );
        if ( $trigger !== NULL )
        {
            $workflowID = $trigger->attribute( 'workflow_id' );
            $workflow = eZWorkflow::fetch( $workflowID );
            if ( $keys != null )
            {
                $keys[] = 'workflow_id';
            }

            $parameters['workflow_id'] = $workflowID;
            // It is very important that the user_id is set correctly.
            // If it was not supplied by the calling code we will use
            // the currently logged in user.
            if ( !isset( $parameters['user_id'] ) or
                 $parameters['user_id'] == 0 )
            {
                $user = eZUser::currentUser();
                $parameters['user_id'] = $user->attribute( 'contentobject_id' );
            }
            $processKey = eZWorkflowProcess::createKey( $parameters, $keys );

//            $searchKey = eZWorkflowProcess::createKey( $keyArray );

            $workflowProcessList = eZWorkflowProcess::fetchListByKey( $processKey );

            if ( count( $workflowProcessList ) > 0 )
            {
                $existingWorkflowProcess = $workflowProcessList[0];
                $existingWorkflowStatus = $existingWorkflowProcess->attribute( 'status' );


                switch( $existingWorkflowStatus )
                {
                    case eZWorkflow::STATUS_FAILED:
                    case eZWorkflow::STATUS_CANCELLED:
                    case eZWorkflow::STATUS_NONE:
                    case eZWorkflow::STATUS_BUSY:
                    {
                        $existingWorkflowProcess->removeThis();
                        return array( 'Status' => eZTrigger::WORKFLOW_CANCELLED,
                                      'Result' => null );
                    } break;
                    case eZWorkflow::STATUS_FETCH_TEMPLATE:
                    case eZWorkflow::STATUS_REDIRECT:
                    case eZWorkflow::STATUS_RESET:
                    {
                        return eZTrigger::runWorkflow( $existingWorkflowProcess );
//                        return eZTrigger::FETCH_TEMPLATE;
                    } break;
                    case eZWorkflow::STATUS_DEFERRED_TO_CRON:
                    {
                        return eZTrigger::runWorkflow( $existingWorkflowProcess );
/*                        return array( 'Status' => eZTrigger::STATUS_CRON_JOB,

                                      'Result' => array( 'content' => 'Operation halted during execution.<br/>Refresh page to continue<br/><br/><b>Note: The halt is just a temporary test</b><br/>',
                                                         'path' => array( array( 'text' => 'Operation halt',
                                                                            'url' => false ) ) ) );
*/                  } break;
                    case eZWorkflow::STATUS_DONE:
                    {
                        $existingWorkflowProcess->removeThis();
                        return array( 'Status' => eZTrigger::WORKFLOW_DONE,
                                      'Result' => null );
                    }
                }
                return array( 'Status' => eZTrigger::WORKFLOW_CANCELLED,
                              'Result' => null );
            }else
            {
//                print( "\n starting new workflow process \n");
//                var_dump( $keyArray );
//                print( " $workflowID, $userID, $objectID, $version, $nodeID, \n ");
            }
            $workflowProcess = eZWorkflowProcess::create( $processKey, $parameters );

            $workflowProcess->store();

            return eZTrigger::runWorkflow( $workflowProcess );

        }
        else
        {
            return array( 'Status' => eZTrigger::NO_CONNECTED_WORKFLOWS,
                          'Result' => null );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function runWorkflow( $workflowProcess )
    {
        $workflow = eZWorkflow::fetch( $workflowProcess->attribute( "workflow_id" ) );
        $workflowEvent = null;

        $workflowStatus = $workflowProcess->run( $workflow, $workflowEvent, $eventLog );

        $db = eZDB::instance();
        $db->begin();
        $workflowProcess->store();

        switch ( $workflowStatus )
        {
            case eZWorkflow::STATUS_FAILED:
            case eZWorkflow::STATUS_CANCELLED:
            case eZWorkflow::STATUS_NONE:
            case eZWorkflow::STATUS_BUSY:
            {
                $workflowProcess->removeThis();
                $db->commit();
                return array( 'Status' => eZTrigger::WORKFLOW_CANCELLED,
                              'Result' => null );
            } break;
            case eZWorkflow::STATUS_FETCH_TEMPLATE:
            {
                require_once( 'kernel/common/template.php' );
                $tpl = templateInit();
                $result = array();
                foreach ( array_keys( $workflowProcess->Template['templateVars'] ) as $key )
                {
                    $value = $workflowProcess->Template['templateVars'][$key];
                    $tpl->setVariable( $key, $value );
                }
                $result['content'] = $tpl->fetch( $workflowProcess->Template['templateName'] );
                if ( isset( $workflowProcess->Template['path'] ) )
                    $result['path'] = $workflowProcess->Template['path'];

                    $db->commit();
                return array( 'Status' => eZTrigger::FETCH_TEMPLATE,
                              'WorkflowProcess' => $workflowProcess,
                              'Result' => $result );
            } break;
            case eZWorkflow::STATUS_REDIRECT:
            {
//                var_dump( $workflowProcess->RedirectUrl  );
                $db->commit();
                return array( 'Status' => eZTrigger::REDIRECT,
                              'WorkflowProcess' => $workflowProcess,
                              'Result' => $workflowProcess->RedirectUrl );

            } break;
            case eZWorkflow::STATUS_DEFERRED_TO_CRON:
            {

                $db->commit();
                return array( 'Status' => eZTrigger::STATUS_CRON_JOB,
                              'WorkflowProcess' => $workflowProcess,
                              'Result' => array( 'content' => 'Deffered to cron. Operation halted during execution. <br/>Refresh page to continue<br/><br/><b>Note: The halt is just a temporary test</b><br/>',
                                                 'path' => array( array( 'text' => 'Operation halt',
                                                                         'url' => false ) ) ) );
/*
                return array( 'Status' => eZTrigger::STATUS_CRON_JOB,
                              'Result' => $workflowProcess->attribute( 'id') );
*/
            } break;
            case eZWorkflow::STATUS_RESET:
            {
                $db->commit();
                return array( 'Status' => eZTrigger::WORKFLOW_RESET,
                              'WorkflowProcess' => $workflowProcess,
                              'Result' => array( 'content' => 'Workflow was reset',
                                                 'path' => array( array( 'text' => 'Operation halt',
                                                                         'url' => false ) ) ) );
            } break;
            case eZWorkflow::STATUS_DONE:
            {
                $workflowProcess->removeThis();
                $db->commit();
                return array( 'Status' => eZTrigger::WORKFLOW_DONE,
                              'Result' => null );
            }
        }

        $db->commit();
        return array( 'Status' => eZTrigger::WORKFLOW_CANCELLED,
                      'Result' => null );



    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function createNew( $moduleName, $functionName, $connectType, $workflowID, $name = false )
    {
        if ( !$name )
        {
            if ( $connectType == 'b' )
            {
                $name = 'pre_';
            }
            else if ( $connectType == 'a' )
            {
                $name = 'post_';
            }
            $name .= $functionName;
        }
        $trigger = new eZTrigger( array( 'module_name' => $moduleName,
                                         'function_name' => $functionName,
                                         'connect_type' => $connectType,
                                         'workflow_id' => $workflowID,
                                         'name' => $name ) );
        $trigger->store();
        return $trigger;
    }

    /*!
     Removes triggers which uses the given workflowID.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function removeTriggerForWorkflow( $workFlowID )
    {
        $db = eZDB::instance();
        $workFlowID = (int)$workFlowID;
        $db->query( "DELETE FROM eztrigger WHERE workflow_id=$workFlowID" );
    }
}

?>
