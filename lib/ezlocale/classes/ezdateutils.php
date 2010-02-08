<?php
//
// Definition of eZDateUtils class
//
// Created on: <05-May-2004 11:51:15 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZDateUtils ezdateutils.php
  \brief The class eZDateUtils does

*/

class eZDateUtils
{
    /*!
     \static
     Return a textual representation of the date according to the RFC 1123 standard.

     rfc1123-date = wkday "," SP date1 SP time SP "GMT"
     date1        = 2DIGIT SP month SP 4DIGIT
                    ; day month year (e.g., 02 Jun 1982)
     time         = 2DIGIT ":" 2DIGIT ":" 2DIGIT
                    ; 00:00:00 - 23:59:59
     wkday        = "Mon" | "Tue" | "Wed"
                  | "Thu" | "Fri" | "Sat" | "Sun"
     month        = "Jan" | "Feb" | "Mar" | "Apr"
                  | "May" | "Jun" | "Jul" | "Aug"
                  | "Sep" | "Oct" | "Nov" | "Dec"
    */
    static function rfc1123Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();
        $wday = (int) gmdate( 'w', $timestamp );
        $days = array( 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                       4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 0 => 'Sun' );
        $wkday = $days[$wday];
        $month = (int) gmdate( 'n', $timestamp );
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );

        $mon = $months[$month];
        return gmstrftime( $wkday . ", %d " . $mon . " %Y %H:%M:%S" . " GMT", $timestamp );
    }

    /*!
     \static
     Return a textual representation of the date according to the RFC 850 standard.

     rfc850-date  = weekday "," SP date2 SP time SP "GMT"
     date2        = 2DIGIT "-" month "-" 2DIGIT
                    ; day-month-year (e.g., 02-Jun-82)
     time         = 2DIGIT ":" 2DIGIT ":" 2DIGIT
                    ; 00:00:00 - 23:59:59
     wkday        = "Mon" | "Tue" | "Wed"
                  | "Thu" | "Fri" | "Sat" | "Sun"
     weekday      = "Monday" | "Tuesday" | "Wednesday"
                  | "Thursday" | "Friday" | "Saturday" | "Sunday"
     month        = "Jan" | "Feb" | "Mar" | "Apr"
                  | "May" | "Jun" | "Jul" | "Aug"
                  | "Sep" | "Oct" | "Nov" | "Dec"
    */
    static function rfc850Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();
        $wday = (int) gmdate( 'w', $timestamp );
        $days = array( 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                       4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday' );
        $weekday = $days[$wday];
        $month = (int) gmdate( 'n', $timestamp );
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );
        $mon = $months[$month];
        return gmstrftime( $weekday . ", %d-" . $mon . "-%Y %H:%M:%S" . " GMT", $timestamp );
    }

    /*!
     \static
     Parses the date \a $dateText which is in text format and returns a timestamp which represents that date.
    */
    static function textToDate( $dateText )
    {
        return strtotime( $dateText );
    }
}

?>
