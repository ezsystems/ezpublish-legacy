<?php
//
// Definition of eZCollaborationItemHandler class
//
// Created on: <22-Jan-2003 16:24:33 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZCollaborationItemHandler ezcollaborationitemhandler.php
  \brief The class eZCollaborationItemHandler does

*/

class eZCollaborationItemHandler
{
    /*
     Definitions for notification handling for collaboration handlers.
    */
    const NOTIFICATION_COLLECTION_ONE_FOR_ALL = 1;
    const NOTIFICATION_COLLECTION_PER_USER = 2;
    const NOTIFICATION_COLLECTION_PER_PARTICIPATION_ROLE = 3;

    /*!
     Initializes the handler with identifier and name.
     Optional parameters can be placed in \a $parameters.
    */
    function eZCollaborationItemHandler( $typeIdentifier, $typeName, $parameters = array() )
    {
        $parameters = array_merge( array( 'use-messages' => false,
                                          'type-class-list' => array(),
                                          'notification-collection-handling' => self::NOTIFICATION_COLLECTION_ONE_FOR_ALL,
                                          'notification-types' => false ),
                                   $parameters );
        $typeClassList = $parameters['type-class-list'];
        $this->Info['type-identifier'] = $typeIdentifier;
        $this->Info['type-class-list'] = $typeClassList;
        $this->Info['type-name'] = $typeName;
        $this->Info['use-messages'] = $parameters['use-messages'];
        $this->Info['notification-collection-handling'] = $parameters['notification-collection-handling'];
        $this->Info['notification-types'] = $parameters['notification-types'];
        $this->NotificationCollectionHandling = $parameters['notification-collection-handling'];
        $this->NotificationTypes = $parameters['notification-types'];
    }

    function attributes()
    {
        return array( 'info',
                      'notification_types' );
    }

    /*!
     \return true if the attribute \a $attribute exists.
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \return the attribute \a $attribute if it exists or \c null.
    */
    function attribute( $attribute )
    {
        if ( $attribute == 'info' )
        {
            return $this->Info;
        }
        else if ( $attribute == 'notification_types' )
        {
            return $this->notificationTypes();
        }

        eZDebug::writeError( "Attribute '$attribute' does not exist", 'eZCollaborationItemHandler::attribute' );
        return null;
    }

    /*!
      \return what kind of notification types this handler supports. Can either return an array or a boolean.
      If it returns \c true the handler supports notification but does not have subnotifications.
      If it returns \c false the handler does not support notificiation.
      If it returns an array the array contains a list associative arrays each containing a \c name and \c value entry.
    */
    function notificationTypes()
    {
        return $this->NotificationTypes;
    }

    /*!
     \return how the handler wants collections to be made.
     \note The default is to create one collection for all participants.
    */
    function notificationCollectionHandling()
    {
        return $this->NotificationCollectionHandling;
    }

    function notificationParticipantTemplate( $participantRole )
    {
        return 'participant.tpl';
    }

    /*!
     \static
     Handles a notification event for collaboration items.
     \note The default implementation sends out a generic email.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function handleCollaborationEvent( $event, $item, &$parameters )
    {
        $participantList = eZCollaborationItemParticipantLink::fetchParticipantList( array( 'item_id' => $item->attribute( 'id' ),
                                                                                             'participant_type' => eZCollaborationItemParticipantLink::TYPE_USER,
                                                                                             'as_object' => false ) );

        $userIDList = array();
        $participantMap = array();
        foreach ( $participantList as $participant )
        {
            $userIDList[] = $participant['participant_id'];
            $participantMap[$participant['participant_id']] = $participant;
        }

//         $collaborationIdentifier = $event->attribute( 'collaboration_identifier' );
        $collaborationIdentifier = $event->attribute( 'data_text1' );
        $ruleList = eZCollaborationNotificationRule::fetchItemTypeList( $collaborationIdentifier, $userIDList, false );
        $userIDList = array();
        foreach ( $ruleList as $rule )
        {
            $userIDList[] = $rule['user_id'];
        }
        $userList = array();
        if ( count( $userIDList ) > 0 )
        {
            $db = eZDB::instance();
            $userIDListText = implode( "', '", $userIDList );
            $userIDListText = "'$userIDListText'";
            $userList = $db->arrayQuery( "SELECT contentobject_id, email FROM ezuser WHERE contentobject_id IN ( $userIDListText )" );
        }
        else
            return eZNotificationEventHandler::EVENT_SKIPPED;

        $itemHandler = $item->attribute( 'handler' );
        $collectionHandling = $itemHandler->notificationCollectionHandling();

        $db = eZDB::instance();
        $db->begin();
        if ( $collectionHandling == self::NOTIFICATION_COLLECTION_ONE_FOR_ALL )
        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();
            $tpl->resetVariables();
            $tpl->setVariable( 'collaboration_item', $item );
            $result = $tpl->fetch( 'design:notification/handler/ezcollaboration/view/plain.tpl' );
            $subject = $tpl->variable( 'subject' );
            if ( $tpl->hasVariable( 'message_id' ) )
                $parameters['message_id'] = $tpl->variable( 'message_id' );
            if ( $tpl->hasVariable( 'references' ) )
                $parameters['references'] = $tpl->variable( 'references' );
            if ( $tpl->hasVariable( 'reply_to' ) )
                $parameters['reply_to'] = $tpl->variable( 'reply_to' );
            if ( $tpl->hasVariable( 'from' ) )
                $parameters['from'] = $tpl->variable( 'from' );
            if ( $tpl->hasVariable( 'content_type' ) )
                $parameters['content_type'] = $tpl->variable( 'content_type' );

            $collection = eZNotificationCollection::create( $event->attribute( 'id' ),
                                                            eZCollaborationNotificationHandler::NOTIFICATION_HANDLER_ID,
                                                            eZCollaborationNotificationHandler::TRANSPORT );

            $collection->setAttribute( 'data_subject', $subject );
            $collection->setAttribute( 'data_text', $result );
            $collection->store();

            foreach( $userList as $subscriber )
            {
                $collection->addItem( $subscriber['email'] );
            }
        }
        else if ( $collectionHandling == self::NOTIFICATION_COLLECTION_PER_PARTICIPATION_ROLE )
        {
            $userCollection = array();
            foreach( $userList as $subscriber )
            {
                $contentObjectID = $subscriber['contentobject_id'];
                $participant = $participantMap[$contentObjectID];
                $participantRole = $participant['participant_role'];
                $userItem = array( 'participant' => $participant,
                                   'email' => $subscriber['email'] );
                if ( !isset( $userCollection[$participantRole] ) )
                    $userCollection[$participantRole] = array();
                $userCollection[$participantRole][] = $userItem;
            }

            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();
            $tpl->resetVariables();
            foreach( $userCollection as $participantRole => $collectionItems )
            {
                $templateName = $itemHandler->notificationParticipantTemplate( $participantRole );
                if ( !$templateName )
                    $templateName = eZCollaborationItemHandler::notificationParticipantTemplate( $participantRole );

                $itemInfo = $itemHandler->attribute( 'info' );
                $typeIdentifier = $itemInfo['type-identifier'];
                $tpl->setVariable( 'collaboration_item', $item );
                $tpl->setVariable( 'collaboration_participant_role', $participantRole );
                $result = $tpl->fetch( 'design:notification/handler/ezcollaboration/view/' . $typeIdentifier . '/' . $templateName );
                $subject = $tpl->variable( 'subject' );
                if ( $tpl->hasVariable( 'message_id' ) )
                    $parameters['message_id'] = $tpl->variable( 'message_id' );
                if ( $tpl->hasVariable( 'references' ) )
                    $parameters['references'] = $tpl->variable( 'references' );
                if ( $tpl->hasVariable( 'reply_to' ) )
                    $parameters['reply_to'] = $tpl->variable( 'reply_to' );
                if ( $tpl->hasVariable( 'from' ) )
                    $parameters['from'] = $tpl->variable( 'from' );
                if ( $tpl->hasVariable( 'content_type' ) )
                    $parameters['content_type'] = $tpl->variable( 'content_type' );

                $collection = eZNotificationCollection::create( $event->attribute( 'id' ),
                                                                eZCollaborationNotificationHandler::NOTIFICATION_HANDLER_ID,
                                                                eZCollaborationNotificationHandler::TRANSPORT );

                $collection->setAttribute( 'data_subject', $subject );
                $collection->setAttribute( 'data_text', $result );
                $collection->store();
                foreach ( $collectionItems as $collectionItem )
                {
                    $collection->addItem( $collectionItem['email'] );
                }
            }
        }
        else if ( $collectionHandling == self::NOTIFICATION_COLLECTION_PER_USER )
        {
        }
        else
        {
            eZDebug::writeError( "Unknown collaboration notification collection handling type '$collectionHandling', skipping notification",
                                 'eZCollaborationItemHandler::handleCollaborationEvent' );
        }
        $db->commit();

        return eZNotificationEventHandler::EVENT_HANDLED;
    }

    /*!
     \return true if the attribute \a $attribute exists in the content data.
    */
    function hasContentAttribute( $collaborationItem, $attribute )
    {
        $content = $collaborationItem->content();
        if ( is_array( $content ) )
        {
            return array_key_exists( $attribute, $content );
        }
        return false;
    }

    /*!
     \return the attribute \a $attribute if it exists in the content data or \c null.
    */
    function contentAttribute( $collaborationItem, $attribute )
    {
        $content = $collaborationItem->content();
        if ( is_array( $content ) )
        {
            if ( array_key_exists( $attribute, $content ) )
                return $content[$attribute];
        }
        $content = null;
        return $content;
    }

    /*!
     \return a list of classes this handler supports.
    */
    function classes()
    {
        return $this->Info['type-class-list'];
    }

    /*!
     \return the template name for the viewmode \a $viewmode.
    */
    function template( $viewMode )
    {
        $templateName = $this->templateName();
        return "design:collaboration/handlers/view/$viewMode/$templateName";
    }

    /*!
     \return the name of the template file for this handler.
     Default is to append .tpl to the identifier.
    */
    function templateName()
    {
        return $this->Info['type-identifier'] . '.tpl';
    }

    /*!
     \return the title of the collaboration item.
    */
    function title( $collaborationItem )
    {
        return $this->Info['type-name'];
    }

    /*!
     \return true if the collaboration item \a $collaborationItem supports messages.
     \note The handler can either determine this by passing \a $useMessages to the constructor
           or by reimplementing this function to do it per item.
    */
    function useMessages( $collaborationItem )
    {
        return $this->Info['use-messages'];
    }

    /*!
     \return the number of messages for the collaboration item \a $collaborationItem.
     \note The default implementation returns 0, if you want real counts
           the handler must reimplement this function.
    */
    function messageCount( $collaborationItem )
    {
        return 0;
    }

    /*!
     \return the number of unread messages for the collaboration item \a $collaborationItem.
     \note The default implementation returns 0, if you want real counts
           the handler must reimplement this function.
    */
    function unreadMessageCount( $collaborationItem )
    {
        return 0;
    }

    /*!
     This is called whenever the item is considered to be read,
     it can be used by handlers to update when the item was last read.
     \note Default implementation does nothing.
    */
    function readItem( $collaborationItem, $viewMode = false )
    {
    }

    /*!
     This is called whenever a collaboration item is to be removed.
     Reimplementing this function can be used to cleanup external tables
     or other resources.
    */
    function removeItem( $collaborationItem )
    {
    }

    /*!
     \static
     \return the ini object which handles collaboration settings.
    */
    static function ini()
    {
        return eZINI::instance( 'collaboration.ini' );
    }

    /*!
     \return a textual representation of the participant type id \a $participantType
     \note It's up to the real handlers to implement this if they use custom participation types.
    */
    function participantTypeString( $participantType )
    {
        return null;
    }

    /*!
     \return a textual representation of the participant role id \a $participantRole
     \note It's up to the real handlers to implement this if they use custom participation roles.
    */
    function participantRoleString( $participantRole )
    {
        return null;
    }

    /*!
     \return a description of the role id \a $roleID in the current language.
     \note It's up to the real handlers to implement this if they use custom participation roles.
    */
    function roleName( $collaborationID, $roleID )
    {
        return null;
    }

    /*!
     \return the content of the collaborationitem.
     \note This is specific to the item type, some might return an array and others an object.
    */
    function content( $collaborationItem )
    {
        return null;
    }

    /*!
     This function is called when a custom action is executed for a specific collaboration item.
     The module object is available in \a $module and the item in \a $collaborationItem.
     \note The default does nothing, the function must be reimplemented in real handlers.
     \sa isCustomAction
    */
    function handleCustomAction( $module, $collaborationItem )
    {
    }

    /*!
     \return true if the current custom action is \a $name.
    */
    function isCustomAction( $name )
    {
        $http = eZHTTPTool::instance();
        $postVariable = 'CollaborationAction_' . $name;
        return $http->hasPostVariable( $postVariable );
    }

    /*!
     \return true if the custom input variable \a $name exists.
    */
    function hasCustomInput( $name )
    {
        $http = eZHTTPTool::instance();
        $postVariable = 'Collaboration_' . $name;
        return $http->hasPostVariable( $postVariable );
    }

    /*!
     \return value of the custom input variable \a $name.
    */
    function customInput( $name )
    {
        $http = eZHTTPTool::instance();
        $postVariable = 'Collaboration_' . $name;
        return $http->postVariable( $postVariable );
    }

    /*!
     \static
     \return an array with directories which acts as default collaboration repositories.
     \sa handlerRepositories
    */
    static function defaultRepositories()
    {
        $collabINI = eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Repositories' );
    }

    /*!
     \static
     \return an array with directories which acts as collaboration extension repositories.
     \sa handlerRepositories
    */
    static function extensionRepositories()
    {
        $collabINI = eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Extensions' );
    }

    /*!
     \static
     \return an array with directories which acts as collaboration repositories.
     \sa defaultRepositories, extensionRepositories
    */
    static function handlerRepositories()
    {
        $extensions = eZCollaborationItemHandler::extensionRepositories();
        $repositories = eZCollaborationItemHandler::defaultRepositories();
        $extensionRoot = eZExtension::baseDirectory();
        foreach ( $extensions as $extension )
        {
            $handlerPath = eZDir::path( array( $extensionRoot, $extension, 'collaboration' ) );
            if ( file_exists( $handlerPath ) )
                $repositories[] = $handlerPath;
        }
        return $repositories;
    }

    /*!
     \static
     \return an array with handler identifiers that are considered active.
    */
    static function activeHandlers()
    {
        $collabINI = eZCollaborationItemHandler::ini();
        return $collabINI->variable( 'HandlerSettings', 'Active' );
    }

    /*!
     \static
     \return a unique instance of the handler for the identifier \a $handler.
     If \a $repositories is left out it will use the handlerRepositories.
    */
    static function instantiate( $handler, $repositories = false )
    {
        $objectCache =& $GLOBALS["eZCollaborationHandlerObjectCache"];
        if ( !isset( $objectCache ) )
            $objectCache = array();
        if ( isset( $objectCache[$handler] ) )
            return $objectCache[$handler];
        if ( $repositories === false )
        {
            $repositories = eZCollaborationItemHandler::handlerRepositories();
        }
        $handlerInstance = null;
        $foundHandlerFile = false;
        $foundHandler = false;
        foreach ( $repositories as $repository )
        {
            $handlerFile = $handler . 'collaborationhandler.php';
            $handlerClass = $handler . 'collaborationhandler';
            $handlerPath = eZDir::path( array( $repository, $handler, $handlerFile ) );
            if ( file_exists( $handlerPath ) )
            {
                $foundHandlerFile = true;
                include_once( $handlerPath );
                if ( class_exists( $handlerClass ) )
                {
                    $foundHandler = true;
                    $handlerInstance = new $handlerClass();
                    $objectCache[$handler] = $handlerInstance;
                    $handlerClasses = $handlerInstance->classes();
                    foreach ( $handlerClasses as $handlerClass )
                    {
                    }
                }
            }
        }
        if ( !$foundHandlerFile )
        {
            eZDebug::writeWarning( "Collaboration file '$handlerFile' could not be found in " . implode( ', ', $repositories ), 'eZCollaborationItemHandler::fetchList' );
        }
        else if ( !$foundHandler )
        {
            eZDebug::writeWarning( "Collaboration class '$handlerClass' does not exist", 'eZCollaborationItemHandler::fetchList' );
        }
        return $handlerInstance;
    }

    /*!
     \static
     \return a list of collaboration handler objects.
     \sa instantiate, activeHandlers
    */
    static function fetchList()
    {
        $list =& $GLOBALS['eZCollaborationList'];
        if ( isset( $list ) )
            return $list;
        $list = array();
        $activeHandlers = eZCollaborationItemHandler::activeHandlers();
        $repositories = eZCollaborationItemHandler::handlerRepositories();
        foreach ( $activeHandlers as $handler )
        {
            $handlerInstance = eZCollaborationItemHandler::instantiate( $handler, $repositories );
            if ( $handlerInstance !== null )
                $list[] = $handlerInstance;
        }
        return $list;
    }

    /// \privatesection
    public $Info;
}

?>
