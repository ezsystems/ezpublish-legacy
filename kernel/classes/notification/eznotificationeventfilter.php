<?php
/**
 * File containing the eZNotificationEventFilter class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNotificationEventFilter eznotificationeventfilter.php
  \brief The class eZNotificationEventFilter does

*/
class eZNotificationEventFilter
{
    /*!
     Constructor
    */
    function eZNotificationEventFilter()
    {
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function process()
    {
        $limit = 100;
        $offset = 0;
        $availableHandlers = eZNotificationEventFilter::availableHandlers();
        do
        {
            $eventList = eZNotificationEvent::fetchUnhandledList( array( 'offset' => $offset, 'length' => $limit ) );
            foreach( $eventList as $event )
            {
                $db = eZDB::instance();
                $db->begin();

                foreach( $availableHandlers as $handler )
                {
                    if ( $handler === false )
                    {
                        eZDebug::writeError( "Notification handler does not exist: $handlerKey", __METHOD__ );
                    }
                    else
                    {
                        $handler->handle( $event );
                    }
                }
                $itemCountLeft = eZNotificationCollectionItem::fetchCountForEvent( $event->attribute( 'id' ) );
                if ( $itemCountLeft == 0 )
                {
                    $event->remove();
                }
                else
                {
                    $event->setAttribute( 'status', eZNotificationEvent::STATUS_HANDLED );
                    $event->store();
                }

                $db->commit();
            }
            eZContentObject::clearCache();
        } while ( count( $eventList ) == $limit ); // If less than limit, we're on the last iteration

        eZNotificationCollection::removeEmpty();
    }

    static function availableHandlers()
    {
        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
        $availableHandlers = $notificationINI->variable( 'NotificationEventHandlerSettings', 'AvailableNotificationEventTypes' );
        $repositoryDirectories = array();
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/notification/handler';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $handlers = array();
        foreach( $availableHandlers as $handlerString )
        {
            $eventHandler = eZNotificationEventFilter::loadHandler( $repositoryDirectories, $handlerString );
            if ( is_object( $eventHandler ) )
                $handlers[$handlerString] = $eventHandler;
        }
        return $handlers;
    }

    static function loadHandler( $directories, $handlerString )
    {
        $foundHandler = false;
        $includeFile = '';

        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
        $repositoryDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'RepositoryDirectories' );
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = "{$baseDirectory}/{$extensionDirectory}/notification/handler/";
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $repositoryDirectory = trim( $repositoryDirectory, '/' );
            $includeFile = "{$repositoryDirectory}/{$handlerString}/{$handlerString}handler.php";
            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }
        if ( !$foundHandler  )
        {
            eZDebug::writeError( "Notification handler does not exist: $handlerString", __METHOD__ );
            return false;
        }
        include_once( $includeFile );
        $className = $handlerString . "handler";
        return new $className();
    }

    /*!
     \static
     Goes through all event handlers and tells them to cleanup.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $availableHandlers = eZNotificationEventFilter::availableHandlers();

        $db = eZDB::instance();
        $db->begin();
        foreach( $availableHandlers as $handler )
        {
            if ( $handler !== false )
            {
                $handler->cleanup();
            }
        }
        $db->commit();
    }
}

?>
