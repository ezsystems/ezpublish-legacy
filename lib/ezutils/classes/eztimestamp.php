<?php
/**
 * File containing the eZTimestamp class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

class eZTimestamp
{
    /*!
     \return a timestamp in UTC
    */
    public static function getUtcTimestampFromLocalTimestamp( $localTimestamp ) {

        if ( $localTimestamp === null || $localTimestamp === '' )
        {
            return null;
        }

        $utcTimezone = new \DateTimeZone( 'UTC' );
        $localTimezone = new \DateTimeZone( date_default_timezone_get() );

        $localDate = new \DateTime( null, $localTimezone );
        $localDate->setTimestamp( $localTimestamp );
        $utcDate = new \DateTime( $localDate->format( 'Y-m-d H:i:s' ), $utcTimezone );

        return $utcDate->getTimestamp();
    }

    /*!
     \return a timestamp in timezone defined in php.ini
    */
    public static function getLocalTimestampFromUtcTimestamp( $utcTimestamp ) {

        if ( $utcTimestamp === null || $utcTimestamp === '' )
        {
            return null;
        }

        $utcTimezone = new \DateTimeZone( 'UTC' );
        $localTimezone = new \DateTimeZone( date_default_timezone_get() );

        $utcDate = new \DateTime( null, $utcTimezone );
        $utcDate->setTimestamp( $utcTimestamp );
        $localDate = new \DateTime( $utcDate->format( 'Y-m-d H:i:s' ), $localTimezone );

        return $localDate->getTimestamp();
    }
}
?>
