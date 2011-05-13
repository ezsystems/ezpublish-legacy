<?php
/**
 * File containing the eZNotificationSchedule class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNotificationSchedule eznotificationschedule.php
  \brief The class eZNotificationSchedule does

*/

class eZNotificationSchedule
{
    /*!
     Constructor
    */
    function eZNotificationSchedule()
    {
    }

    static function setDateForItem( $item, $settings )
    {
        if ( !is_array( $settings ) )
            return false;

        $dayNum = isset( $settings['day'] ) ? $settings['day'] : false;
        $hour = $settings['hour'];
        $currentDate = getdate();
        $hoursDiff = $hour - $currentDate['hours'];

        switch ( $settings['frequency'] )
        {
            case 'day':
            {
                if ( $hoursDiff <= 0 )
                {
                    $hoursDiff += 24;
                }

                $secondsDiff = 3600 * $hoursDiff
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'week':
            {
                $daysDiff = $dayNum - $currentDate['wday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += 7;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'month':
            {
                // If the daynum the user has chosen is larger than the number of days in this month,
                // then reduce it to the number of days in this month.
                $daysInMonth = intval( date( 't', mktime( 0, 0, 0, $currentDate['mon'], 1, $currentDate['year'] ) ) );
                if ( $dayNum > $daysInMonth )
                {
                    $dayNum = $daysInMonth;
                }

                $daysDiff = $dayNum - $currentDate['mday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += $daysInMonth;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;
        }

        $sendDate = time() + $secondsDiff;
        eZDebugSetting::writeDebug( 'kernel-notification', getdate( $sendDate ), "item date"  );
        $item->setAttribute( 'send_date', $sendDate );
        return $sendDate;
    }
}

?>
