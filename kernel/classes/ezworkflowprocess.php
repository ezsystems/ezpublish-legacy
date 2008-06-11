<?php
//
// Definition of eZWorkflowProcess class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZWorkflowProcess ezworkflowprocess.php
  \brief

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezworkflowevent.php' );

class eZWorkflowProcess extends eZPersistentObject
{
    function eZWorkflowProcess( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'process_key' => array( 'name' => 'ProcessKey',
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
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         'content_id' => array( 'name' => 'ContentID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'content_version' => array( 'name' => 'ContentVersion',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         'session_key' => array( 'name' => 'SessionKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '0',
                                                                 'required' => true ),
                                         'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'event_id' => array( 'name' => 'EventID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'event_position' => array( 'name' => 'EventPosition',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         'event_state' => array( 'name' => 'EventState',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'last_event_id' => array( 'name' => 'LastEventID',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'last_event_position' => array( 'name' => 'LastEventPosition',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                         'last_event_status' => array( 'name' => 'LastEventStatus',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true ),
                                         'event_status' => array( 'name' => 'EventStatus',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'activation_date' => array( 'name' => 'ActivationDate',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'parameters' => array( 'name' => 'Parameters',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'memento_key' => array( 'name' => 'MementoKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'user' => 'user',
                                                      'content' => 'content',
                                                      'node' => 'node',
                                                      'workflow' => 'workflow',
                                                      'workflow_event' => 'workflowEvent',
                                                      'last_workflow_event' => 'lastWorkflowEvent',
                                                      'parameter_list' => 'parameterList' ),
                      "increment_key" => "id",
                      'class_name' => 'eZWorkflowProcess',
                      'sort' => array( 'user_id' => 'asc' ),
                      'name' => 'ezworkflow_process' );
    }

    function &create( $processKey, $parameters )
//                      $workflowID, $userID,
//                      $contentID, $contentVersion, $nodeID, $sessionKey = '' )
    {
        $dateTime = time();
        if ( !isset( $parameters['user_id'] ) )
            $parameters['user_id'] = 0;
        $row = array( 'process_key' => $processKey,
                      'workflow_id' => $parameters['workflow_id'],
                      'user_id' => $parameters['user_id'],
                      'content_id' => 0,
                      'content_version' => 0,
                      'node_id' => 0,
                      'session_key' => 0,
                      'event_id' => 0,
                      'event_position' => 0,
                      'last_event_id' => 0,
                      'last_event_position' => 0,
                      'last_event_status' => 0,
                      'event_status' => 0,
                      'created' => $dateTime,
                      'modified' => $dateTime,
                      'parameters' => serialize( $parameters ) );
        $newWorkflowProccess = new eZWorkflowProcess( $row );
        return $newWorkflowProccess;
    }

    function reset()
    {
        $this->EventID = 0;
        $this->EventPosition = 0;
        $this->LastEventID = 0;
        $this->LastEventPosition = 0;
        $this->LastEventStatus = 0;
        $this->ActivationDate = 0;
        $this->EventStatus = 0;
        $this->EventState = 0;
    }

    function advance( $next_event_id = 0, $next_event_pos = 0, $status = 0 )
    {
        $this->LastEventID = $this->EventID;
        $this->LastEventPosition = $this->EventPosition;
        $this->LastEventStatus = $status;
        $this->EventID =  $next_event_id;
        $this->EventPosition = $next_event_pos;
        $this->ActivationDate = 0;
    }


    function run( &$workflow, &$workflowEvent, &$eventLog )
    {
        $eventLog = array();
        eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent, "workflowEvent in process->run beginning" );

        $runCurrentEvent = true;
        $done = false;
        $workflowStatus = $this->attribute( 'status' );
        eZDebugSetting::writeDebug( 'workflow-process', $workflowStatus , 'workflowStatus' );
        $currentEventStatus = $this->attribute( 'event_status' );

        // just temporary. needs to be removed from parameters
        if ( $workflowEvent == null )
        {
            $workflowEvent = eZWorkflowEvent::fetch( $this->attribute( 'event_id' ) );
        }

        switch( $currentEventStatus )
        {
            case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON:
            case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE:
            case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_REDIRECT:
            case EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT:
            case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET:
            {
                if ( $workflowEvent !== null )
                {
                    $activationDate = 0;
                    if ( $this->hasAttribute( 'activation_date' ) )
                    {
                        $activationDate = $this->attribute( "activation_date" );
                    }
                    eZDebugSetting::writeDebug( 'workflow-process', "Checking activation date" );
                    if ( $activationDate == 0  )
                    {
                        $eventType =& $workflowEvent->eventType();
                        $eventLog[] = array( "status" => $currentEventStatus,
                                             "status_text" => eZWorkflowType::statusName( $currentEventStatus ),
                                             "information" => $eventType->attribute( "information" ),
                                             "description" => $workflowEvent->attribute( "description" ),
                                             "type_name" => $eventType->attribute( "name" ),
                                             "type_group" => $eventType->attribute( "group_name" ) );
                        if ( $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON ||
                             $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE   ||
                             $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_REDIRECT )
                        {
                            $runCurrentEvent = false;
                        }
                    }
                    else if ( time() < $activationDate )
                    {
                        eZDebugSetting::writeDebug( 'workflow-process', "Date failed, not running events" );
                        $eventType =& $workflowEvent->eventType();
                        $eventLog[] = array( "status" => $currentEventStatus,
                                             "status_text" => eZWorkflowType::statusName( $currentEventStatus ),
                                             "information" => $eventType->attribute( "information" ),
                                             "description" => $workflowEvent->attribute( "description" ),
                                             "type_name" => $eventType->attribute( "name" ),
                                             "type_group" => $eventType->attribute( "group_name" ) );
                        $done = true;
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'workflow-process', "Date ok, running events" );
                        eZDebugSetting::writeDebug( 'workflow-process', $currentEventStatus, 'WORKFLOW_TYPE_STATUS' );
                        if ( $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON ||
                             $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE   ||
                             $currentEventStatus == EZ_WORKFLOW_TYPE_STATUS_REDIRECT )
                        {
                            $runCurrentEvent = false;
                        }
                    }
                }
            } break;
            default:
                break;
        }

        while ( !$done )
        {
            if ( $runCurrentEvent )
            {
                eZDebugSetting::writeDebug( 'workflow-process', "runCurrentEvent is true" );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', "runCurrentEvent is false" );
            }
            if ( $workflowEvent != null )
            {
                //eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent ,"workflowEvent  is not null" );
            }
            else
            {
                //eZDebugSetting::writeDebug( 'workflow-process', $workflowEvent ,"workflowEvent  is  null" );
            }
            if ( get_class( $workflowEvent ) == "ezworkflowevent")
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent )  ,"workflowEvent class  is ezworkflowevent " );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent )  ,"workflowEvent class  is not ezworkflowevent " );
            }
            eZDebugSetting::writeDebug( 'workflow-process', $done , "in while" );
            if ( $runCurrentEvent and
                 $workflowEvent !== null and
                 get_class( $workflowEvent ) == "ezworkflowevent" )
            {
                $eventType =& $workflowEvent->eventType();

                if ( is_subclass_of( $eventType, "ezworkflowtype" ) )
                {
                    $currentEventStatus = $eventType->execute( $this, $workflowEvent );
                    $this->setAttribute( "event_status", $currentEventStatus );

                    $workflowParameters =& $this->attribute( 'parameter_list' );

                    if ( isset( $workflowParameters['cleanup_list'] ) )
                    {
                        $cleanupList =& $workflowParameters['cleanup_list'];
                    }
                    else
                    {
                        unset( $cleanupList );
                        $cleanupList = array();
                    }

                    if ( $eventType->needCleanup() )
                    {
                        $cleanupList[] = $workflowEvent->attribute( 'id' );
                        $workflowParameters['cleanup_list'] = $cleanupList;
                        $this->setAttribute( 'parameters', serialize( $workflowParameters ) );
                    }

                    eZDebugSetting::writeDebug( 'workflow-process', $currentEventStatus, "currentEventStatus" );
                    switch( $currentEventStatus )
                    {
                        case EZ_WORKFLOW_TYPE_STATUS_ACCEPTED:
                        {
                            $done = false;
                            $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                        }break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE:
                        {
                            $done = true;
                            $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_REJECTED:
                        {
                            $done = true;
                            $workflowStatus = EZ_WORKFLOW_STATUS_FAILED;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON:
                        case EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT:
                        {
                            if ( $eventType->hasAttribute( "activation_date" ) )
                            {
                                $date = $eventType->attribute( "activation_date" );
                                $this->setAttribute( "activation_date", $date );
                            }
                            $workflowStatus = EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE:
                        case EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT:
                        {
                            $workflowStatus = EZ_WORKFLOW_STATUS_FETCH_TEMPLATE;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_REDIRECT:
                        case EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT:
                        {
                            $workflowStatus = EZ_WORKFLOW_STATUS_REDIRECT;
                            $done = true;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_RUN_SUB_EVENT:
                        {
                            eZDebug::writeWarning( "Run sub event not supported yet", "eZWorkflowProcess::run" );
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED:
                        {
                            $done = true;
                            $this->advance();
                            $workflowStatus = EZ_WORKFLOW_STATUS_CANCELLED;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET:
                        {
                            $done = true;
                            $this->reset();
                            $workflowStatus = EZ_WORKFLOW_STATUS_RESET;
                        } break;
                        case EZ_WORKFLOW_TYPE_STATUS_NONE:
                        {
                            eZDebug::writeWarning( "Workflow executing status is EZ_WORKFLOW_TYPE_STATUS_NONE", "eZWorkflowProcess::run" );
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( "Unknown status '$currentEventStatus'", "eZWorkflowProcess::run" );
                        } break;
                    }
                    $eventLog[] = array( "status" => $currentEventStatus,
                                         "status_text" => eZWorkflowType::statusName( $currentEventStatus ),
                                         "information" => $eventType->attribute( "information" ),
                                         "description" => $workflowEvent->attribute( "description" ),
                                         "type_name" => $eventType->attribute( "name" ),
                                         "type_group" => $eventType->attribute( "group_name" ) );
                }
                else
                    eZDebug::writeError( "Expected an eZWorkFlowType object", "eZWorkflowProcess::run" );
            }
            else
            {
                eZDebugSetting::writeDebug( 'workflow-process', "Not running current event. Trying next" );
            }
            $runCurrentEvent = true;
            // still not done
            if ( !$done )
            {
                // fetch next event
                $event_pos = $this->attribute( "event_position" );

                $next_event_pos = $event_pos + 1;
                $next_event_id = $workflow->fetchEventIndexed( $next_event_pos );
                if ( $next_event_id !== null )
                {
                    eZDebugSetting::writeDebug( 'workflow-process', $event_pos , "workflow not done");
                    $this->advance( $next_event_id, $next_event_pos, $currentEventStatus );
                    $workflowEvent = eZWorkflowEvent::fetch( $next_event_id );
                }
                else
                {
                    $done = true;
                    unset( $workflowEvent );
                    eZDebugSetting::writeDebug( 'workflow-process', $event_pos , "workflow done");
                    $workflowStatus = EZ_WORKFLOW_STATUS_DONE;
                    $this->advance();
                }
            }

        }

        $this->setAttribute( "status", $workflowStatus );
        $this->setAttribute( "modified", time() );
        return $workflowStatus;
    }


    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function store()
    {
        eZPersistentObject::store();
    }

    function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowProcess::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    function fetchList( $conds = null, $asObject = true, $offset = false, $limit = false )
    {
        $limitation = array( 'offset' => $offset,
                             'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, $limitation,
                                                    $asObject );
    }

    /*!
     \return The number of workflow processes in the database. Optionally \a $conditions can be used to limit the list count.
     \sa fetchList
    */
    function fetchListCount( $conditions = null )
    {
        $rows =  eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                      array(),
                                                      $conditions,
                                                      false,
                                                      null,
                                                      false, false,
                                                      array( array( 'operation' => 'count( * )',
                                                                    'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    function createKey( $parameters, $keys = null )
    {

        $string = '';
        if ( $keys != null )
        {
            foreach ( $keys as $key )
            {
                $value = $parameters[$key];
                $string .= $key . $value;
            }
        }else
        {
            foreach ( array_keys( $parameters ) as $key )
            {
                $value =& $parameters[$key];
                $string .= $key . $value;
            }
        }
        return md5( $string );
    }



    function fetchListByKey( $searchKey, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null,
                                                    array( 'process_key' => $searchKey ), null, null,
                                                    $asObject );
    }

    function fetchUserList( $userID, $asObject = true )
    {
        $conds = array( 'user_id' => $userID );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function fetchForContent( $workflowID, $userID,
                               $contentID, $content_version, $nodeID,
                               $asObject = true )
    {
        $conds = array( 'workflow_id' => $workflowID,
                        'user_id' => $userID,
                        'content_id' => $contentID,
                        'content_version' => $contentVersion,
                        'node_id' => $nodeID );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }
    function fetchForStatus( $status = EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON,  $asObject = true )
    {
        $conds = array( 'status' => $status );

        $db = eZDB::instance();
        if ( $db->databaseName() == 'oracle' )
            $conds['LENGTH(memento_key)'] = array( '!=', 0 );
        else
            $conds['memento_key'] = array( '!=', '' );

        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function fetchForSession( $sessionKey, $workflowID, $asObject = true )
    {
        $conds = array( 'workflow_id' => $workflowID,
                        'session_key' => $sessionKey );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function currentEvent()
    {
    }

    function advanceToNext()
    {
    }

    function &setParameters( $parameterList = null )
    {
        if ( !is_null( $parameterList ) )
        {
            $this->Parameters = $parameterList;
            unset( $this->ParameterList );
        }
        $this->setAttribute( 'parameters', serialize( $this->Parameters ) );
        $parameterList = $this->attribute( 'parameter_list' );
        return $parameterList;
    }

    function &user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user =& eZUser::instance( $this->UserID );
        }
        else
            $user = null;
        return $user;
    }

    function &content()
    {
        if ( isset( $this->ContentID ) and $this->ContentID )
        {
            include_once( 'kernel/classes/ezcontentobject.php' );
            $content =& eZContentObject::fetch( $this->ContentID );
        }
        else
            $content = null;
        return $content;
    }

    function &node()
    {
        if ( isset( $this->NodeID ) and $this->NodeID )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $node = eZContentObjectTreeNode::fetch( $this->NodeID );
        }
        else
            $node = null;
        return $node;
    }

    function &workflow()
    {
        if ( isset( $this->WorkflowID ) and $this->WorkflowID )
        {
            include_once( 'kernel/classes/ezworkflow.php' );
            $workflow = eZWorkflow::fetch( $this->WorkflowID );
        }
        else
            $workflow = null;
        return $workflow;
    }

    function &workflowEvent()
    {
        if ( isset( $this->EventID ) and $this->EventID )
        {
            include_once( 'kernel/classes/ezworkflowevent.php' );
            $event = eZWorkflowEvent::fetch( $this->EventID );
        }
        else
            $event = null;
        return $event;
    }

    function &lastWorkflowEvent()
    {
        if ( isset( $this->LastEventID ) and $this->LastEventID )
        {
            include_once( 'kernel/classes/ezworkflowevent.php' );
            $lastEvent = eZWorkflowEvent::fetch( $this->LastEventID );
        }
        else
            $lastEvent = null;
        return $lastEvent;
    }

    function &parameterList()
    {
        if ( !isset( $this->ParameterList ) )
        {
            $this->ParameterList = unserialize( $this->attribute( 'parameters' ) );
        }
        return $this->ParameterList;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove()
    {
        $workflowParameters = $this->attribute( 'parameter_list' );
        $cleanupList = array();

        $db =& eZDB::instance();
        $db->begin();
        if ( isset( $workflowParameters['cleanup_list'] ) && is_array( $workflowParameters['cleanup_list'] ) )
        {
            $cleanupList = $workflowParameters['cleanup_list'];
            foreach ( array_keys( $cleanupList ) as $key )
            {
                $workflowEventID = $cleanupList[$key];
                $workflowEvent = eZWorkflowEvent::fetch( $workflowEventID );
                $workflowType =& $workflowEvent->eventType();
                $workflowType->cleanup( $this, $workflowEvent );
            }
        }
        eZPersistentObject::removeObject( eZWorkflowProcess::definition(), array( 'id' => $this->attribute( 'id' ) ) );
        $db->commit();
    }

    /*!
     \static
     Removes all workflow processes from database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezworkflow_process" );
    }

    /// \privatesection
    var $ID;
    var $WorkflowID;
    var $UserID;
    var $ContentID;
    var $NodeID;
    var $EventID;
    var $EventPosition;
    var $LastEventID;
    var $LastEventPosition;
    var $LastEventStatus;
    var $EventStatus;
    var $Created;
    var $Modified;
    var $ActivationDate;
}

?>
