<?php
/**
 * File containing the eZCollaborationEventType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZCollaborationEventType ezcollaborationeventtype.php
  \brief The class eZCollaborationEventType does

*/

class eZCollaborationEventType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezcollaboration';

    /*!
     Constructor
    */
    function eZCollaborationEventType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type collaboration' );
        $event->setAttribute( 'data_int1', $params['collaboration_id'] );
        $event->setAttribute( 'data_text1', $params['collaboration_identifier'] );
    }

    function attributes()
    {
        return array_merge( array( 'collaboration_identifier',
                                   'collaboration_id' ),
                            eZNotificationEventType::attributes() );
    }

    function hasAttribute( $attributeName )
    {
        return in_array( $attributeName, $this->attributes() );
    }

    function attribute( $attributeName )
    {
        if ( $attributeName == 'collaboration_identifier' )
        {
            return eZNotificationEventType::attribute( 'data_text1' );
        }
        else if ( $attributeName == 'collaboration_id' )
        {
            return eZNotificationEventType::attribute( 'data_int1' );
        }

        return eZNotificationEventType::attribute( $attributeName );
    }

    function eventContent( $event )
    {
        return eZCollaborationItem::fetch( $event->attribute( 'data_int1' ) );
    }
}

eZNotificationEventType::register( eZCollaborationEventType::NOTIFICATION_TYPE_STRING, 'eZCollaborationEventType' );

?>
