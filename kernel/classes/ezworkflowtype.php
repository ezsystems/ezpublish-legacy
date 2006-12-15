<?php
//
// Definition of eZWorkflowType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

//!! eZKernel
//! The class eZWorkflowType does
/*!

*/

include_once( "kernel/classes/ezworkflow.php" );
include_once( "kernel/common/i18n.php" );
include_once( "lib/ezutils/classes/ezdebug.php" );

define( "EZ_WORKFLOW_TYPE_STATUS_NONE", 0 );
define( "EZ_WORKFLOW_TYPE_STATUS_ACCEPTED", 1 );
define( "EZ_WORKFLOW_TYPE_STATUS_REJECTED", 2 );
define( "EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON", 3 );
define( "EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT", 4 );
define( "EZ_WORKFLOW_TYPE_STATUS_RUN_SUB_EVENT", 5 );
define( "EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED", 6 );
define( "EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE", 7 );
define( "EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT", 8 );
define( "EZ_WORKFLOW_TYPE_STATUS_REDIRECT", 10 );
define( "EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE", 9 );
define( "EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT", 11 );
define( "EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET", 12 );

class eZWorkflowType
{
    function eZWorkflowType( $group, $type,
                             $groupName, $name )
    {
        $this->Group = $group;
        $this->Type = $type;
        $this->TypeString = $group . "_" . $type;
        $this->GroupName = $groupName;
        $this->Name = $name;
        $this->Information = "";
        $this->ActivationDate = false;
        $this->Attributes = array();
        $this->Attributes["group"] =& $this->Group;
        $this->Attributes["type"] =& $this->Type;
        $this->Attributes["type_string"] =& $this->TypeString;
        $this->Attributes["group_name"] =& $this->GroupName;
        $this->Attributes["name"] =& $this->Name;
        $this->Attributes["information"] =& $this->Information;
        $this->Attributes["activation_date"] =& $this->ActivationDate;
    }

    function statusName( $status )
    {
        include_once( 'kernel/workflow/ezworkflowfunctioncollection.php' );
        $statusNames = eZWorkflowFunctionCollection::fetchWorkflowTypeStatuses();
        if ( isset( $statusNames[$status] ) )
            return $statusNames[$status];
        return false;
    }

    function &createType( $typeString )
    {
        $types =& $GLOBALS["eZWorkflowTypes"];
        $def = null;
        if ( !isset( $types[$typeString] ) )
        {
            $result = eZWorkflowType::loadAndRegisterType( $typeString );
            if ( $result === false )
                return $def;
        }

        if ( isset( $types[$typeString] ) )
        {
            $type_def =& $types[$typeString];
            $class_name = $type_def["class_name"];

            $def =& $GLOBALS["eZWorkflowTypeObjects"][$typeString];
            if ( get_class( $def ) != $class_name )
            {
                if ( class_exists( $class_name ) )
                    $def = new $class_name();
                else
                    eZDebug::writeError( "Undefined event type class: $class_name", "eZWorkflowType::createType" );
            }
        }
        else
            eZDebug::writeError( "Undefined type: $typeString", "eZWorkflowType::createType" );
        return $def;
    }

    function &fetchRegisteredTypes()
    {
        eZWorkflowType::loadAndRegisterAllTypes();
        $definition_objects =& $GLOBALS["eZWorkflowTypeObjects"];
        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( is_array( $types ) )
        {
            foreach ( $types as $typeString => $type_def )
            {
                $class_name = $type_def["class_name"];
                $def =& $definition_objects[$typeString];
                if ( get_class( $def ) != $class_name )
                {
                    if ( class_exists( $class_name ) )
                        $def = new $class_name();
                    else
                        eZDebug::writeError( "Undefined event type class: $class_name", "eZWorkflowType::fetchRegisteredTypes" );
                }
            }
        }
        return $definition_objects;
    }

    function allowedTypes()
    {
        $allowedTypes =& $GLOBALS["eZWorkflowAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $wfINI =& eZINI::instance( 'workflow.ini' );
            $eventTypes = $wfINI->variable( "EventSettings", "AvailableEventTypes" );
            // $availableTypes = $wfINI->variableArray( "EventSettings", "AvailableWorkflowTypes" );
            // $allowedTypes = array_unique( array_merge( $eventTypes, $availableTypes ) );
            $allowedTypes = array_unique( $eventTypes );
        }
        return $allowedTypes;
    }

    function loadAndRegisterAllTypes()
    {
        $allowedTypes = eZWorkflowType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZWorkflowType::loadAndRegisterType( $type );
        }
    }

    function registerType( $group, $type, $class_name )
    {
        $typeString = $group . "_" . $type;
        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( !is_array( $types ) )
            $types = array();
        if ( isset( $types[$typeString] ) )
        {
            eZDebug::writeError( "Type already registered: $typeString", "eZWorkflowType::registerType" );
        }
        else
        {
            $types[$typeString] = array( "class_name" => $class_name );
        }
    }

    function loadAndRegisterType( $typeString )
    {
        $typeElements = explode( "_", $typeString );
        if ( count( $typeElements ) < 2 )
        {
            eZDebug::writeError( "Workflow type not found: $typeString", "eZWorkflowType::loadAndRegisterType" );
            return false;
        }

        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( isset( $types[ $typeString ] ) and
             isset( $types[ $typeString ][ 'class_name' ] ) and
             class_exists( $types[ $typeString ][ 'class_name' ] ) )
        {
            return true;
        }

        $group = $typeElements[0];
        $type = $typeElements[1];

        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $wfINI =& eZINI::instance( 'workflow.ini' );
        $repositoryDirectories = $wfINI->variable( 'EventSettings', 'RepositoryDirectories' );
        $extensionDirectories = $wfINI->variable( 'EventSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/eventtypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$group/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                include_once( $includeFile );
                return true;
            }
        }

        eZDebug::writeError( "Workflow type not found: $typeString, searched in these directories: " . implode( ', ', $repositoryDirectories ), "eZWorkflowType::loadAndRegisterType" );
        return false;
    }


    function attributes()
    {
        return array_merge( array( 'description',
                                   'allowed_triggers' ),
                            array_keys( $this->Attributes ) );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'description':
            {
                return $this->eventDescription();
            } break;

            case 'allowed_triggers':
            {
                return $this->TriggerTypes;
            } break;

            default:
            {
                if ( isset( $this->Attributes[$attr] ) )
                    return $this->Attributes[$attr];
            } break;
        }
        eZDebug::writeError( "Attribute '$attr' does not exist", 'eZWorkflowType::attribute' );
        $retValue = null;
        return $retValue;
    }

    function setAttribute( $attr, $value )
    {
        if ( array_key_exists( $attr, $this->Attributes ) )
            $this->Attributes[$attr] = $value;
    }

    /*!
     Set trigger types.

     \param allowed trigger types format :
              array( <module> => array( <function> => array( <event> ) ) )
            if all is allowed,
              array( '*' => true )
     */
    function setTriggerTypes( $allowedTypes )
    {
        $this->TriggerTypes = $allowedTypes;
    }

    function &eventDescription()
    {
        return $this->Attributes["name"];
    }

    function execute( &$process, &$event )
    {
        return EZ_WORKFLOW_TYPE_STATUS_NONE;
    }

    function initializeEvent( &$event )
    {
    }

    function validateHTTPInput( &$http, $base, &$event, &$validation )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    function fixupHTTPInput( &$http, $base, &$event )
    {
        return true;
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
    }

    function setActivationDate( $date )
    {
        $this->ActivationDate = $date;
    }

    function setInformation( $inf )
    {
        $this->Information = $inf;
    }

    function needCleanup()
    {
        return false;
    }

    function cleanupAfterRemoving( $attr = array() )
    {
    }

    function cleanup( &$process, &$event )
    {
    }

    function &attributeDecoder( &$event, $attr )
    {
        $retValue = null;
        return $retValue;
    }

    function typeFunctionalAttributes( )
    {
        return array();
    }

    function customWorkflowEventHTTPAction( &$http, $action, &$workflowEvent )
    {
    }

    function &workflowEventContent()
    {
        $retValue = "";
        return $retValue;
    }
    function storeEventData( &$event, $version )
    {
    }

    /*!
     Check if specified trigger is allowed

      \param module name
      \param function name
      \param connect type

     \return true is allowed, false if not.
    */
    function isAllowed( $moduleName, $functionName, $connectType )
    {
        if ( isset( $this->TriggerTypes['*'] ) )
        {
            return true;
        }
        else if ( isset( $this->TriggerTypes[$moduleName] ) )
        {
            if ( isset( $this->TriggerTypes[$moduleName][$functionName] ) )
            {
                if ( in_array( $connectType, $this->TriggerTypes[$moduleName][$functionName] ) )
                {
                    return true;
                }
            }
        }

        return false;
    }

    /// \privatesection
    var $Group;
    var $Type;
    var $TypeString;
    var $GroupName;
    var $Name;
    var $ActivationDate;
    var $Information;
    var $TriggerTypes = array( '*' => true );
}

class eZWorkflowEventType extends eZWorkflowType
{
    function eZWorkflowEventType( $typeString, $name )
    {
        $this->eZWorkflowType( "event", $typeString, ezi18n( 'kernel/workflow/event', "Event" ), $name );
    }

    function registerType( $typeString, $class_name )
    {
        eZWorkflowType::registerType( "event", $typeString, $class_name );
    }
}

class eZWorkflowGroupType extends eZWorkflowType
{
    function eZWorkflowGroupType( $typeString, $name )
    {
        $this->eZWorkflowType( "group", $typeString, ezi18n( 'kernel/workflow/group', "Group" ), $name );
    }

    function registerType( $typeString, $class_name )
    {
        eZWorkflowType::registerType( "group", $typeString, $class_name );
    }
}

?>
