<?php
//
// Definition of eZLocale class
//
// Created on: <01-Mar-2002 13:48:32 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \defgroup eZLocale Locale system */

/*!
  \class eZLocale ezlocale.php
  \ingroup eZLocale
  \brief Provides unified access to locale information and conversions.

  The eZLocale class handles locale information and can format time, date, numbers and currency
  for correct display for a given locale. The locale conversion uses plain numerical values for
  dates, times, numbers and currency, if you want more elaborate conversions consider using the
  eZDate, eZTime, eZDateTime and eZCurrency classes.

  The first time a locale object is created (ie. eZLocale::instance() ) you must be sure to set
  a language using setLanguage before using any textual conversions.

  Example:

\code
include_once( 'lib/ezlocale/classes/ezlanguage.php' );
include_once( 'lib/ezlocale/classes/ezlocale.php' );

// Fetch the default values supplied by site.ini
$language =& eZLanguage::instance();
$locale =& eZLocale::instance();

// Set the language for the locale, this loads some of the textual translations for dates and times.
$locale->setLanguage( $language );
// Make sure PHP is to the correct locale
$locale->initPHP();

print( $locale->formatTime() . '<br>' ); // Display current time
print( $locale->formatDate() . '<br>' ); // Display current day

foreach ( $locale->weekDays() as $day ) // Print a week with 3 letter daynames
{
    print( $locale->shortDayName( $day ) . '<br>' );
}
\endcode

Countries are specified by the ISO 3166 Country Code
http://www.iso.ch/iso/en/prods-services/iso3166ma/index.html
User-assigned code elements
http://www.iso.ch/iso/en/prods-services/iso3166ma/04background-on-iso-3166/reserved-and-user-assigned-codes.html#userassigned


Language is specified by the ISO 639 Language Code
http://www.w3.org/WAI/ER/IG/ert/iso639.htm

Currency/funds are specified by the ISO 4217
http://www.bsi-global.com/Technical+Information/Publications/_Publications/tig90.xalter


Discussion on Norwegian locales
https://lister.ping.uio.no/pipermail/lister.ping.uio.no/i18n-nn/2002-April.txt
http://www.sprakrad.no/oss.htm


\sa eZLanguage
*/

include_once( 'lib/ezutils/classes/ezini.php' );

class eZLocale
{
    /*!
     Initializes the locale with the locale string \a $localeString.
     All locale data is read from locale/$localeString.ini
    */
    function eZLocale( $localeString )
    {
        $this->TimePHPArray = array( 'g', 'G', 'h', 'H', 'i', 's', 'U', 'l' );
        $this->DatePHPArray = array( 'd', 'j', 'm', 'n', 'O', 'T', 'U', 'w', 'W', 'Y', 'y', 'z', 'Z' );
        $this->DateTimePHPArray = array( 'd', 'j', 'm', 'n', 'O', 'T', 'U', 'w', 'W', 'Y', 'y', 'z', 'Z',
                                         'g', 'G', 'h', 'H', 'i', 's', 'U', 'l' );
        $this->TimeArray = preg_replace( '/.+/', '%$0', $this->TimePHPArray );
        $this->DateArray = preg_replace( '/.+/', '%$0', $this->DatePHPArray );
        $this->DateTimeArray = preg_replace( '/.+/', '%$0', $this->DateTimePHPArray );
        $this->TimeSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->TimePHPArray );
        $this->DateSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->DatePHPArray );
        $this->DateTimeSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->DateTimePHPArray );
        $this->TimeSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->TimePHPArray );
        $this->DateSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->DatePHPArray );
        $this->DateTimeSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->DateTimePHPArray );

        $this->DayNames = array( 0 => 'sun', 1 => 'mon', 2 => 'tue',
                                 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat' );
        $this->MonthNames = array( 1 => 'jan', 2 => 'feb', 3 => 'mar',
                                   4 => 'apr', 5 => 'may', 6 => 'jun',
                                   7 => 'jul', 8 => 'aug', 9 => 'sep',
                                   10 => 'oct', 11 => 'nov', 12 => 'dec' );
        $this->WeekDays = array( 0, 1, 2, 3, 4, 5 );
        $this->Months = array( 1, 2, 3, 4, 5, 6,
                               7, 8, 9, 10, 11, 12 );

        $locale = eZLocale::localeInformation( $localeString );

        $this->CountryCode =& $locale['country'];
        $this->LanguageCode =& $locale['language'];
        $this->LocaleCode =& $locale['locale'];

        $this->LocaleINI = null;
        $this->CountryINI = null;
        $this->LanguageINI = null;

        // Figure out if we use one locale file or separate country/language file.
        $localeINI =& $this->localeFile();
        $countryINI =& $localeINI;
        $languageINI =& $localeINI;
        if ( $localeINI === null )
        {
            $countryINI =& $this->countryFile();
            $languageINI =& $this->languageFile();
        }

        $this->reset();

        // Load country information
        if ( $countryINI !== null )
        {
            $this->initCountry( $countryINI );
        }
        else
            eZDebug::writeError( 'Could not load country settings for ' . $this->CountryCode, 'eZLocale' );

        if ( $this->MondayFirst )
            $this->WeekDays = array( 1, 2, 3, 4, 5, 6, 0 );
        else
            $this->WeekDays = array( 0, 1, 2, 3, 4, 5 );

        // Load language information
        if ( $languageINI !== null )
        {
            $this->initLanguage( $languageINI );
        }
        else
            eZDebug::writeError( 'Could not load language settings for ' . $this->LanguageCode, 'eZLocale' );

        $this->AM = 'am';
        $this->PM = 'pm';
    }

    /*!
     \private
    */
    function reset()
    {
        $this->TimeFormat = '';
        $this->ShortTimeFormat = '';
        $this->DateFormat = '';
        $this->ShortDateFormat = '';
        $this->DateTimeFormat = '';
        $this->ShortDateTimeFormat = '';

        $this->MondayFirst = false;;

        $this->Country = '';

        $this->DecimalSymbol = '';
        $this->ThousandsSeparator = '';
        $this->FractDigits = 2;
        $this->NegativeSymbol = '-';
        $this->PositiveSymbol = '';

        $this->CurrencyDecimalSymbol = '';
        $this->CurrencyName = '';
        $this->CurrencyShortName = '';
        $this->CurrencyThousandsSeparator = '';
        $this->CurrencyFractDigits = 2;
        $this->CurrencyNegativeSymbol = '-';
        $this->CurrencyPositiveSymbol = '';
        $this->CurrencySymbol = '';
        $this->CurrencyPositiveFormat = '';
        $this->CurrencyNegativeFormat = '';

        $this->LanguageName = '';
        $this->IntlLanguageName = '';

        $this->ShortDayNames = array();
        $this->LongDayNames = array();
        foreach ( $this->DayNames as $day )
        {
            $this->ShortDayNames[$day] = '';
            $this->LongDayNames[$day] = '';
        }

        $this->ShortMonthNames = array();
        $this->LongMonthNames = array();
        foreach ( $this->MonthNames as $month )
        {
            $this->ShortMonthNames[$month] = '';
            $this->LongMonthNames[$month] = '';
        }

        $this->ShortWeekDayNames = array();
        $this->LongWeekDayNames = array();
        foreach( $this->WeekDays as $wday )
        {
            $code = $this->DayNames[$wday];
            $this->ShortWeekDayNames[$wday] = '';
            $this->LongWeekDayNames[$wday] = '';
        }
    }

    /*!
     \private
    */
    function initCountry( &$countryINI )
    {
        $this->TimeFormat = $countryINI->variable( 'DateTime', 'TimeFormat' );
        $this->ShortTimeFormat = $countryINI->variable( 'DateTime', 'ShortTimeFormat' );
        $this->DateFormat = $countryINI->variable( 'DateTime', 'DateFormat' );
        $this->ShortDateFormat = $countryINI->variable( 'DateTime', 'ShortDateFormat' );
        $this->DateTimeFormat = $countryINI->variable( 'DateTime', 'DateTimeFormat' );
        $this->ShortDateTimeFormat = $countryINI->variable( 'DateTime', 'ShortDateTimeFormat' );

        $this->MondayFirst = strtolower( $countryINI->variable( 'DateTime', 'MondayFirst' ) ) == 'yes';

        $this->Country = $countryINI->variable( 'RegionalSettings', 'Country' );

        $this->DecimalSymbol = $countryINI->variable( 'Numbers', 'DecimalSymbol' );
        $this->ThousandsSeparator = $countryINI->variable( 'Numbers', 'ThousandsSeparator' );
        $this->FractDigits = $countryINI->variable( 'Numbers', 'FractDigits' );
        $this->NegativeSymbol = $countryINI->variable( 'Numbers', 'NegativeSymbol' );
        $this->PositiveSymbol = $countryINI->variable( 'Numbers', 'PositiveSymbol' );

        $this->CurrencyDecimalSymbol = $countryINI->variable( 'Currency', 'DecimalSymbol' );
        $this->CurrencyName = $countryINI->variable( 'Currency', 'Name' );
        $this->CurrencyShortName = $countryINI->variable( 'Currency', 'ShortName' );
        $this->CurrencyThousandsSeparator = $countryINI->variable( 'Currency', 'ThousandsSeparator' );
        $this->CurrencyFractDigits = $countryINI->variable( 'Currency', 'FractDigits' );
        $this->CurrencyNegativeSymbol = $countryINI->variable( 'Currency', 'NegativeSymbol' );
        $this->CurrencyPositiveSymbol = $countryINI->variable( 'Currency', 'PositiveSymbol' );
        $this->CurrencySymbol = $countryINI->variable( 'Currency', 'Symbol' );
        $this->CurrencyPositiveFormat = $countryINI->variable( 'Currency', 'PositiveFormat' );
        $this->CurrencyNegativeFormat = $countryINI->variable( 'Currency', 'NegativeFormat' );
    }

    /*!
     \private
    */
    function initLanguage( &$languageINI )
    {
        $this->LanguageName =& $languageINI->variable( "RegionalSettings", "LanguageName" );
        $this->IntlLanguageName =& $languageINI->variable( "RegionalSettings", "InternationalLanguageName" );

        $this->ShortDayNames = array();
        $this->LongDayNames = array();
        foreach ( $this->DayNames as $day )
        {
            $this->ShortDayNames[$day] = $languageINI->variable( 'ShortDayNames', $day );
            $this->LongDayNames[$day] = $languageINI->variable( 'LongDayNames', $day );
        }

        $this->ShortMonthNames = array();
        $this->LongMonthNames = array();
        foreach ( $this->MonthNames as $month )
        {
            $this->ShortMonthNames[$month] = $languageINI->variable( 'ShortMonthNames', $month );
            $this->LongMonthNames[$month] = $languageINI->variable( 'LongMonthNames', $month );
        }

        $this->ShortWeekDayNames = array();
        $this->LongWeekDayNames = array();
        foreach( $this->WeekDays as $wday )
        {
            $code = $this->DayNames[$wday];
            $this->ShortWeekDayNames[$wday] = $languageINI->variable( 'ShortDayNames', $code );
            $this->LongWeekDayNames[$wday] = $languageINI->variable( 'LongDayNames', $code );
        }
    }

    /*!
     Decodes a locale string into language, country and charset and returns an array with the information.
     Country and charset is optional, country is specified with a - or _ followed by the country code (NO, GB),
     charset is specified with a . followed by the charset name.
     Examples of locale strings are: nor-NO, en_GB.utf8, nn_NO
    */
    function localeInformation( $localeString )
    {
        $info = null;
        if ( preg_match( '/^([a-zA-Z]+)([_-]([a-zA-Z]+))?(\.([a-zA-Z-]+))?/', $localeString, $regs ) )
        {
            $info = array();
            $language = strtolower( $regs[1] );
            $country = '';
            if ( isset( $regs[3] ) )
                $country = strtoupper( $regs[3] );
            $charset = '';
            if ( isset( $regs[5] ) )
                $charset = strtolower( $regs[5] );
            $locale = $language;
            if ( $country !== '' )
                $locale .= '-' . $country;
            $info['language'] = $language;
            $info['country'] = $country;
            $info['charset'] = $charset;
            $info['locale'] = $locale;
        }
        else
        {
            $info = array();
            $locale = strtolower( $localeString );
            $language = $locale;
            $info['language'] = $language;
            $info['country'] = '';
            $info['charset'] = '';
            $info['locale'] = $locale;
        }
        return $info;
    }

    /*!
     Sets locale information in PHP. This means that some of the string/sort functions in
     PHP will work with non-latin1 characters.
     Make sure setLanguage is called before this.
    */
    function initPHP( $charset = false )
    {
        if ( $charset === false )
            $charset = $this->Language->code();
        setlocale( LC_ALL, $charset );
    }

    /*!
     Returns the name of the country in British English.
    */
    function countryName()
    {
        return $this->Country;
    }

    /*!
     Returns the code for the country. eg. 'NO'
    */
    function countryCode()
    {
        return $this->CountryCode;
    }

    /*!
     Returns the language code for this language, for instance nor for norwegian or eng for english.
    */
    function languageCode()
    {
        return $this->LanguageCode;
    }

    /*!
     Returns the locale code for this language which is the language and the country with a dash (-) between them,
     for instance nor-NO or eng-GB.
    */
    function localeCode()
    {
        return $this->LocaleCode;
    }

    /*!
     \static
     Returns the current locale code for this language which is the language and the country with a dash (-) between them,
     for instance nor-NO or eng-GB.
     \sa localeCode, instance
    */
    function currentLocaleCode()
    {
        $locale =& eZLocale::instance();
        return $locale->localeCode();
    }

    /*!
     Returns the name of the language in its own tounge.
    */
    function languageName()
    {
        return $this->LanguageName;
    }

    /*!
     Returns the name of the language in English (eng).
    */
    function internationalLanguageName()
    {
        return $this->IntlLanguageName;
    }

    /*!
     Returns the currency symbol for this locale.
    */
    function currencySymbol()
    {
        return $this->CurrencySymbol;
    }

    /*!
     Returns the name of the currency.
    */
    function currencyName()
    {
        return $this->CurrencyName;
    }

    /*!
     Returns the short name of the currency.
    */
    function currencyShortName()
    {
        return $this->CurrencyShortName;
    }

    /*!
     Returns true if the week starts with monday, false if sunday.
     \sa weekDays()
    */
    function isMondayFirst()
    {
        return $this->MondayFirst;
    }

    /*!
     Returns an array with the days of the week according to locale information.
     Each entry in the array can be supplied to the shortDayName() and longDayName() functions.
     \sa isMondayFirst(), weekDayNames()
    */
    function weekDays()
    {
        return $this->WeekDays;
    }

    /*!
     Returns the months of the year as an array. This only supplied for completeness.
     \sa weekDays()
    */
    function months()
    {
        return $this->Months;
    }

    /*!
     Returns the same array as in weekDays() but with all days translated to text.
    */
    function weekDayNames( $short = false )
    {
        if ( $short )
            return $this->ShortWeekDayNames;
        else
            return $this->LongWeekDayNames;
    }

    /*!
     Formats the time $time according to locale information and returns it. If $time
     is not specified the current time is used.
    */
    function &formatTime( $time = false )
    {
        return $this->formatTimeType( $this->TimeFormat, $time );
    }

    /*!
     Formats the time $time according to locale information for short times and returns it. If $time
     is not specified the current time is used.
    */
    function &formatShortTime( $time = false )
    {
        return $this->formatTimeType( $this->ShortTimeFormat, $time );
    }

    /*!
     Formats the time $time according to the format $fmt. You shouldn't call this
     directly unless you want to deviate from the locale settings.
     \sa formatTime(), formatShortTime()
    */
    function &formatTimeType( $fmt, $time = false )
    {
        if ( $time == false )
            $time =& mktime();

        $timeArray = $this->TimeArray;
        $timePHPArray = $this->TimePHPArray;
        $timeSlashInputArray = $this->TimeSlashInputArray;
        $timeSlashOutputArray = $this->TimeSlashOutputArray;
        $fmt =& preg_replace( $timeSlashInputArray, $timeSlashOutputArray, $fmt );
        $fmt =& str_replace( $timeArray, $timePHPArray, $fmt );
        $fmt =& str_replace( '%', "%\\", $fmt );
        $text =& date( $fmt, $time );
        $text =& str_replace( array( '%a', '%A' ),
                              array( $this->meridiemName( $time, false ),
                                     $this->meridiemName( $time, true ) ),
                              $text );
        return $text;
    }

    /*!
     Returns the name for the meridiem ie am (ante meridiem) or pm (post meridiem).
     If $time is not supplied or false the current time is used. If $upcase is false
     the name is in lowercase otherwise uppercase.
     The time is defined to be am if the hour is less than 12 and pm otherwise. Normally
     the hours 00 and 12 does not have am/pm attached and are instead called Midnight and Noon,
     but for simplicity the am/pm is always attached (if the locale allows it).
    */
    function &meridiemName( $time = false, $upcase = false )
    {
        if ( $time == false )
            $time =& mktime();
        $hour =& date( 'G', $time );
        $name = $hour < 12 ? $this->AM : $this->PM;
        if ( $upcase )
            $name =& strtoupper( $name );
        return $name;
    }

    /*!
     Formats the date $date according to locale information and returns it. If $date
     is not specified the current date is used.
    */
    function &formatDate( $date = false )
    {
        return $this->formatDateType( $this->DateFormat, $date );
    }

    /*!
     Formats the date $date according to locale information for short dates and returns it. If $date
     is not specified the current date is used.
    */
    function &formatShortDate( $date = false )
    {
        return $this->formatDateType( $this->ShortDateFormat, $date );
    }

    /*!
     Formats the date and time $date according to locale information and returns it. If $date
     is not specified the current date is used.
    */
    function &formatDateTime( $date = false )
    {
        return $this->formatDateTimeType( $this->DateTimeFormat, $date );
    }

    /*!
     Formats the date and time $date according to locale information for short dates and returns it.
     If $date is not specified the current date is used.
    */
    function &formatShortDateTime( $date = false )
    {
        return $this->formatDateTimeType( $this->ShortDateTimeFormat, $date );
    }

    /*!
     Formats the date $date according to the format $fmt. You shouldn't call this
     directly unless you want to deviate from the locale settings.
     \sa formatDate(), formatShortDate()
    */
    function &formatDateType( $fmt, $date = false )
    {
        if ( $date === false )
            $date =& mktime();

        $dateArray = $this->DateArray;
        $datePHPArray = $this->DatePHPArray;
        $dateSlashInputArray = $this->DateSlashInputArray;
        $dateSlashOutputArray = $this->DateSlashOutputArray;
        $fmt =& preg_replace( $dateSlashInputArray, $dateSlashOutputArray, $fmt );
        $fmt =& str_replace( $dateArray, $datePHPArray, $fmt );
        $fmt =& str_replace( '%', "%\\", $fmt );
        $text =& date( $fmt, $date );
        $text =& str_replace( array( '%D', '%l', '%M', '%F' ),
                              array( $this->shortDayName( date( 'w', $date ) ),
                                     $this->longDayName( date( 'w', $date ) ),
                                     $this->shortMonthName( date( 'n', $date ) ),
                                     $this->longMonthName( date( 'n', $date ) ) ),
                              $text );
        return $text;
    }

    /*!
     Formats the date and time \a $datetime according to the format \a $fmt.
     You shouldn't call this directly unless you want to deviate from the locale settings.
     \sa formatDateTime(), formatShortDateTime()
    */
    function &formatDateTimeType( $fmt, $datetime = false )
    {
        if ( $datetime === false )
            $datetime =& mktime();

        $dateTimeArray = $this->DateTimeArray;
        $dateTimePHPArray = $this->DateTimePHPArray;
        $dateTimeSlashInputArray = $this->DateTimeSlashInputArray;
        $dateTimeSlashOutputArray = $this->DateTimeSlashOutputArray;
        $fmt =& preg_replace( $dateTimeSlashInputArray, $dateTimeSlashOutputArray, $fmt );
        $fmt =& str_replace( $dateTimeArray, $dateTimePHPArray, $fmt );
        $fmt =& str_replace( '%', "%\\", $fmt );
        $text =& date( $fmt, $datetime );
        $text =& str_replace( array( '%D', '%l', '%M', '%F',
                                     '%a', '%A' ),
                              array( $this->shortDayName( date( 'w', $datetime ) ),
                                     $this->longDayName( date( 'w', $datetime ) ),
                                     $this->shortMonthName( date( 'n', $datetime ) ),
                                     $this->longMonthName( date( 'n', $datetime ) ),
                                     $this->meridiemName( $datetime, false ),
                                     $this->meridiemName( $datetime, true ) ),
                              $text );
        return $text;
    }

    /*!
     Formats the number $number according to locale information and returns it.
    */
    function &formatNumber( $number )
    {
        $neg = $number < 0;
        $num = $neg ? -$number : $number;
        $text =& number_format( $num, $this->FractDigits, $this->DecimalSymbol, $this->ThousandsSeparator );
        $text = ( $neg ? $this->NegativeSymbol : $this->PositiveSymbol ) . $text;
        return $text;
    }

    /*!
     Formats the currency $number according to locale information and returns it. If $as_html
     is true all spaces are converted to &nbsp; before being returned.
    */
    function &formatCurrency( $number, $as_html = true )
    {
        $neg = $number < 0;
        $num = $neg ? -$number : $number;
        $num_text =& number_format( $num, $this->CurrencyFractDigits,
                                    $this->CurrencyDecimalSymbol, $this->CurrencyThousandsSeparator );
        $text =& str_replace( array( '%c', '%p', '%q' ),
                              array( $this->CurrencySymbol,
                                     $neg ? $this->CurrencyNegativeSymbol : $this->CurrencyPositiveSymbol,
                                     $num_text ),
                              $neg ? $this->CurrencyNegativeFormat : $this->CurrencyPositiveFormat );
//         if ( $as_html )
//             $text =& str_replace( ' ', '&nbsp;', $text );
        return $text;
    }

    /*!
     Returns the short name of the day number $num.
     The different numbers for the days are:
     Sunday    = 0
     Monday    = 1
     Tuesday   = 2
     Wednesday = 3
     Thursday  = 4
     Friday    = 5
     Saturday  = 6
     This functions is usually used together with weekDays().
     \sa longDayName()
    */
    function &shortDayName( $num )
    {
        if ( $num >= 0 and $num <= 6 )
        {
            $code =& $this->DayNames[$num];
            $name = $this->ShortDayNames[$code];
        }
        else
        {
            $name = null;
        }
        return $name;
    }

    /*!
     Returns the long name of the day number $num.
     The different numbers for the days are:
     Sunday    = 0
     Monday    = 1
     Tuesday   = 2
     Wednesday = 3
     Thursday  = 4
     Friday    = 5
     Saturday  = 6
     This functions is usually used together with weekDays().
     \sa shortDayName()
    */
    function &longDayName( $num )
    {
        if ( $num >= 0 and $num <= 6 )
        {
            $code =& $this->DayNames[$num];
            $name = $this->LongDayNames[$code];
        }
        else
        {
            $name = null;
        }
        return $name;
    }

    /*!
     Returns the short name of the month number $num.
     The different numbers for the months are:
     Januray   = 1
     February  = 2
     March     = 3
     April     = 4
     May       = 5
     June      = 6
     July      = 7
     August    = 8
     September = 9
     October   = 10
     November  = 11
     December  = 12
     This functions is usually used together with months().
     \sa longMonthName()
    */
    function &shortMonthName( $num )
    {
        if ( $num >= 1 and $num <= 12 )
        {
            $code =& $this->MonthNames[$num];
            $name = $this->ShortMonthNames[$code];
        }
        else
        {
            $name = null;
        }
        return $name;
    }

    /*!
     Returns the long name of the month number $num.
     The different numbers for the months are:
     Januray   = 1
     February  = 2
     March     = 3
     April     = 4
     May       = 5
     June      = 6
     July      = 7
     August    = 8
     September = 9
     October   = 10
     November  = 11
     December  = 12
     This functions is usually used together with months().
     \sa shortMonthName()
    */
    function &longMonthName( $num )
    {
        if ( $num >= 1 and $num <= 12 )
        {
            $code =& $this->MonthNames[$num];
            $name = $this->LongMonthNames[$code];
        }
        else
        {
            $name = null;
        }
        return $name;
    }

    function localeList()
    {
        $locales =& $GLOBALS['eZLocaleLocaleStringList'];
        if ( !is_array( $locales ) )
        {
            $locales = array();
            $dir = opendir( 'share/locale' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^(.+)\.ini$/", $file, $regs ) )
                {
                    $locales[] = $regs[1];
                }
            }
            closedir( $dir );
            sort( $locales );
        }
        return $locales;
    }

    function countryList()
    {
        $countries =& $GLOBALS['eZLocaleCountryList'];
        if ( !is_array( $countries ) )
        {
            $countries = array();
            $dir = opendir( 'share/locale/country' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^(.+)\.ini$/", $file, $regs ) )
                {
                    $countries[] = $regs[1];
                }
            }
            closedir( $dir );
            sort( $countries );
        }
        return $countries;
    }

    function languageList()
    {
        $languages =& $GLOBALS['eZLocaleLanguageist'];
        if ( !is_array( $languages ) )
        {
            $languages = array();
            $dir = opendir( 'share/locale/language' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^(.+)\.ini$/", $file, $regs ) )
                {
                    $languages[] = $regs[1];
                }
            }
            closedir( $dir );
            sort( $languages );
        }
        return $languages;
    }

    /*!
     Returns the eZINI object for the locale ini file.
     \warning Do not modify this object.
    */
    function &localeFile()
    {
        if ( get_class( $this->LocaleINI ) != 'ezini' )
        {
            $country = $this->countryCode();
            $language = $this->languageCode();
            $locale = $language;
            if ( $country !== '' )
                $locale .= '-' . $country;
            $localeFile = $locale . '.ini';
            eZDebug::writeNotice( "Trying $localeFile" );
            if ( eZINI::exists( $localeFile, 'share/locale' ) )
                $this->LocaleINI = eZINI::instance( $localeFile, 'share/locale' );
        }
        return $this->LocaleINI;
    }

    /*!
     Returns the eZINI object for the country ini file.
     \warning Do not modify this object.
    */
    function &countryFile()
    {
        if ( get_class( $this->CountryINI ) != 'ezini' )
        {
            $country = $this->countryCode();
            $countryFile = 'country/' . $country . '.ini';
            if ( eZINI::exists( $countryFile, 'share/locale' ) )
                $this->CountryINI = eZINI::instance( $countryFile, 'share/locale' );
        }
        return $this->CountryINI;
    }

    /*!
     Returns the eZINI object for the language ini file.
     \warning Do not modify this object.
    */
    function &languageFile()
    {
        if ( get_class( $this->LanguageINI ) != 'ezini' )
        {
            $language = $this->languageCode();
            $languageFile = 'language/' . $language . '.ini';
            if ( eZINI::exists( $languageFile, 'share/locale' ) )
                $this->LanguageINI = eZINI::instance( $languageFile, 'share/locale' );
        }
        return $this->LanguageINI;
    }

    /*!
     Returns an unique instance of the locale class for a given locale string. If $localeString is not
     specified the default local string in site.ini is used.
     Use this instead of newing eZLocale to benefit from speed and unified access.
    */
    function &instance( $localeString = false )
    {
        if ( $localeString === false )
        {
            $ini =& eZINI::instance();
            $localeString =& $ini->variable( 'RegionalSettings', 'Locale' );
        }
        $instance =& $GLOBALS["eZLocaleInstance_$localeString"];
        if ( get_class( $instance ) != 'ezlocale' )
        {
            $instance = new eZLocale( $localeString );
        }
        return $instance;
    }

    //@{
    /// Format of dates
    var $DateFormat;
    /// Format of short dates
    var $ShortDateFormat;
    /// Format of times
    var $TimeFormat;
    /// Format of short times
    var $ShortTimeFormat;
    /// True if monday is the first day of the week
    var $MondayFirst;
    /// AM and PM names
    var $AM, $PM;
    //@}

    //@{
    /// Numbers
    var $DecimalSymbol;
    var $ThousandsSeparator;
    var $FractDigits;
    var $NegativeSymbol;
    var $PositiveSymbol;
    //@}

    //@{
    /// Currency
    var $CurrencyDecimalSymbol;
    var $CurrencyThousandsSeparator;
    var $CurrencyFractDigits;
    var $CurrencyNegativeSymbol;
    var $CurrencyPositiveSymbol;
    var $CurrencySymbol;
    var $CurrencyPositiveFormat;
    var $CurrencyNegativeFormat;
    //@}

    //@{
    /// Help arrays
    var $DayNames;
    var $ShortDayNames, $LongDayNames;
    var $MonthNames;
    var $ShortMonthNames, $LongMonthNames;
    var $WeekDays, $Months;
    var $ShortWeekDayNames, $LongWeekDayNames;

    var $TimeArray;
    var $DateArray;
    var $TimePHPArray;
    var $DatePHPArray;

    //@}

    //@{
    /// Objects
    var $Country;
    var $CountryCode;
    var $LocaleINI;
    var $CountryINI;
    var $LanguageINI;
    /// The language code, for instance nor-NO, or eng-GB
    var $LanguageCode;
    /// Name of the language
    var $LanguageName;
    /// Internationalized name of the language
    var $IntlLanguageName;
    //@}
};

?>
