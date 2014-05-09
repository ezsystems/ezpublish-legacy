<?php
/**
 * File containing the eZCurrentTimeType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZCurrentTimeType ezcurrenttimetype.php
  \brief The class eZCurrentTimeType does

*/
class eZCurrentTimeType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezcurrenttime';

    /*!
     Constructor
    */
    function eZCurrentTimeType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $time = 0;
        if ( array_key_exists( 'time', $params ) )
        {
            $time = $params['time'];
        }
        else
        {
            $time = time();
        }
        $event->setAttribute( 'data_int1', $time );
    }

    function eventContent( $event )
    {
        $date = new eZDate( );
        $stamp = $event->attribute( 'data_int1' );
        $date->setTimeStamp( $stamp );
        return $date;
    }

}

eZNotificationEventType::register( eZCurrentTimeType::NOTIFICATION_TYPE_STRING, 'eZCurrentTimeType' );


?>
