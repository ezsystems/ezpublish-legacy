<?php
/**
 * File containing the eZNotificationEventType class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

    function initializeEvent( $event, $params )
    {
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    static function create( $notificationEventTypeString )
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

            if ( !is_object( $def ) || strtolower( get_class( $def ) ) != $className )
            {
                $def = new $className();
            }
        }
        return $def;
    }


    function attributes()
    {
        return array_merge( array( 'description' ),
                            array_keys( $this->Attributes ) );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == "description" )
        {
            return  $this->eventDescription();
        }
        if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->Attributes[$attr];
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function eventDescription()
    {
        return $this->Attributes["name"];
    }

    function execute( $event )
    {
    }

    function eventContent( $event )
    {
        return "";
    }

    static function allowedTypes()
    {
        $allowedTypes = $GLOBALS["eZNotificationEventTypeAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $notificationINI = eZINI::instance( 'notification.ini' );
            $eventTypes = $notificationINI->variable( 'NotificationEventTypeSettings', 'AvailableEventTypes' );
            $allowedTypes = array_unique( $eventTypes );
        }
        return $allowedTypes;
    }

    static function loadAndRegisterAllTypes()
    {
        $allowedTypes = eZNotificationEventType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZNotificationEventType::loadAndRegisterType( $type );
        }
    }

    static function loadAndRegisterType( $type )
    {
        $types = $GLOBALS["eZNotificationEventTypes"];
        if ( isset( $types[$type] ) )
        {
            eZDebug::writeError( "Notification event type already registered: $type", __METHOD__ );
            return false;
        }

        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
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
            eZDebug::writeError( "Notification event type not found: $type, searched in these directories: " . implode( ', ', $repositoryDirectories ), __METHOD__ );
            return false;
        }
        include_once( $includeFile );
        return true;
    }

    static function register( $notificationTypeString, $className )
    {
        if ( !isset( $GLOBALS["eZNotificationEventTypes"] ) || !is_array( $GLOBALS["eZNotificationEventTypes"] ) )
        {
            $types = array();
        }
        $GLOBALS["eZNotificationEventTypes"][$notificationTypeString] = $className;
    }




}

?>
