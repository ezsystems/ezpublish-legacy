<?php
//
// Definition of eZNotificationEventType class
//
// Created on: <12-May-2003 09:58:12 sp>
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

/*! \file eznotificationeventtype.php
*/

/*!
  \class eZNotificationEventType eznotificationeventtype.php
  \brief The class eZNotificationEventType does

*/

class eZNotificationEventType
{
    /*!
     Constructor
    */
    function eZNotificationEventType( $notificationEventTypeString )
    {
        $this->NotificationEventTypeString = $notificationEventTypeString;
    }

    function initializeEvent( &$event, $params )
    {
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    function &create( $notificationEventTypeString )
    {
        $types =& $GLOBALS["eZNotificationEventTypes"];
        if( !isset( $types[$notificationEventTypeString] ) )
        {
            eZDebugSetting::writeDebug( 'kernel-notification', $types, 'notification types' );
            eZNotificationEventType::loadAndRegisterType( $notificationEventTypeString );
            eZDebugSetting::writeDebug( 'kernel-notification', $types, 'notification types 2' );
        }
        $def = null;
        if ( isset( $types[$notificationEventTypeString] ) )
        {
            $className = $types[$notificationEventTypeString];
            $def =& $GLOBALS["eZNotificationEventTypeObjects"][$notificationEventTypeString];

            if ( get_class( $def ) != $className )
            {
                $def = new $className();
            }
        }
        return $def;
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

    function &eventDescription()
    {
        return $this->Attributes["name"];
    }

    function execute( &$event )
    {
    }

    function eventContent()
    {
        return "";
    }

    function allowedTypes()
    {
        $allowedTypes =& $GLOBALS["eZNotificationEventTypeAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $notificationINI =& eZINI::instance( 'notification.ini' );
            $eventTypes = $notificationINI->variable( 'NotificationEventTypeSettings', 'AvailableEventTypes' );
            $allowedTypes = array_unique( $eventTypes );
        }
        return $allowedTypes;
    }

    function loadAndRegisterAllTypes()
    {
        $allowedTypes =& eZNotificationEventType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZNotificationEventType::loadAndRegisterType( $type );
        }
    }

    function loadAndRegisterType( $type )
    {
        $types =& $GLOBALS["eZNotificationEventTypes"];
        if ( isset( $types[$type] ) )
        {
            eZDebug::writeError( "Notification event type already registered: $type", "eZNotificationEventType::loadAndRegisterType" );
            return false;
        }

        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI =& eZINI::instance( 'notification.ini' );
        $repositoryDirectories = $notificationINI->variable( 'NotificationEventTypeSettings', 'RepositoryDirectories' );
        $extensionDirectories = $notificationINI->variable( 'NotificationEventTypeSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/notificationtypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $foundEventType = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                $foundEventType = true;
                break;
            }
        }
        if ( !$foundEventType )
        {
            eZDebug::writeError( "Notification event type not found: $type, searched in these directories: " . implode( ', ', $repositoryDirectories ), "eZNotificationEventType::loadAndRegisterType" );
            return false;
        }
        include_once( $includeFile );
        return true;
    }

    function register( $notificationTypeString, $className )
    {
        $types =& $GLOBALS["eZNotificationEventTypes"];
        if ( !is_array( $types ) )
            $types = array();
        $types[$notificationTypeString] = $className;
    }




}

?>
