<?php
//
// Definition of eZWorkflow class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! eZKernel
//! The class eZWorkflow does
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezworkflowevent.php" );

define( "EZ_WORKFLOW_STATUS_NONE", 0 );
define( "EZ_WORKFLOW_STATUS_BUSY", 1 );
define( "EZ_WORKFLOW_STATUS_DONE", 2 );
define( "EZ_WORKFLOW_STATUS_FAILED", 3 );
define( "EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON", 4 );
define( "EZ_WORKFLOW_STATUS_CANCELLED", 5 );
define( "EZ_WORKFLOW_STATUS_FETCH_TEMPLATE", 6 );
define( "EZ_WORKFLOW_STATUS_REDIRECT", 7 );
define( "EZ_WORKFLOW_STATUS_RESET", 8 );

class eZWorkflow extends eZPersistentObject
{
    function eZWorkflow( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "is_enabled" => array( 'name' => "IsEnabled",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "workflow_type_string" => array( 'name' => "WorkflowTypeString",
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      "keys" => array( "id", "version" ),
                      "increment_key" => "id",
                      "class_name" => "eZWorkflow",
                      "sort" => array( "name" => "asc" ),
                      "name" => "ezworkflow" );
    }

    function statusName( $status )
    {
        $statusNames =& $GLOBAL["eZWorkflowStatusNames"];
        if ( !is_array( $statusNames ) )
        {
            $statusNames = array( EZ_WORKFLOW_STATUS_NONE => ezi18n( 'kernel/classes', 'No state yet' ),
                                  EZ_WORKFLOW_STATUS_BUSY => ezi18n( 'kernel/classes', 'Workflow running' ),
                                  EZ_WORKFLOW_STATUS_DONE => ezi18n( 'kernel/classes', 'Workflow done' ),
                                  EZ_WORKFLOW_STATUS_FAILED => ezi18n( 'kernel/classes', 'Workflow failed an event' ),
                                  EZ_WORKFLOW_STATUS_DEFERRED_TO_CRON => ezi18n( 'kernel/classes', 'Workflow event deferred to cron job' ),
                                  EZ_WORKFLOW_STATUS_CANCELLED => ezi18n( 'kernel/classes', 'Workflow was cancelled' ),
                                  EZ_WORKFLOW_STATUS_FETCH_TEMPLATE => ezi18n( 'kernel/classes', 'Workflow fetches template' ),
                                  EZ_WORKFLOW_STATUS_REDIRECT => ezi18n( 'kernel/classes', 'Workflow redirects user view' ),
                                  EZ_WORKFLOW_STATUS_RESET => ezi18n( 'kernel/classes', 'Workflow was reset for reuse' ) );
        }
        if ( isset( $statusNames[$status] ) )
            return $statusNames[$status];
        return false;
    }

    function &create( $user_id )
    {
        $date_time = time();
        $row = array(
            "id" => null,
            "workflow_type_string" => "group_ezserial",
            "version" => 1,
            "is_enabled" => 1,
            "name" => "",
            "creator_id" => $user_id,
            "modifier_id" => $user_id,
            "created" => $date_time,
            "modified" => $date_time );
        return new eZWorkflow( $row );
    }

    function setIsEnabled( $enabled, $id = false, $version = 0 )
    {
        if ( $id === false )
            $id = $this->attribute( "id" );
        eZPersistentObject::updateObjectList( array(
                                                  "definition" => eZWorkflow::definition(),
                                                  "update_fields" => array( "is_enabled" => ( $enabled ? 1 : 0 ) ),
                                                  "conditions" => array( "id" => $id,
                                                                         "version" => $version )
                                                  )
                                              );
    }

    function removeWorkflow( $id, $version )
    {
        eZPersistentObject::removeObject( eZWorkflow::definition(),
                                          array("id" => $id,
                                                "version" => $version ) );
    }

    function remove( $remove_childs = false )
    {
        if ( is_array( $remove_childs ) or $remove_childs )
        {
            if ( is_array( $remove_childs ) )
            {
                $events =& $remove_childs;
                for ( $i = 0; $i < count( $events ); ++$i )
                {
                    $event =& $events[$i];
                    $event->remove();
                }
            }
            else
            {
                eZPersistentObject::removeObject( eZWorkflowEvent::definition(),
                                                  array( "workflow_id" => $this->ID,
                                                         "version" => $this->Version ) );
            }
        }
        eZPersistentObject::remove();
    }

    /*!
     \static
     Removes all temporary versions.
    */
    function removeTemporary()
    {
        $version = 1;
        $temporaryWorkflows =& eZWorkflow::fetchList( $version, null, true );
        foreach ( $temporaryWorkflows as $workflow )
        {
            $workflow->remove( true );
        }
        eZPersistentObject::removeObject( eZWorkflowEvent::definition(),
                                          array( 'version' => $version ) );
    }

    function removeEvents( $events = false, $id = false, $version = false )
    {
        if ( is_array( $events ) )
        {
            for ( $i = 0; $i < count( $events ); ++$i )
            {
                $event =& $events[$i];
                $event->remove();
            }
        }
        else
        {
            if ( $version === false )
                $version = $this->Version;
            if ( $id === false )
                $id = $this->ID;
            eZPersistentObject::removeObject( eZWorkflowEvent::definition(),
                                              array( "workflow_id" => $id,
                                                     "version" => $version ) );
        }
    }

    function adjustEventPlacements( &$events )
    {
        if ( !is_array( $events ) )
            return;
        for ( $i = 0; $i < count( $events ); ++$i )
        {
            $event =& $events[$i];
            $event->setAttribute( "placement", $i + 1 );
        }
    }

    function store( $store_childs = false )
    {
        if ( is_array( $store_childs ) or $store_childs )
        {
            if ( is_array( $store_childs ) )
                $events =& $store_childs;
            else
                $events =& $this->fetchEvents();
            for ( $i = 0; $i < count( $events ); ++$i )
            {
                $event =& $events[$i];
                $event->store();
            }
        }
        eZPersistentObject::store();
    }

    function setVersion( $version, $set_childs = false )
    {
        if ( is_array( $set_childs ) or $set_childs )
        {
            if ( is_array( $set_childs ) )
                $events =& $set_childs;
            else
                $events =& $this->fetchEvents();
            for ( $i = 0; $i < count( $events ); ++$i )
            {
                $event =& $events[$i];
                $event->setAttribute( "version", $version );
            }
        }
        eZPersistentObject::setAttribute( "version", $version );
    }

    function &fetch( $id, $asObject = true, $version = 0 )
    {
//         $workflowDBData =& $GLOBALS["eZWorkflowDBData"];
//         if ( !is_array( $workflowDBData ) )
//             $workflowDBData = array();
//         $row =& $workflowDBData[$id][$version];
//         if ( !is_array( $row ) )
//              $row = eZPersistentObject::fetchObject( eZWorkflow::definition(),
//                                                      null,
//                                                      array( "id" => $id,
//                                                             "version" => $version ),
//                                                      false );
//         if ( $asObject )
//             return new eZWorkflow( $row );
//         return $row;
        return eZPersistentObject::fetchObject( eZWorkflow::definition(),
                                                null,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    /*!
      \static
      Fetch workflows based on module, function and connection type

      \param module name
      \param function name
      \param connect type

      \returns array of allowed workflows limited by trigger
    */
    function &fetchLimited( $moduleName, $functionName, $connectType )
    {
        $workflowArray =& eZWorkflow::fetchList();
        $returnArray = array();

        foreach ( array_keys( $workflowArray ) as $key )
        {
            if ( $workflowArray[$key]->isAllowed( $moduleName,
                                                  $functionName,
                                                  $connectType ) )
            {
                $returnArray[] = $workflowArray[$key];
            }
        }

        return $returnArray;
    }

    /*!
      Check if a trigger specified trigger is allowed to use with this workflow

      \param module name
      \param function name
      \param connect type

      \return true if allowed, false if not.
    */
    function isAllowed( $moduleName, $functionName, $connectType )
    {
        $eventArray =& $this->fetchEvents();

        foreach ( array_keys( $eventArray ) as $key )
        {
            $eventType =& $eventArray[$key]->attribute( 'workflow_type' );
            if ( !$eventType->isAllowed( $moduleName, $functionName, $connectType ) )
            {
                return false;
            }
        }

        return true;
    }

    function &fetchList( $version = 0, $enabled = 1, $asObject = true )
    {
        $conds = array( 'version' => $version );
        if ( $enabled !== null )
            $conds['is_enabled'] = $enabled;
        return eZPersistentObject::fetchObjectList( eZWorkflow::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    function &fetchListCount( $version = 0, $enabled = 1 )
    {
        $custom = array( array( "name" => "count",
                                "operation" => "count( id )" ) );
        $list =& eZPersistentObject::fetchObjectList( eZWorkflow::definition(),
                                                      array(), array( "version" => $version,
                                                                      "is_enabled" => $enabled ), array(), null,
                                                      false, null,
                                                      $custom );
        return $list[0]["count"];
    }

    function fetchEventIndexed( $index )
    {
        $id = $this->ID;
        eZDebugSetting::writeDebug( 'workflow-event', $index, 'index in fetchEventIndexed' );
        $list =& eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                      array( "id", "placement" ),
                                                      array( "workflow_id" => $id,
                                                             "version" => 0 ),
                                                      array( "placement" => "asc" ),
                                                      array( "offset" => $index - 1,
                                                             "length" => 1 ),
                                                      false, null,
                                                      null );
//        if ( count( $list ) > 0 )
//            return $list[$index - 1]["id"];
        eZDebugSetting::writeDebug( 'workflow-event', $list, "event indexed" );
        if ( count( $list ) > 0 )
            return $list[$index - 1]["id"];
        return null;
    }

    function &fetchEvents( $id = false, $asObject = true, $version = 0 )
    {
        if ( $id === false )
        {
            if ( isset( $this ) and
                 get_class( $this ) == "ezworkflow" )
            {
                $id = $this->ID;
                $version = $this->Version;
            }
            else
                return null;
        }
        return eZWorkflowEvent::fetchFilteredList( array( "workflow_id" => $id,
                                                          "version" => $version ) );
    }

    function &fetchEventCount( $id = false, $version = 0 )
    {
        if ( $id === false )
        {
            if ( isset( $this ) and
                 get_class( $this ) == "ezworkflow" )
            {
                $id = $this->ID;
                $version = $this->Version;
            }
            else
                return null;
        }
        $custom = array( array( "name" => "count",
                                "operation" => "count( id )" ) );
        $list =& eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                      array(), array( "version" => $version,
                                                                      "workflow_id" => $id ),
                                                      array(), null,
                                                      false, null,
                                                      $custom );
        return $list[0]["count"];
    }

    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(),
                            array( "workflow_type",
                                   "version_status",
                                   "version_count",
                                   "event_count",
                                   "ordered_event_list",
                                   "creator",
                                   "modifier",
                                   "ingroup_list",
                                   "group_list" ) );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == "version_status" or $attr == "version_count" or
                 $attr == "creator" or $attr == "modifier" or $attr == "workflow_type" or
                 $attr == "event_count" or $attr == "ordered_event_list" or
                 $attr == "ingroup_list" or  $attr == "group_list" or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case "event_count":
            {
                return $this->fetchEventCount();
            } break;
            case "ordered_event_list":
            {
                return $this->fetchEvents();
            } break;
            case "version_count":
            {
                return $this->VersionCount;
            } break;
            case "version_status":
            {
                return $this->versionStatus();
            } break;
            case "creator":
            {
                $user_id = $this->CreatorID;
            } break;
            case "modifier":
            {
                $user_id = $this->ModifierID;
            } break;
            case "ingroup_list":
            {
                $this->InGroups =& eZWorkflowGroupLink::fetchGroupList( $this->attribute("id"),
                                                                        $this->attribute("version"),
                                                                        $asObject = true );
                return $this->InGroups;
            } break;
            case "group_list":
            {
                $this->AllGroups =& eZWorkflowGroup::fetchList();
                return $this->AllGroups;
            } break;
            case "workflow_type":
            {
                return $this->workflowType();
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::fetch( $user_id );
        return $user;
    }

    function &workflowType()
    {
        include_once( "kernel/classes/ezworkflowtype.php" );
        return eZWorkflowType::createType( $this->WorkflowTypeString );
    }

    /// \privatesection
    var $ID;
    var $Name;
    var $WorkflowTypeString;
    var $Version;
    var $IsEnabled;
    var $CreatorID;
    var $ModifierID;
    var $Created;
    var $Modified;
    var $InGroups;
    var $AllGroups;
}

?>
