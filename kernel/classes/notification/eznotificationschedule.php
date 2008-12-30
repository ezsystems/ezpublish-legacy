<?php
//
// Definition of eZNotificationSchedule class
//
// Created on: <16-May-2003 15:22:43 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

    function setDateForItem( $item, $settings )
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
