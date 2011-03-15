<?php
/**
 * File containing the eZPublishType class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/

/*!
  \class eZPublishType ezpublishtype.php
  \brief The class eZPublishType does

*/

class eZPublishType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezpublish';

    /*!
     Constructor
    */
    function eZPublishType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $event->setAttribute( 'data_int1', $params['object'] );
        $event->setAttribute( 'data_int2', $params['version'] );
    }

    function eventContent( $event )
    {
        return eZContentObjectVersion::fetchVersion( $event->attribute( 'data_int2' ), $event->attribute( 'data_int1' ) );
    }
}

eZNotificationEventType::register( eZPublishType::NOTIFICATION_TYPE_STRING, 'eZPublishType' );

?>
