<?php
//
// Definition of eZTime class
//
// Created on: <01-Mar-2002 13:48:40 amos>
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
//include_once( 'lib/ezlocale/classes/ezlocale.php' );
//include_once( 'lib/ezlocale/classes/eztime.php' );

$us_locale = eZLocale::instance( 'us' );

$time1 = new eZTime();
$time2 = eZTime::create();
$time2->setLocale( $us_locale );
$time2->adjustTime( -8 );
$time3 = $time1->duplicate();

print( $time1->toString() );
print( $time2->toString( true ) );
print( $time1->isEqualTo( $time3 ) ? 'true' : 'false' ); // Prints 'true'

\endcode

  \sa eZDate, eZDateTime, eZLocale
*/

//include_once( 'lib/ezlocale/classes/ezlocale.php' );

class eZTime
{
    /*!
     Number of seconds in a minute.
    */
    const SECONDS_A_MINUTE = 60;

    /*!
     Number of seconds in an hour.
    */
    const SECONDS_AN_HOUR = 3600;

    /*!
     Number of seconds in a day.
    */
    const SECONDS_A_DAY = 86400; // 24*60*60

    /*!
     Creates a new time object with default locale, if $time is not supplied
     the current time is used.
    */
    function eZTime( $timestamp = false )
    {
        if ( $timestamp === false )
        {
            $cur_date = getdate();
            $this->setHMS( $cur_date[ 'hours' ],
                           $cur_date[ 'minutes' ],
                           $cur_date[ 'seconds' ] );
        }
        else if ( $timestamp > self::SECONDS_A_DAY )
            $this->setTimeStamp( $timestamp );
        else
            $this->setTimeOfDay( $timestamp );

        $this->Locale = eZLocale::instance();
    }

    function attributes()
    {
        return array( 'timestamp',
                      'time_of_day',
                      'hour',
                      'minute',
                      'second',
                      'is_valid' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        if ( $name == 'timestamp' )
            return $this->timeStamp();
        else if ( $name == 'time_of_day' )
            return $this->timeOfDay();
        else if ( $name == 'hour' )
            return $this->hour();
        else if ( $name == 'minute' )
            return $this->minute();
        else if ( $name == 'second' )
            return $this->second();
        else if ( $name == 'is_valid'  )
            return $this->isValid();

        eZDebug::writeError( "Attribute '$name' does not exist", 'eZTime::attribute' );
        return false;
    }

    /*!
     \return true if the date has valid data.
    */
    function isValid()
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
     Sets the hour leaving the other elements untouched.
    */
    function setHour( $hour )
    {
        $this->Time = ($hour % 24) * self::SECONDS_AN_HOUR + $this->minute() * self::SECONDS_A_MINUTE + $this->second();
        $this->IsValid = $this->Time >= 0;
    }

    /*!
     Sets the minute leaving the other elements untouched.
    */
    function setMinute( $min )
    {
        $this->Time = $this->hour() * self::SECONDS_AN_HOUR + ($min % 60) * self::SECONDS_A_MINUTE + $this->second();
        $this->IsValid = $this->Time >= 0;
    }

    /*!
     Sets the second leaving the other elements untouched.
    */
    function setSecond( $sec )
    {
        $this->Time = $this->hour() * self::SECONDS_AN_HOUR + $this->minute() * self::SECONDS_A_MINUTE + ($sec % 60);
        $this->IsValid = $this->Time >= 0;
    }

    /*!
     Sets all hour, minute and second elements.
    */
    function setHMS( $hour, $min = 0, $sec = 0 )
    {
        $this->Time = ($hour % 24 ) * self::SECONDS_AN_HOUR + ($min % 60) * self::SECONDS_A_MINUTE + ($sec % 60);
        $this->IsValid = $this->Time >= 0;
    }

    /*!
     Returns the hour element.
    */
    function hour()
    {
        return (int)($this->Time / self::SECONDS_AN_HOUR);
    }

    /*!
     Returns the minute element.
    */
    function minute()
    {
        return (int)(($this->Time % self::SECONDS_AN_HOUR) / self::SECONDS_A_MINUTE);
    }

    /*!
     Returns the second element.
    */
    function second()
    {
        return (int)($this->Time % self::SECONDS_A_MINUTE);
    }

    /*
     Return long timestamp ($this->Time + current day midnight timestamp)
    */
    function timeStamp()
    {
        $cur_date = getdate();
        $longtimestamp = mktime( $this->hour(),
                                 $this->minute(),
                                 $this->second(),
                                 $cur_date[ 'mon' ],
                                 $cur_date[ 'mday' ],
                                 $cur_date[ 'year' ] );
        return $longtimestamp;
    }

    /*
     Sets timestamp (time of the das) by clamping off time from the last midnight.
    */
    function setTimeStamp( $timestamp )
    {
        $date = getdate( $timestamp );
        $this->Time = $date[ 'hours' ] * self::SECONDS_AN_HOUR +
                      $date[ 'minutes' ] * self::SECONDS_A_MINUTE +
                      $date[ 'seconds' ];
    }

    /*!
     Returns the timestamp value, this is not the number of seconds since the epoch but
     a clamped value to the number of seconds in a day.
    */
    function timeOfDay()
    {
        return $this->Time;
    }

    /*
     Sets time of day (the number of seconds since begining of day)
    */
    function setTimeOfDay( $timestamp )
    {
        $this->Time = $timestamp % self::SECONDS_A_DAY;
        if ( $this->Time < 0 )
            $this->Time += self::SECONDS_A_DAY;
        $this->IsValid = $this->Time >= 0;
    }


    /*!
     Adjusts the time relative to it's current value. This is useful for adding/subtracting
     hours, minutes or seconds to an existing time.
    */
    function adjustTime( $hour, $minute = 0, $second = 0 )
    {
        $this->setTimeOfDay( $hour * self::SECONDS_AN_HOUR +
                             $minute * self::SECONDS_A_MINUTE +
                             $second + $this->Time );
    }

    /*!
     Creates a new eZTime object with the time values $hour, $min and $sec and returns a reference to it.
     Any value can be ommitted or set to -1 to use the current time value.
    */
    function create( $hour = -1, $minute = -1, $second = -1 )
    {
        $cur_date = getdate();

        $time = new eZTime();
        $time->setHMS( $hour < 0 ? $cur_date[ 'hours' ] : $hour,
                       $minute < 0 ? $cur_date[ 'minutes' ] : $minute,
                       $second < 0 ? $cur_date[ 'seconds' ] : $second );
        return $time;
    }

    /*!
     \deprecated This function is deprecated in PHP5, use the PHP5 clone keyword instead
     Creates an exact copy of this object and returns it.
    */
    function duplicate()
    {
        $copy = clone $this;
        return $copy;
    }

    /*!
     Returns true if this object has a time greater than $time. $time can be specified as
     a timestamp value or as an eZTime object. If $equal is true it returns true if
     they are equal as well.
    */
    function isGreaterThan( &$time, $equal = false )
    {
        $t1 =& $this->Time;
        if ( $time instanceof eZTime )
            $t2 = $time->timeOfDay();
        else
            $t2 = ( $time % self::SECONDS_A_DAY );
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
        if ( $time instanceof eZTime )
            $t2 = $time->timeOfDay();
        else
            $t2 = ( $time % self::SECONDS_A_DAY );
        return $t1 == $t2;
    }

    /*!
     Creates a string representation of the time using the current locale and returns it.
     If $short is true a short representation is used.
    */
    function toString( $short = false )
    {
        if ( $short )
            $str = $this->Locale->formatShortTime( $this->timeStamp() );
        else
            $str = $this->Locale->formatTime( $this->timeStamp() );
        return $str;
    }

    /*!
     \static
     Get number of seconds per day
    */
    function secondsPerDay()
    {
        return self::SECONDS_A_DAY;
    }

    /// Locale object, is just a reference to minimize memory usage.
    public $Locale;
    /// The current time as a clamped timestamp
    public $Time;
}

?>
