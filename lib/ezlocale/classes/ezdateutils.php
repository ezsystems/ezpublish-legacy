<?php
//
// Definition of eZDateUtils class
//
// Created on: <05-May-2004 11:51:15 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezdateutils.php
*/

/*!
  \class eZDateUtils ezdateutils.php
  \brief The class eZDateUtils does

*/

class eZDateUtils
{
    /*!
     Constructor
    */
    function eZDateUtils()
    {
    }

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
    function rfc1123Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = mktime();
        $info = getdate( $timestamp );
        $days = array( 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                       4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 0 => 'Sun' );
        $wkday = $days[$info['wday']];
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );
        $mon = $months[$info['mon']];
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
    function rfc850Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = mktime();
        $info = getdate( $timestamp );
        $days = array( 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                       4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday' );
        $weekday = $days[$info['wday']];
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );
        $mon = $months[$info['mon']];
        return gmstrftime( $weekday . ", %d-" . $mon . "-%Y %H:%M:%S" . " GMT", $timestamp );
    }

    /*!
     \static
     Parses the date \a $dateText which is in text format and returns a timestamp which represents that date.
    */
    function textToDate( $dateText )
    {
        return strtotime( $dateText );
    }
}

?>
