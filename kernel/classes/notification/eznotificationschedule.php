<?php
//
// Definition of eZNotificationSchedule class
//
// Created on: <16-May-2003 15:22:43 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eznotificationschedule.php
*/

/*!
  \class eZNotificationSchedule eznotificationschedule.php
  \brief The class eZNotificationSchedule does

*/
include_once( "lib/ezlocale/classes/ezdate.php" );


class eZNotificationSchedule
{
    /*!
     Constructor
    */
    function eZNotificationSchedule()
    {
    }

    function setDateForItem( &$item, $settings )
    {
        if ( !is_array( $settings ) )
            return false;
        if ( $settings['frequency'] == 'week' )
        {
            $dayNum = $settings['day'];
            $time = $settings['time'];
            $date = new eZDate( );
            $stamp = mktime();
            $currentDate = getdate();
            $date->setTimeStamp( $stamp );

            $weekday = $currentDate['wday'];

            $dayDiff = $dayNum - $weekday;
            if( $dayDiff <= 0 )
            {
                $dayDiff += 7;
            }

            $hoursDiff = $time - $currentDate['hours'];
            if ( $hoursDiff < 0 )
            {
//                if (
//                $hoursDiff += 24;
            }

            $secondsDiff = 3600 * ( $dayDiff * 24  + $hoursDiff ) - $currentDate['seconds'] - 60 * $currentDate['minutes'];
            $sendDate = $stamp + $secondsDiff;
            eZDebugSetting::writeDebug( 'kernel-notification', getdate( $sendDate ), "item date"  );
            $item->setAttribute( 'send_date', $sendDate );
            return $sendDate;
        }
    }
}

?>
