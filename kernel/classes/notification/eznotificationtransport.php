<?php
/**
 * File containing the eZNotificationTransport class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNotificationTransport eznotificationtransport.php
  \brief The class eZNotificationTransport does

*/
class eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZNotificationTransport()
    {
    }

    /**
     * Returns a shared instance of the eZNotificationTransport class.
     *
     *
     * @param string|false $transport Uses notification.ini[TransportSettings]DefaultTransport if false
     * @param bool $forceNewInstance
     * @return eZNotificationTransport
     */
    static function instance( $transport = false, $forceNewInstance = false )
    {
        $ini = eZINI::instance( 'notification.ini' );
        if ( $transport == false )
        {
            $transport = $ini->variable( 'TransportSettings', 'DefaultTransport' );
        }
        $transportImpl =& $GLOBALS['eZNotificationTransportGlobalInstance_' . $transport ];
        $class = $transportImpl !== null ? strtolower( get_class( $transportImpl ) ) : '';

        $fetchInstance = false;
        if ( !preg_match( '/.*?transport/', $class ) )
                $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            $fetchInstance = true;
        }

        if ( $fetchInstance )
        {
            $className = $transport . 'notificationtransport';
            if ( class_exists( $className ) )
            {
                $impl = new $className( );
            }
        }
        if ( !isset( $impl ) )
        {
            $impl = new eZNotificationTransport();
            eZDebug::writeError( 'Transport implementation not supported: ' . $transport, __METHOD__ );
        }
        return $impl;
    }

    function send( $address = array(), $subject, $body, $transportData = null )
    {
        return true;
    }
}

?>
