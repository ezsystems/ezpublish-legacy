<?php
//
// Definition of eZTime class
//
// Created on: <01-Mar-2002 13:48:40 amos>
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

/*!
  \class eZTime eztime.php
  \ingroup eZLocale
  \brief Locale aware time handler

  eZTime handles 24 hour time values in hours, minutes and seconds.
  The time stored as a timestamp clamped to a 24 hour day, ie 86400.

  A new instance of eZTime will automaticly use the current locale and current time,
  if you however want a different locale use the setLocale() function. The current locale can be
  fetched with locale().

  You can also change the time directly with setHour(), setMinute(),
  setSecond() and setHMS(). You can also adjust the time relative to it's
  current value by using adjustTime(). Use timeStamp() to get the current
  timestamp value or hour(), minute() and second() for the respective
  values.

  When creating new times you're advised to use the static create()
  function which returns a new eZTime object. You can also create a copy
  with the duplicate() function.

  Time checking is done with the isGreaterThan() and isEqualTo() functions.

  Text output is done with toString() which can return a long string (default) or
  short string representation according to the current locale.

Example:
\code
include_once( 'lib/ezlocale/classes/ezlocale.php' );
include_once( 'lib/ezlocale/classes/eztime.php' );

$us_locale =& eZLocale::instance( 'us' );

$time1 = new eZTime();
$time2 =& eZTime::create();
$time2->setLocale( $us_locale );
$time2->adjustTime( -8 );
$time3 =& $time1->duplicate();

print( $time1->toString() );
print( $time2->toString( true ) );
print( $time1->isEqualTo( $time3 ) ? 'true' : 'false' ); // Prints 'true'

\endcode

  \sa eZDate, eZDateTime, eZLocale
*/

include_once( 'lib/ezlocale/classes/ezlocale.php' );

/*!
 Number of seconds in a minute.
*/
define( 'EZTIME_SECONDS_A_MINUTE', 60 );
/*!
 Number of seconds in an hour.
*/
define( 'EZTIME_SECONDS_AN_HOUR', 3600 );
/*!
 Number of seconds in a day.
*/
define( 'EZTIME_SECONDS_A_DAY', 86400 ); // 24*60*60

class eZTime
{
    /*!
     Creates a new time object with default locale, if $time is not supplied
     the current time is used.
    */
    function eZTime( $time = false )
    {
        if ( $time === false )
        {
            $time =& time();
        }
        $time = ( $time % EZTIME_SECONDS_A_DAY );
        $this->Time =& $time;
        $this->Locale =& eZLocale::instance();
        $this->IsValid = $time >= 0;
    }

    function attributes()
    {
        return array( 'timestamp',
                      'hour',
                      'minute',
                      'is_valid' );
    }

    function hasAttribute( $name )
    {
        if ( $name == 'timestamp' or
             $name == 'hour' or
             $name == 'minute' or
             $name == 'is_valid' )
            return true;
        else
            return false;
    }

    function &attribute( $name )
    {
        if ( $name == 'timestamp'  )
            return $this->timeStamp();
        else if ( $name == 'hour' )
            return $this->hour();
        else if ( $name == 'minute'  )
            return $this->minute();
        else if ( $name == 'is_valid'  )
            return $this->isValid();
        else
            return false;
    }

    /*!
     \return true if the date has valid data.
    */
    function &isValid()
    {
        return $this->IsValid;
    }

    /*!
     Sets the locale to $locale which is used in text output.
    */
    function setLocale( &$locale )
    {
        $this->Locale =& $locale;
    }

    /*!
     Returns a reference to the current locale.
    */
    function &locale()
    {
        return $this->Locale;
    }

    /*!
     Returns the current time zone.
    */
    function &timeZone()
    {
        return date( 'T', $this->Time );
    }

    /*!
     Sets the hour leaving the other elements untouched.
    */
    function setHour( $hour )
    {
//         $this->Time = ( mktime( $hour, date( 'i', $this->Time ), date( 's', $this->Time ) ) % EZTIME_SECONDS_A_DAY );
        $this->Time = ( mktime( $hour, date( 'i', $this->Time ), date( 's', $this->Time ) )  );
    }

    /*!
     Sets the minute leaving the other elements untouched.
    */
    function setMinute( $min )
    {
//         $this->Time = ( mktime( date( 'G', $this->Time ), $min, date( 's', $this->Time ) ) % EZTIME_SECONDS_A_DAY );
        $this->Time = ( mktime( date( 'G', $this->Time ), $min, date( 's', $this->Time ) )  );
    }

    /*!
     Sets the second leaving the other elements untouched.
    */
    function setSecond( $sec )
    {
//         $this->Time = ( mktime( date( 'G', $this->Time ),date( 'i', $this->Time ), $sec ) % EZTIME_SECONDS_A_DAY );
        $this->Time = ( mktime( date( 'G', $this->Time ),date( 'i', $this->Time ), $sec )  );
    }

    /*!
     Sets all hour, minute and second elements.
    */
    function setHMS( $hour, $min = 0, $sec = 0 )
    {
//         $this->Time = ( mktime( $hour, $min, $sec ) % EZTIME_SECONDS_A_DAY );
        $this->Time = ( mktime( $hour, $min, $sec ) );
    }

    /*!
     Returns the hour element.
    */
    function &hour()
    {
        return date( 'G', $this->Time );
    }

    /*!
     Returns the minute element.
    */
    function &minute()
    {
        return date( 'i', $this->Time );
    }

    /*!
     Returns the second element.
    */
    function &second()
    {
        return date( 's', $this->Time );
    }

    /*!
     Returns the timestamp value, this is not the number of seconds since the epoch but
     a clamped value to the number of seconds in a day.
     \note The value is returned as a reference and should not be modified.
    */
    function &timeStamp()
    {
        return $this->Time;
    }

    function setTimeStamp( $stamp )
    {
        $this->Time = $stamp;
        $this->IsValid = $stamp >= 0;
    }

    /*!
     Adjusts the time relative to it's current value. This is useful for adding/subtracting
     hours, minutes or seconds to an existing time.
    */
    function adjustTime( $hour, $min = 0, $sec = 0 )
    {
//         $this->Time = ( ( $hour * EZTIME_SECONDS_AN_HOUR +
//                           $min * EZTIME_SECONDS_A_MINUTE +
//                           $sec +
//                           $this->Time )  % EZTIME_SECONDS_A_DAY );
        $this->Time = ( ( $hour * EZTIME_SECONDS_AN_HOUR +
                          $min * EZTIME_SECONDS_A_MINUTE +
                          $sec +
                          $this->Time ) );
    }

    /*!
     Creates a new eZTime object with the time values $hour, $min and $sec and returns a reference to it.
     Any value can be ommitted or set to -1 to use the current time value.
    */
    function &create( $hour = -1, $min = -1, $sec = -1 )
    {
        if ( $sec != -1 )
            $time =& mktime( $hour, $min, $sec );
        else if ( $min != -1 )
            $time =& mktime( $hour, $min );
        else if ( $hour != -1 )
            $time =& mktime( $hour );
        else
            $time =& mktime();
        return new eZTime( $time );
    }

    /*!
     Creates an exact copy of this object and returns a reference to it.
    */
    function &duplicate()
    {
        $t =& new eZTime( $this->Time );
        $t->setLocale( $this->Locale );
        return $t;
    }

    /*!
     Returns true if this object has a time greater than $time. $time can be specified as
     a timestamp value or as an eZTime object. If $equal is true it returns true if
     they are equal as well.
    */
    function isGreaterThan( &$time, $equal = false )
    {
        $t1 =& $this->Time;
        if ( get_class( $time ) == 'eztime' )
            $t2 =& $time->timeStamp();
        else
            $t2 = ( $time % EZTIME_SECONDS_A_DAY );
        if ( $t1 > $t2 )
            return true;
        else if ( $equal and $t1 == $t2 )
            return true;
        else
            return false;
    }

    /*!
     Returns true if this object is equal to $time. $time can be specified as
     a timestamp value or as an eZTime object.
    */
    function isEqualTo( &$time )
    {
        $t1 =& $this->Time;
        if ( get_class( $time ) == 'eztime' )
            $t2 =& $time->timeStamp();
        else
            $t2 = ( $time % EZTIME_SECONDS_A_DAY );
        return $t1 == $t2;
    }

    /*!
     Creates a string representation of the time using the current locale and returns it.
     If $short is true a short representation is used.
    */
    function &toString( $short = false )
    {
        if ( $short )
            $str =& $this->Locale->formatShortTime( $this->Time );
        else
            $str =& $this->Locale->formatTime( $this->Time );
        return $str;
    }

    /*!
     \static
     Get number of seconds per day

     \return number of seconds per day
    */
    function secondsPerDay()
    {
        return EZTIME_SECONDS_A_DAY;
    }

    /// Locale object, is just a reference to minimize memory usage.
    var $Locale;
    /// The current time as a clamped timestamp
    var $Time;
}

?>
