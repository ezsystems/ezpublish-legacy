<?php
//
// Definition of eZWorkflowType class
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

// include defined datatypes

$wfINI =& eZINI::instance( 'workflow.ini' );
$workflowTypes =& $GLOBALS["eZWorkflowTypes"];
$types = $wfINI->variable( 'EventSettings', 'AvailableEventTypes' );
// eZDebugSetting::writeDebug( 'workflow-type', $types, 'workflow' );

class eZWorkflowType
{
    function eZWorkflowType( $group, $type,
                             $groupName, $name )
    {
        $this->TypeGroup = $group;
        $this->TypeString = $type;
        $this->TypeString = $group . "_" . $type;
        $this->GroupName = $groupName;
        $this->Name = $name;
        $this->Information = "";
        $this->ActivationDate = null;
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
        $statusNames =& $GLOBAL["eZWorkflowTypeStatusNames"];
        if ( !is_array( $statusNames ) )
        {
            $statusNames = array( EZ_WORKFLOW_TYPE_STATUS_NONE => ezi18n( 'kernel/classes', 'No state yet' ),
                                  EZ_WORKFLOW_TYPE_STATUS_ACCEPTED => ezi18n( 'kernel/classes', 'Accepted event' ),
                                  EZ_WORKFLOW_TYPE_STATUS_REJECTED => ezi18n( 'kernel/classes', 'Rejected event' ),
                                  EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON => ezi18n( 'kernel/classes', 'Event deferred to cron job' ),
                                  EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON_REPEAT => ezi18n( 'kernel/classes', 'Event deferred to cron job, event will be rerun' ),
                                  EZ_WORKFLOW_TYPE_STATUS_RUN_SUB_EVENT => ezi18n( 'kernel/classes', 'Event runs a sub event' ),
                                  EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_CANCELLED => ezi18n( 'kernel/classes', 'Cancelled whole workflow' ),
                                  EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_RESET => ezi18n( 'kernel/classes', 'Workflow was reset for reuse' ) );
        }
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
                return null;
        }
        if ( isset( $types[$typeString] ) )
        {
            $type_def =& $types[$typeString];
            $class_name = $type_def["class_name"];

            $def =& $GLOBALS["eZWorkflowTypeObjects"][$typeString];
            if ( get_class( $def ) != $class_name )
            {
//                 eZDebugSetting::writeDebug( 'workflow-type', "Created type: $typeString", "eZWorkflowType::createType" );
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
//                     eZDebugSetting::writeDebug( 'workflow-type', "Created list type: $typeString", "eZWorkflowType::fetchRegisteredTypes" );
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
//            $workflowTypes = $wfINI->variableArray( "EventSettings", "AvailableWorkflowTypes" );
//             $allowedTypes = array_unique( array_merge( $eventTypes, $workflowTypes ) );
            $allowedTypes = array_unique( $eventTypes );
        }
        return $allowedTypes;
    }

    function loadAndRegisterAllTypes()
    {
        $allowedTypes =& eZWorkflowType::allowedTypes();
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
//             eZDebugSetting::writeDebug( 'workflow-type', "Registered type: $typeString", "eZWorkflowType::registerType" );
            $types[$typeString] = array( "class_name" => $class_name );
        }
    }

    function loadAndRegisterType( $typeString )
    {
        $types =& $GLOBALS["eZWorkflowTypes"];
        if ( isset( $types[$typeString] ) )
        {
            eZDebug::writeError( "Workflow type not found: $typeString", "eZWorkflowType::loadAndRegisterType" );
            return null;
        }
        $typeElements = explode( "_", $typeString );
        if ( count( $typeElements ) < 2 )
        {
            eZDebug::writeError( "Workflow type not found: $typeString", "eZWorkflowType::loadAndRegisterType" );
            return null;
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
        $foundEventType = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$group/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                $foundEventType = true;
                break;
            }
        }
        if ( !$foundEventType )
        {
            eZDebug::writeError( "Workflow type not found: $typeString, searched in these directories: " . implode( ', ', $repositoryDirectories ), "eZWorkflowType::loadAndRegisterType" );
            return false;
        }
        include_once( $includeFile );
        return true;
    }


    function &attributes()
    {
        return array_merge( array_keys( $this->Attributes ), "description" );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == "description" or
                 isset( $this->Attributes[$attr] ) );
    }

    function &attribute( $attr )
    {
        if ( $attr == "description" )
            return $this->eventDescription();
        if ( isset( $this->Attributes[$attr] ) )
            return $this->Attributes[$attr];
        else
            return null;
    }

    function setAttribute( $attr, $value )
    {
        if ( array_key_exists( $attr, $this->Attributes ) )
            $this->Attributes[$attr] = $value;
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

    function validateHTTPInput( &$http, $base, &$event )
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

    function cleanup( &$process, &$event )
    {
    }

    function attributeDecoder( &$event, $attr )
    {
    }

    function typeFunctionalAttributes( )
    {
        return array();
    }

    function customWorkflowEventHTTPAction( &$http, $action, &$workflowEvent )
    {
    }

    function workflowEventContent()
    {
        return "";
    }
    function storeEventData( &$event, $version )
    {
    }
    /// \privatesection
    var $Group;
    var $Type;
    var $TypeString;
    var $GroupName;
    var $Name;
    var $ActivationDate;
    var $Information;
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

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'allowed_triggers' ||
                 eZWorkflowType::hasAttribute( $attr ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'allowed_triggers':
            {
                return $this->getTriggerTypes();
            } break;
        }
        return eZWorkflowType::attribute( $attr );
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

    var $TriggerTypes = array( '*' => true );
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
