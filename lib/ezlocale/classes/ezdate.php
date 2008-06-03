<?php
//
// Definition of eZDate class
//
// Created on: <01-Mar-2002 13:48:04 amos>
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
  \class eZDate ezdate.php
  \ingroup eZLocale
  \brief Locale aware date handler

  eZDate handles date values in months, days and years.
  The time stored as a timestamp with 0 hours, 0 minutes and 0 seconds.

  A new instance of eZDate will automaticly use the current locale and current date,
  if you however want a different locale use the setLocale() function. The current locale can be
  fetched with locale().

  Change the date directly with setYear(), setMonth(), setDay() and setMDY().
  You can also adjust the date relative to it's current value by using adjustDate().
  Use timeStamp() to get the current timestamp value or year(), month() and day()
  for the respective values.

  When creating new times you're advised to use the static create()
  function which returns a new eZDate object. You can also create a copy
  with the duplicate() function.

  Date checking is done with the isGreaterThan() and isEqualTo() functions.

  Text output is done with toString() which can return a long string (default) or
  short string representation according to the current locale.

Example:
\code
//include_once( 'lib/ezlocale/classes/ezlocale.php' );
//include_once( 'lib/ezlocale/classes/ezdate.php' );

$us_locale = eZLocale::instance( 'us' );

$date1 = new eZDate();
$date2 = eZDate::create();
$date2->setLocale( $us_locale );
$date2->adjustDate( 1, 2, 3 );
$date3 = $date1->duplicate();

print( $date1->toString() );
print( $date2->toString( true ) );
print( $date1->isEqualTo( $date3 ) ? 'true' : 'false' ); // Prints 'true'

\endcode

  \sa eZTime, eZDateTime, eZLocale
*/

//include_once( 'lib/ezlocale/classes/ezlocale.php' );

class eZDate
{
    /*!
     Creates a new date object with default locale, if $date is not supplied
     the current date is used.
    */
    function eZDate( $date = false )
    {
        if ( $date === false )
        {
            $date = mktime( 0, 0, 0 );
        }
        else
        {
            $arr = getdate( $date );
            $date = mktime( 0, 0, 0, $arr['mon'], $arr['mday'], $arr['year'] );
        }
        $this->Date = $date;
        $this->Locale = eZLocale::instance();
        $this->IsValid = $date > 0;
    }

    function attributes()
    {
        return array( 'timestamp',
                      'is_valid',
                      'year',
                      'month',
                      'day' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        if ( $name == 'timestamp'  )
            return $this->timeStamp();
        else if ( $name == 'is_valid' )
            return $this->isValid();
        else if ( $name == 'day'  )
            return $this->day();
        else if ( $name == 'year'  )
            return $this->year();
        else if ( $name == 'month'  )
            return $this->month();

        eZDebug::writeError( "Attribute '$name' does not exist", 'eZDate::attribute' );
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
    function setLocale( $locale )
    {
        $this->Locale = $locale;
    }

    /*!
     Returns a reference to the current locale.
    */
    function locale()
    {
        return $this->Locale;
    }

    /*!
     Returns the timestamp value, this is the number of seconds since the epoch
     with hours, minutes and seconds set to 0.
     \note The value is returned as a reference and should not be modified.
    */
    function timeStamp()
    {
        return $this->Date;
    }

    function setTimeStamp( $stamp )
    {
        $this->Date = $stamp;
        $this->IsValid = $stamp > 0;
    }

    /*!
     Sets the year leaving the other elements untouched.
    */
    function setYear( $year )
    {
        $arr = getdate( $this->Date );
        $this->Date = mktime( 0, 0, 0, $arr['mon'], $arr['mday'], $year );
    }

    /*!
     Sets the month leaving the other elements untouched.
    */
    function setMonth( $month )
    {
        $arr = getdate( $this->Date );
        $this->Date = mktime( 0, 0, 0, $month, $arr['mday'], $arr['year'] );
    }

    /*!
     Sets the day leaving the other elements untouched.
    */
    function setDay( $day )
    {
        $arr = getdate( $this->Date );
        $this->Date = mktime( 0, 0, 0, $arr['mon'], $day, $arr['year'] );
    }

    /*!
     Returns the year element.
    */
    function year()
    {
        return date( 'Y', $this->Date );
    }

    /*!
     Returns the month element.
    */
    function month()
    {
        return date( 'm', $this->Date );
    }

    /*!
     Returns the day element.
    */
    function day()
    {
        return date( 'd', $this->Date );
    }

    /*!
     Sets the year, month and day elements. If $day or $year is omitted or set 0
     they will get a value taken from the current time.
    */
    function setMDY( $month, $day = 0, $year = 0 )
    {
        if ( $year != 0 )
            $date = mktime( 0, 0, 0, $month, $day, $year );
        else if ( $day != 0 )
            $date = mktime( 0, 0, 0, $month, $day );
        else
            $date = mktime( 0, 0, 0, $month );
        $this->Date = $date;
    }

    /*!
     Adjusts the date relative to it's current value. This is useful for adding/subtracting
     years, months or days to an existing date.
    */
    function adjustDate( $month, $day = 0, $year = 0 )
    {
        $arr = getdate( $this->Date );
        $date = mktime( 0, 0, 0, $month + $arr['mon'], $day + $arr['mday'], $year + $arr['year'] );
        $this->Date = $date;
    }

    /*!
     Returns true if this object has a date greater than $date. $date can be specified as
     a timestamp value or as an eZDate object. If $equal is true it returns true if
     they are equal as well.
    */
    function isGreaterThan( $date, $equal = false )
    {
        $d1 = $this->timeStamp();
        if ( $date instanceof eZDate )
        {
            $d2 = $date->timeStamp();
        }
        else
        {
            $arr = getdate( $date );
            $d2 = mktime( 0, 0, 0, $arr['mon'], $arr['mday'], $arr['year'] );
        }
        if ( $d1 > $d2 )
            return true;
        else if ( $equal and $d1 == $d2 )
            return true;
        else
            return false;
    }
    /*!
     Returns true if this object is equal to $date. $date can be specified as
     a timestamp value or as an eZDate object.
    */
    function isEqualTo( $date )
    {
        $d1 = $this->timeStamp();
        if ( $date instanceof eZDate )
        {
            $d2 = $date->timeStamp();
        }
        else
        {
            $arr = getdate( $date );
            $d2 = mktime( 0, 0, 0, $arr['mon'], $arr['mday'], $arr['year'] );
        }
        return $d1 == $d2;
    }

    /*!
     Creates a new eZDate object with the date values $month, $day and $year and returns a reference to it.
     Any value can be ommitted or set to 0 to use the current date value.
    */
    function create( $month, $day = 0, $year = 0 )
    {
        if ( $year != 0 )
            $date = mktime( 0, 0, 0, $month, $day, $year );
        else if ( $day != 0 )
            $date = mktime( 0, 0, 0, $month, $day );
        else
            $date = mktime( 0, 0, 0, $month );
        $newDateObject = new eZDate( $date );
        return $newDateObject;
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
            $str = $this->Locale->formatShortDate( $this->Date );
        else
            $str = $this->Locale->formatDate( $this->Date );
        return $str;
    }


    /// Locale object, is just a reference to minimize memory usage.
    public $Locale;
    /// The current date as a timestamp without hour, minute or second values
    public $Date;
    public $IsValid;
}

?>
