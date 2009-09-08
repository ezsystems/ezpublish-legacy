<?php
//
// Definition of eZWorkflowProcess class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

class eZWorkflowProcess extends eZPersistentObject
{
    function eZWorkflowProcess( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
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

    static function create( $processKey, $parameters )
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
        return new eZWorkflowProcess( $row );
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
            case eZWorkflowType::STATUS_DEFERRED_TO_CRON:
            case eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT:
            case eZWorkflowType::STATUS_FETCH_TEMPLATE:
            case eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT:
            case eZWorkflowType::STATUS_REDIRECT:
            case eZWorkflowType::STATUS_REDIRECT_REPEAT:
            case eZWorkflowType::STATUS_WORKFLOW_RESET:
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
                        $eventType = $workflowEvent->eventType();
                        $eventLog[] = array( "status" => $currentEventStatus,
                                             "status_text" => eZWorkflowType::statusName( $currentEventStatus ),
                                             "information" => $eventType->attribute( "information" ),
                                             "description" => $workflowEvent->attribute( "description" ),
                                             "type_name" => $eventType->attribute( "name" ),
                                             "type_group" => $eventType->attribute( "group_name" ) );
                        if ( $currentEventStatus == eZWorkflowType::STATUS_DEFERRED_TO_CRON ||
                             $currentEventStatus == eZWorkflowType::STATUS_FETCH_TEMPLATE   ||
                             $currentEventStatus == eZWorkflowType::STATUS_REDIRECT )
                        {
                            $runCurrentEvent = false;
                        }
                    }
                    else if ( time() < $activationDate )
                    {
                        eZDebugSetting::writeDebug( 'workflow-process', "Date failed, not running events" );
                        $eventType = $workflowEvent->eventType();
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
                        if ( $currentEventStatus == eZWorkflowType::STATUS_DEFERRED_TO_CRON ||
                             $currentEventStatus == eZWorkflowType::STATUS_FETCH_TEMPLATE   ||
                             $currentEventStatus == eZWorkflowType::STATUS_REDIRECT )
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
            if ( $workflowEvent instanceof eZWorkflowEvent )
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent ), "workflowEvent class is ezworkflowevent" );
            }
            else if ( $workflowEvent !== null )
            {
                eZDebugSetting::writeDebug( 'workflow-process', get_class( $workflowEvent ), "workflowEvent class is not ezworkflowevent" );
            }
            eZDebugSetting::writeDebug( 'workflow-process', $done , "in while" );
            if ( $runCurrentEvent and
                 $workflowEvent !== null and
                 $workflowEvent instanceof eZWorkflowEvent )
            {
                $eventType = $workflowEvent->eventType();

                if ( $eventType instanceof eZWorkflowType )
                {
                    $currentEventStatus = $eventType->execute( $this, $workflowEvent );
                    $this->setAttribute( "event_status", $currentEventStatus );

                    $workflowParameters = $this->attribute( 'parameter_list' );

                    if ( isset( $workflowParameters['cleanup_list'] ) )
                    {
                        $cleanupList = $workflowParameters['cleanup_list'];
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
                        case eZWorkflowType::STATUS_ACCEPTED:
                        {
                            $done = false;
                            $workflowStatus = eZWorkflow::STATUS_DONE;
                        }break;
                        case eZWorkflowType::STATUS_WORKFLOW_DONE:
                        {
                            $done = true;
                            $workflowStatus = eZWorkflow::STATUS_DONE;
                        } break;
                        case eZWorkflowType::STATUS_REJECTED:
                        {
                            $done = true;
                            $workflowStatus = eZWorkflow::STATUS_FAILED;
                        } break;
                        case eZWorkflowType::STATUS_DEFERRED_TO_CRON:
                        case eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT:
                        {
                            if ( $eventType->hasAttribute( "activation_date" ) )
                            {
                                $date = $eventType->attribute( "activation_date" );
                                $this->setAttribute( "activation_date", $date );
                            }
                            $workflowStatus = eZWorkflow::STATUS_DEFERRED_TO_CRON;
                            $done = true;
                        } break;
                        case eZWorkflowType::STATUS_FETCH_TEMPLATE:
                        {
                            $workflowStatus = eZWorkflow::STATUS_FETCH_TEMPLATE;
                            $done = true;
                        } break;
                        case eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT:
                        {
                            $workflowStatus = eZWorkflow::STATUS_FETCH_TEMPLATE_REPEAT;
                            $done = true;
                        } break;
                        case eZWorkflowType::STATUS_REDIRECT:
                        case eZWorkflowType::STATUS_REDIRECT_REPEAT:
                        {
                            $workflowStatus = eZWorkflow::STATUS_REDIRECT;
                            $done = true;
                        } break;
                        case eZWorkflowType::STATUS_RUN_SUB_EVENT:
                        {
                            eZDebug::writeWarning( "Run sub event not supported yet", "eZWorkflowProcess::run" );
                        } break;
                        case eZWorkflowType::STATUS_WORKFLOW_CANCELLED:
                        {
                            $done = true;
                            $this->advance();
                            $workflowStatus = eZWorkflow::STATUS_CANCELLED;
                        } break;
                        case eZWorkflowType::STATUS_WORKFLOW_RESET:
                        {
                            $done = true;
                            $this->reset();
                            $workflowStatus = eZWorkflow::STATUS_RESET;
                        } break;
                        case eZWorkflowType::STATUS_NONE:
                        {
                            eZDebug::writeWarning( "Workflow executing status is eZWorkflowType::STATUS_NONE", "eZWorkflowProcess::run" );
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
                {
                    eZDebug::writeError( "Expected an eZWorkFlowType object", "eZWorkflowProcess::run" );
                }
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
                    $workflowStatus = eZWorkflow::STATUS_DONE;
                    $this->advance();
                }
            }

        }

        $this->setAttribute( "status", $workflowStatus );
        $this->setAttribute( "modified", time() );
        return $workflowStatus;
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowProcess::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    static function fetchList( $conds = null, $asObject = true, $offset = false, $limit = false )
    {
        $limitation = array( 'offset' => $offset,
                             'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, $limitation,
                                                    $asObject );
    }

    static function createKey( $parameters, $keys = null )
    {
        $string = '';
        if ( $keys != null )
        {
            foreach ( $keys as $key )
            {
                $value = $parameters[$key];
                if ( is_array( $value ) )
                {
                    $value = serialize( $value );
                }
                $string .= $key . $value;
            }
        }else
        {
            foreach ( array_keys( $parameters ) as $key )
            {
                $value = $parameters[$key];
                if ( is_array( $value ) )
                {
                    $value = serialize( $value );
                }
                $string .= $key . $value;
            }
        }
        return md5( $string );
    }



    static function fetchListByKey( $searchKey, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null,
                                                    array( 'process_key' => $searchKey ), null, null,
                                                    $asObject );
    }

    static function fetchUserList( $userID, $asObject = true )
    {
        $conds = array( 'user_id' => $userID );
        return eZPersistentObject::fetchObjectList( eZWorkflowProcess::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    static function fetchForContent( $workflowID, $userID,
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

    static function fetchForStatus( $status = eZWorkflow::STATUS_DEFERRED_TO_CRON,  $asObject = true )
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

    static function fetchForSession( $sessionKey, $workflowID, $asObject = true )
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

    function setParameters( $parameterList = null )
    {
        if ( $parameterList !== null )
        {
            $this->Parameters = $parameterList;
            unset( $this->ParameterList );
        }
        $this->setAttribute( 'parameters', serialize( $this->Parameters ) );
        return $this->attribute( 'parameter_list' );
    }

    function user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            return eZUser::instance( $this->UserID );
        }
        return null;
    }

    function content()
    {
        if ( isset( $this->ContentID ) and $this->ContentID )
        {
            return eZContentObject::fetch( $this->ContentID );
        }
        return null;
    }

    function node()
    {
        if ( isset( $this->NodeID ) and $this->NodeID )
        {
            return eZContentObjectTreeNode::fetch( $this->NodeID );
        }
        return null;
    }

    function workflow()
    {
        if ( isset( $this->WorkflowID ) and $this->WorkflowID )
        {
            return eZWorkflow::fetch( $this->WorkflowID );
        }
        return null;
    }

    function workflowEvent()
    {
        if ( isset( $this->EventID ) and $this->EventID )
        {
            return eZWorkflowEvent::fetch( $this->EventID );
        }
        return null;
    }

    function lastWorkflowEvent()
    {
        if ( isset( $this->LastEventID ) and $this->LastEventID )
        {
            return eZWorkflowEvent::fetch( $this->LastEventID );
        }
        return null;
    }

    function parameterList()
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
    function removeThis()
    {
        $workflowParameters = $this->attribute( 'parameter_list' );
        $cleanupList = array();

        $db = eZDB::instance();
        $db->begin();
        if ( isset( $workflowParameters['cleanup_list'] ) && is_array( $workflowParameters['cleanup_list'] ) )
        {
            $cleanupList = $workflowParameters['cleanup_list'];
            foreach ( $cleanupList as $workflowEventID )
            {
                $workflowEvent = eZWorkflowEvent::fetch( $workflowEventID );
                $workflowType = $workflowEvent->eventType();
                $workflowType->cleanup( $this, eZWorkflowEvent::fetch( $workflowEventID ) );
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
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezworkflow_process" );
    }

    /// \privatesection
    public $ID;
    public $WorkflowID;
    public $UserID;
    public $ContentID;
    public $NodeID;
    public $EventID;
    public $EventPosition;
    public $LastEventID;
    public $LastEventPosition;
    public $LastEventStatus;
    public $EventStatus;
    public $Created;
    public $Modified;
    public $ActivationDate;
}

?>
