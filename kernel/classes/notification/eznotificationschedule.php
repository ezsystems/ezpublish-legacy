<?php
//
// Definition of eZNotificationSchedule class
//
// Created on: <16-May-2003 15:22:43 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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
            $hour = $settings['time'];
            $days = array( 0 => 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
            $day = $days[$settings['day']];
            $sendDate = strtotime( "first $day" ) + $hour * 3600;

            /* Ugly hack to work around a bug in PHP =< 4.3.6. strtotime() will
             * return a time one hour too late if DST is in effect when using
             * "first *". */
            if ( version_compare( phpversion(), "4.3.6", "<=" ) )
            {
                $lt = localtime( $sendDate, true );
                if ( $lt['tm_isdst'] )
                {
                    $sendDate -= 3600;
                }
            }

            eZDebugSetting::writeDebug( 'kernel-notification', getdate( $sendDate ), "item date"  );
            $item->setAttribute( 'send_date', $sendDate );
            return $sendDate;
        }
    }
}

?>
