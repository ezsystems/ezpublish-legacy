<?php
/**
 * File containing the eZCurrentTimeType class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
