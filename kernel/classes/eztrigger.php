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



    function runTrigger( $moduleName, $function, $type, $parameters = array(), &$module )
    {
        $trigger = eZPersistentObject::fetchObject( eZTrigger::definition(),
                                                null,
                                                array( 'module_name' => $moduleName,
                                                       'function_name' => $function,
                                                       'connect_type' => $type ),
                                                true);
        if ( $trigger !== NULL )
        {
            $workflowID = $trigger->attribute( 'workflow_id' );
            $workflow =& eZWorkflow::fetch( $workflowID );
            eZDebug::writeNotice( $workflowID, "we are going to start workflow:" );

            $keyArray = array();
            if ( is_numeric( $workflowID ) )
            {
                $keyArray['workflow_id'] = $workflowID;
            }
            else
            {
                eZDebug::writeNotice( "can't get workflowID", 'eZTrigger::runTrigger' );
            }

            $currentUser = eZUser::currentUser();
            if ( !is_null( $currentUser ) )
            {
                $userID = $currentUser->id();
                $keyArray['user_id'] = $userID;
            }
            else
            {
                eZDebug::writeNotice( "can't get userID", 'eZTrigger::runTrigger' );
            }

            $contentObject =& $parameters[ 'object' ];
            if ( !is_null( $contentObject ) )
            {
                $objectID = $contentObject->attribute( 'id' );
                $keyArray['content_id'] = $objectID;
            }
            else
            {
                eZDebug::writeNotice( "can't get contentobjectID", 'eZTrigger::runTrigger' );
            }

//            $keyArray['node_id'] = '0';
//            $keyArray['content_version'] = '0';
//            $keyArray['session_key'] = '0';
            if ( array_key_exists( 'version', $parameters ) &&  !is_null( $parameters['version'] ) )
            {
                $keyArray['content_version'] = $parameters['version'];
            }
            else
            {
                eZDebug::writeNotice( "can't get object Version", 'eZTrigger::runTrigger' );
            }

            if( array_key_exists( 'parent_node_id', $parameters ) && !is_null( $parameters['parent_node_id'] ) )
            {
                $keyArray['node_id'] = $parameters['parent_node_id'];
            }

            if( array_key_exists( 'session_key', $parameters ) && !is_null( $parameters['session_key'] ) )
            {
                $keyArray['session_key'] = $parameters['session_key'];
            }

            if( array_key_exists( 'node_id', $parameters ) && !is_null( $parameters['node_id'] ) )
            {
                $keyArray['node_id'] = $parameters['node_id'];
            }

            $searchKey = eZWorkflowProcess::createKey( $keyArray );

            $workflowProcessList =& eZWorkflowProcess::fetchListByKey( $searchKey );

/*
            if ( $workflow->attribute( 'workflow_type_string' ) == 'group_ezcontentbased' )
            {
                $workflowProcessList =& eZWorkflowProcess::fetchForContent( $workflowID, $userID,
                                                                            $objectID, $version, $nodeID,
                                                                            true );
            }
            elseif ( $workflow->attribute( 'workflow_type_string' ) == 'group_ezsessionbased' )
            {

                $conditions = array( 'session_key' => $sessionKey,
                                     'workflow_id' => $workflowID,
                                     'node_id' => $parameters['node_id'] );
                $workflowProcessList =& eZWorkflowProcess::fetchList( $conditions );
//                $workflowProcessList =& eZWorkflowProcess::fetchForSession( $sessionKey, $workflowID,  );
//                var_dump($workflowProcessList);

            }*/
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
                        return EZ_TRIGGER_WORKFLOW_CANCELED;
                    } break;
                    case EZ_WORKFLOW_STATUS_FETCH_TEMPLATE:
                    {
                        return eZTrigger::runWorkflow( $existingWorkflowProcess, $module );
                        return EZ_TRIGGER_FETCH_TEMPLATE;
                    } break;
                    case EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON:
                    {
                        return EZ_TRIGGER_STATUS_CRON_JOB;
                    } break;
                    case EZ_WORKFLOW_STATUS_DONE:
                    {
                        return EZ_TRIGGER_WORKFLOW_DONE;
                    }
                }
                    return EZ_TRIGGER_WORKFLOW_CANCELED;
            }else
            {
                print( "\n starting new workflow process \n");
                var_dump( $keyArray );
//                print( " $workflowID, $userID, $objectID, $version, $nodeID, \n ");
            }

            $workflowProcess =& eZWorkflowProcess::create( $searchKey, $keyArray );

            $workflowProcess->store();
            eZModuleRun::createFromModule( $workflowProcess->attribute( 'id' ), $module );

            return eZTrigger::runWorkflow( $workflowProcess, $module );

        }
        else
        {
            eZDebug::writeNotice( $moduleName.$function.$type, "there is no connected workflow for:" );
            return EZ_TRIGGER_NO_CONNECTED_WORKFLOWS;
        }
    }


    function runWorkflow( &$workflowProcess, &$module )
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
                return EZ_TRIGGER_WORKFLOW_CANCELED;
            } break;
            case EZ_WORKFLOW_STATUS_FETCH_TEMPLATE:
            {
                $tpl =& templateInit();
                $Result = array();
                foreach ( array_keys( $workflowProcess->Template['templateVars'] ) as $key )
                {
                    $value =& $workflowProcess->Template['templateVars'][$key];
                    $tpl->setVariable( $key, $value );
                }
                $Result['content'] =& $tpl->fetch( $workflowProcess->Template['templateName'] );
                $module->setViewResult( $Result );
                return EZ_TRIGGER_FETCH_TEMPLATE;
            } break;
            case EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON:
            {
                return EZ_TRIGGER_STATUS_CRON_JOB;
            } break;
            case EZ_WORKFLOW_STATUS_DONE:
            {
                return EZ_TRIGGER_WORKFLOW_DONE;
            }
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
