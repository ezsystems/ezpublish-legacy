<?php
//
// Definition of eZDateTime class
//
// Created on: <01-Mar-2002 13:48:12 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
  \class eZDateTime ezdatetime.php
  \ingroup eZLocale
  \brief Locale aware date and time handler

  eZDateTime handles 24 hour time values in hours, minutes and seconds
  and date values.
  The datetime stored as a timestamp with the number of seconds since the epoch.
  See PHP function date() and time() for more information.

  A new instance of eZDateTime will automaticly use the current locale and current datetime,
  if you however want a different locale use the setLocale() function. The current locale can be
  fetched with locale().

  Change the time directly with setHour(), setMinute(), setSecond() and setHMS().
  Change the date directly with setYear(), setMonth(), setDay() and setMDY().
  You can also adjust the date time relative to it's current value by using
  adjustDateTime(). Use timeStamp() to get the current timestamp value or
  year(), month(), day(), hour(), minute() and second() for the respective
  values.

  When creating new datetimes you're advised to use the static create()
  function which returns a new eZDateTime object. You can also create a copy
  with the duplicate() function.

  Time checking is done with the isGreaterThan() and isEqualTo() functions.

  Text output is done with toString() which can return a long string (default) or
  short string representation according to the current locale.

Example:
\code
//include_once( 'lib/ezlocale/classes/ezlocale.php' );
//include_once( 'lib/ezlocale/classes/ezdatetime.php' );

$us_locale = eZLocale::instance( 'us' );

$dt1 = new eZDateTime();
$dt2 = eZDateTime::create();
$dt2->setLocale( $us_locale );
$dt2->adjustDateTime( -8, 0, 0, 1, 2, 3 );
$dt3 = $dt1->duplicate();

print( $dt1->toString() );
print( $dt2->toString( true ) );
print( $dt1->isEqualTo( $dt3 ) ? 'true' : 'false' ); // Prints 'true'

\endcode

  \sa eZDate, eZTime, eZLocale
*/

//include_once( 'lib/ezlocale/classes/ezlocale.php' );
//include_once( 'lib/ezlocale/classes/ezdate.php' );
//include_once( 'lib/ezlocale/classes/eztime.php' );

class eZDateTime
{
    /*!
     Creates a new datetime object with default locale, if $datetime is not supplied
     the current datetime is used.
    */
    function eZDateTime( $datetime = false )
    {
        if ( $datetime instanceof eZDate )
        {
            $arr = getdate( $datetime->timeStamp() );
            $arr2 = getdate( $this->DateTime );
            $datetime = mktime( $arr2['hours'], $arr2['minutes'], $arr2['seconds'],
                                $arr['mon'], $arr['mday'], $arr['year'] );
        }
        else if ( $datetime instanceof eZTime )
        {
            $arr2 = getdate( $datetime->timeStamp() );
            $arr = getdate( $this->DateTime );
            $datetime = mktime( $arr2['hours'], $arr2['minutes'], $arr2['seconds'],
                                $arr['mon'], $arr['mday'], $arr['year'] );
        }
        else if ( $datetime === false )
        {
            $datetime = time();
        }
        else
        {
            $arr = getdate( $datetime );
            $datetime = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                                $arr['mon'], $arr['mday'], $arr['year'] );
        }
        $this->DateTime = $datetime;
        $this->Locale = eZLocale::instance();
        $this->IsValid = $datetime > 0;
    }

    function attributes()
    {
        return array( 'timestamp',
                      'hour',
                      'minute',
                      'year',
                      'month',
                      'day',
                      'is_valid' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        if ( $name == 'timestamp'  )
        {
            return $this->timeStamp();
        }
        else if ( $name == 'hour' )
        {
            return $this->hour();
        }
        else if ( $name == 'minute'  )
        {
            return $this->minute();
        }
        else if ( $name == 'day'  )
        {
            return $this->day();
        }
        else if ( $name == 'year'  )
        {
            return $this->year();
        }
        else if ( $name == 'month'  )
        {
            return $this->month();
        }
        else if ( $name == 'is_valid'  )
        {
            return $this->isValid();
        }
        else
        {
            eZDebug::writeError( "Attribute '$name' does not exist", 'eZDateTime::attribute' );
            return false;
        }
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
    function setLocale( $locale )
    {
        $this->Locale = $locale;
    }

    /*!
     Returns the current locale.
    */
    function locale()
    {
        return $this->Locale;
    }

    /*!
     Returns the current time zone.
    */
    function timeZone()
    {
        return date( 'T', $this->DateTime );
    }

    /*!
     Returns the timestamp value, this is the number of seconds since the epoch.
     \note The value is returned as a reference and should not be modified.
    */
    function timeStamp( )
    {
        return $this->DateTime;
    }

    function setTimeStamp( $stamp )
    {
        $this->DateTime = $stamp;
        $this->IsValid = $stamp > 0;
    }

    /*!
     \static
     Returns the current date and time as a UNIX timestamp
    */
    static function currentTimeStamp()
    {
        return time();
    }

    /*!
     Creates an eZDate object of this datetime with the same date and locale.
     Returns a reference to the object.
    */
    function toDate()
    {
        $date = new eZDate( $this->DateTime );
        $date->setLocale( $this->Locale );
        return $date;
    }

    /*!
     Creates an eZTime object of this datetime with the same time and locale.
     Returns a reference to the object.
    */
    function toTime()
    {
        $time = new eZTime( $this->DateTime );
        $time->setLocale( $this->Locale );
        return $time;
    }

    /*!
     Returns the year element.
    */
    function year()
    {
        return date( 'Y', $this->DateTime );
    }

    /*!
     Returns the month element.
    */
    function month()
    {
        return date( 'm', $this->DateTime );
    }

    /*!
     Returns the day element.
    */
    function day()
    {
        return date( 'd', $this->DateTime );
    }

    /*!
     Returns the hour element.
    */
    function hour()
    {
        return date( 'G', $this->DateTime );
    }

    /*!
     Returns the minute element.
    */
    function minute()
    {
        return date( 'i', $this->DateTime );
    }

    /*!
     Returns the second element.
    */
    function second()
    {
        return date( 's', $this->DateTime );
    }

    /*!
     Sets the year leaving the other elements untouched.
    */
    function setYear( $year )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                                  $arr['mon'], $arr['mday'], $year );
    }

    /*!
     Sets the month leaving the other elements untouched.
    */
    function setMonth( $month )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                                  $month, $arr['mday'], $arr['year'] );
    }

    /*!
     Sets the day leaving the other elements untouched.
    */
    function setDay( $day )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                                  $arr['mon'], $day, $arr['year'] );
    }

    /*!
     Sets the hour leaving the other elements untouched.
    */
    function setHour( $hour )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $hour, $arr['minutes'], $arr['seconds'],
                                  $arr['mon'], $arr['mday'], $arr['year'] );
    }

    /*!
     Sets the minute leaving the other elements untouched.
    */
    function setMinute( $min )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $arr['hours'], $min, $arr['seconds'],
                                  $arr['mon'], $arr['mday'], $arr['year'] );
    }

    /*!
     Sets the second leaving the other elements untouched.
    */
    function setSecond( $sec )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $arr['hours'], $arr['minutes'], $sec,
                                  $arr['mon'], $arr['mday'], $arr['year'] );
    }

    /*!
     Sets all hour, minute and second elements leaving the other elements untouched.
    */
    function setHMS( $hour, $min = 0, $sec = 0 )
    {
        $arr = getdate( $this->DateTime );
        $this->DateTime = mktime( $hour, $min, $sec,
                                  $arr['mon'], $arr['mday'], $arr['year'] );
    }

    /*!
     Sets all hour, minute and second elements leaving the other elements untouched.
    */
    function setMDYHMS( $month, $day, $year, $hour, $min, $sec = 0 )
    {
        $this->DateTime = mktime( $hour, $min, $sec, $month, $day, $year );
    }

    /*!
     Sets the year, month and day elements. If $day or $year is omitted or set 0
     they will get a value taken from the current time.
    */
    function setMDY( $month, $day = 0, $year = 0 )
    {
        $arr = getdate( $this->DateTime );
        if ( $year != 0 )
            $date = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                            $month, $day, $year );
        else if ( $day != 0 )
            $date = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                            $month, $day );
        else
            $date = mktime( $arr['hours'], $arr['minutes'], $arr['seconds'],
                            $month );
        $this->DateTime = $date;
    }

    /*!
     Adjusts the datetime relative to it's current value. This is useful for adding/subtracting
     hours, minutes, seconds, years, months or days to an existing datetime.
    */
    function adjustDateTime( $hour, $minute = 0, $second = 0, $month = 0, $day = 0, $year = 0 )
    {
        $arr = getdate( $this->DateTime );
        $date = mktime( $hour + $arr['hours'], $minute + $arr['minutes'], $second + $arr['seconds'],
                        $month + $arr['mon'], $day + $arr['mday'], $year + $arr['year'] );
        $this->DateTime = $date;
    }

    /*!
     Returns true if this object has a datetime greater than $datetime. $datetime can be specified as
     a timestamp value or as an eZDateTime, eZDate or eZTime object. If $equal is true it returns true if
     they are equal as well.
     \note If $datetime is either eZDate or eZTime it will create temporary objects with toDate() and
     toTime() and use these for comparison.
    */
    function isGreaterThan( &$datetime, $equal = false )
    {
        if ( $datetime instanceof eZDate )
        {
            $d1 = $this->toDate();
            return $d1->isGreaterThan( $datetime, $equal );
        }
        else if ( $datetime instanceof eZTime )
        {
            $t1 = $this->toTime();
            return $t1->isGreaterThan( $datetime, $equal );
        }
        else
        {
            $dt1 = $this->timeStamp();
            $dt2 = $datetime instanceof eZDateTime ? $datetime->timeStamp() : $datetime;

            if ( $dt1 > $dt2 )
                return true;
            else if ( $equal and $dt1 == $dt2 )
                return true;
            else
                return false;
        }
    }
    /*!
     Returns true if this object is equal to $date. $date can be specified as
     a timestamp value or as an eZDateTime, eZDate or eZTime object.
     \note If $datetime is either eZDate or eZTime it will create temporary objects with toDate() and
     toTime() and use these for comparison.
    */
    function isEqualTo( &$datetime )
    {
        if ( $datetime instanceof eZDate )
        {
            $d1 = $this->toDate();
            return $d1->isEqualTo( $datetime );
        }
        else if ( $datetime instanceof eZTime )
        {
            $t1 = $this->toTime();
            return $t1->isEqualTo( $datetime );
        }
        else
        {
            $dt1 = $this->timeStamp();
            $dt2 = $datetime instanceof eZDateTime ? $datetime->timeStamp() : $datetime;

            return $dt1 == $dt2;
        }
    }

    /*!
     Creates a new eZDate object with the time values $hour, $minute and $second,
     date values $month, $day and $year and returns a reference to it.
     Any value can be ommitted or set to -1 to use the current date or time value.
    */
    static function create( $hour = -1, $minute = -1, $second = -1, $month = -1, $day = -1, $year = -1 )
    {
        if ( $year != -1 )
            $datetime = mktime( $hour, $minute, $second, $month, $day, $year );
        else if ( $day != -1 )
            $datetime = mktime( $hour, $minute, $second, $month, $day );
        else if ( $month != -1 )
            $datetime = mktime( $hour, $minute, $second, $month );
        else if ( $second != -1 )
            $datetime = mktime( $hour, $minute, $second );
        else if ( $minute != -1 )
            $datetime = mktime( $hour, $minute );
        else if ( $hour != -1 )
            $datetime = mktime( $hour );
        else
            $datetime = time();
        return new eZDateTime( $datetime );
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
     Creates a string representation of the date using the current locale and returns it.
     If $short is true a short representation is used.
    */
    function toString( $short = false )
    {
        if ( $short )
            $str = $this->Locale->formatShortDate( $this->DateTime ) . ' ' .
                $this->Locale->formatShortTime( $this->DateTime );
        else
            $str = $this->Locale->formatDate( $this->DateTime ) . ' ' .
                $this->Locale->formatTime( $this->DateTime );
        return $str;
    }

    /// Locale object, is just a reference to minimize memory usage.
    public $Locale;
    /// The current datetime as a timestamp
    public $DateTime;
    public $IsValid;
}

?>
