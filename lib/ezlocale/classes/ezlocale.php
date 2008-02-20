<?php
//
// Definition of eZLocale class
//
// Created on: <01-Mar-2002 13:48:32 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
//include_once( 'lib/ezlocale/classes/ezlocale.php' );

// Fetch the default values supplied by site.ini
$locale = eZLocale::instance();

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


The date and time formats are quite similar to the builting PHP date function,
the main differences are those which returns textual representations of months
and days.
More info on the date function here:
http://www.php.net/manual/en/function.date.php

The following characters are not recognized in the format string:

- B - Swatch Internet time
- r - RFC 822 formatted date; e.g. "Thu, 21 Dec 2000 16:01:07 +0200" (added in PHP 4.0.4)
- S - English ordinal suffix for the day of the month, 2 characters; i.e. "st", "nd", "rd" or "th"

The following characters are recognized in the format string:

- a - "am" or "pm"
- A - "AM" or "PM"
- d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
- D - day of the week, textual, 3 letters; e.g. "Fri"
- F - month, textual, long; e.g. "January"
- g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
- G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
- h - hour, 12-hour format; i.e. "01" to "12"
- H - hour, 24-hour format; i.e. "00" to "23"
- i - minutes; i.e. "00" to "59"
- I (capital i) - "1" if Daylight Savings Time, "0" otherwise.
- j - day of the month without leading zeros; i.e. "1" to "31"
- l (lowercase 'L') - day of the week, textual, long; e.g. "Friday"
- L - boolean for whether it is a leap year; i.e. "0" or "1"
- m - month; i.e. "01" to "12"
- M - month, textual, 3 letters; e.g. "Jan"
- n - month without leading zeros; i.e. "1" to "12"
- O - Difference to Greenwich time in hours; e.g. "+0200"
- s - seconds; i.e. "00" to "59"
- t - number of days in the given month; i.e. "28" to "31"
- T - Timezone setting of this machine; e.g. "EST" or "MDT"
- U - seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
- w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
- W - ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0)
- Y - year, 4 digits; e.g. "1999"
- y - year, 2 digits; e.g. "99"
- z - day of the year; i.e. "0" to "365"
- Z - timezone offset in seconds (i.e. "-43200" to "43200"). The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.

\sa eZLanguage
*/

//include_once( 'lib/ezutils/classes/ezini.php' );

class eZLocale
{
    const DEBUG_INTERNALS = false;

    /*!
     Initializes the locale with the locale string \a $localeString.
     All locale data is read from locale/$localeString.ini
    */
    function eZLocale( $localeString )
    {
        $this->IsValid = false;
        $this->TimePHPArray = array( 'g', 'G', 'h', 'H', 'i', 's', 'U', 'I', 'L', 't' );
        $this->DatePHPArray = array( 'd', 'j', 'm', 'n', 'O', 'T', 'U', 'w', 'W', 'Y', 'y', 'z', 'Z', 'I', 'L', 't' );
        $this->DateTimePHPArray = array( 'd', 'j', 'm', 'n', 'O', 'T', 'U', 'w', 'W', 'Y', 'y', 'z', 'Z',
                                         'g', 'G', 'h', 'H', 'i', 's', 'U', 'I', 'L', 't', 'a' );
        $this->TimeArray = preg_replace( '/.+/', '%$0', $this->TimePHPArray );
        $this->DateArray = preg_replace( '/.+/', '%$0', $this->DatePHPArray );
        $this->DateTimeArray = preg_replace( '/.+/', '%$0', $this->DateTimePHPArray );

        $this->TimeSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->TimePHPArray );
        $this->DateSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->DatePHPArray );
        $this->DateTimeSlashInputArray = preg_replace( '/.+/', '/(?<!%)$0/', $this->DateTimePHPArray );

        $this->TimeSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->TimePHPArray );
        $this->DateSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->DatePHPArray );
        $this->DateTimeSlashOutputArray = preg_replace( '/.+/', '\\\\$0', $this->DateTimePHPArray );

        $this->HTTPLocaleCode = '';
        $this->functionMap = array(
            'time' => 'formatTime',
            'shorttime' => 'formatShortTime',
            'date' => 'formatDate',
            'shortdate' => 'formatShortDate',
            'datetime' => 'formatDateTime',
            'shortdatetime' => 'formatShortDateTime',
            'currency' => 'formatCurrencyWithSymbol',
            'clean_currency' => 'formatCleanCurrency',
            'number' => 'formatNumber',
        );

        $this->DayNames = array( 0 => 'sun', 1 => 'mon', 2 => 'tue',
                                 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat' );
        $this->MonthNames = array( 1 => 'jan', 2 => 'feb', 3 => 'mar',
                                   4 => 'apr', 5 => 'may', 6 => 'jun',
                                   7 => 'jul', 8 => 'aug', 9 => 'sep',
                                   10 => 'oct', 11 => 'nov', 12 => 'dec' );
        $this->WeekDays = array( 0, 1, 2, 3, 4, 5, 6 );
        $this->Months = array( 1, 2, 3, 4, 5, 6,
                               7, 8, 9, 10, 11, 12 );

        $locale = eZLocale::localeInformation( $localeString );

        $this->CountryCode = $locale['country'];
        $this->CountryVariation = $locale['country-variation'];
        $this->LanguageCode = $locale['language'];
        $this->LocaleCode = $locale['locale'];
        $this->TranslationCode = $locale['locale'];
        $this->Charset = $locale['charset'];
        $this->OverrideCharset = $locale['charset'];

        $this->LocaleINI = array( 'default' => null, 'variation' => null );
        $this->CountryINI = array( 'default' => null, 'variation' => null );
        $this->LanguageINI = array( 'default' => null, 'variation' => null );

        // Figure out if we use one locale file or separate country/language file.
        $localeINI = $this->localeFile();
        $countryINI = $localeINI;
        $languageINI = $localeINI;
        if ( $localeINI === null )
        {
            $countryINI = $this->countryFile();
            $languageINI = $this->languageFile();
        }

        $this->reset();

        $this->IsValid = true;

        // Load country information
        if ( $countryINI !== null )
        {
            $this->initCountry( $countryINI );
        }
        else
        {
            $this->IsValid = false;
            eZDebug::writeError( 'Could not load country settings for ' . $this->CountryCode, 'eZLocale' );
        }

        // Load language information
        if ( $languageINI !== null )
        {
            $this->initLanguage( $languageINI );
        }
        else
        {
            $this->IsValid = false;
            eZDebug::writeError( 'Could not load language settings for ' . $this->LanguageCode, 'eZLocale' );
        }


        // Load variation if any
        if ( $this->countryVariation() )
        {
            $localeVariationINI = $this->localeFile( true );
            $countryVariationINI = $localeVariationINI;
            $languageVariationINI = $localeVariationINI;
            if ( $localeVariationINI === null )
            {
                $countryVariationINI = $this->countryFile( true );
                $languageVariationINI = $this->languageFile( true );
            }

            // Load country information
            if ( $countryVariationINI !== null )
            {
                $this->initCountry( $countryVariationINI );
            }

            // Load language information
            if ( $languageVariationINI !== null )
            {
                $this->initLanguage( $languageVariationINI );
            }
        }

        if ( $this->MondayFirst )
        {
            $this->WeekDays[] = array_shift( $this->WeekDays );
        }

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

        $this->MondayFirst = false;

        $this->Country = '';
        $this->CountryComment = '';

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
        $this->CountryNames = '';

        $this->LanguageName = '';
        $this->LanguageComment = '';
        $this->IntlLanguageName = '';
        $this->AllowedCharsets = array();

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
     \return true if the locale is valid, ie the locale file could be loaded.
    */
    function isValid()
    {
        return $this->IsValid;
    }

    /*!
     \private
    */
    function initCountry( &$countryINI )
    {
        $countryINI->assign( 'DateTime', 'TimeFormat', $this->TimeFormat );
        $countryINI->assign( 'DateTime', 'ShortTimeFormat', $this->ShortTimeFormat );
        $countryINI->assign( 'DateTime', 'DateFormat', $this->DateFormat );
        $countryINI->assign( 'DateTime', 'ShortDateFormat', $this->ShortDateFormat );
        $countryINI->assign( 'DateTime', 'DateTimeFormat', $this->DateTimeFormat );
        $countryINI->assign( 'DateTime', 'ShortDateTimeFormat', $this->ShortDateTimeFormat );

        if ( $countryINI->hasVariable( 'DateTime', 'MondayFirst' ) )
            $this->MondayFirst = strtolower( $countryINI->variable( 'DateTime', 'MondayFirst' ) ) == 'yes';

        $countryINI->assign( 'RegionalSettings', 'Country', $this->Country );
        $countryINI->assign( "RegionalSettings", "CountryComment", $this->CountryComment );

        $countryINI->assign( 'Numbers', 'DecimalSymbol', $this->DecimalSymbol );
        $countryINI->assign( 'Numbers', 'ThousandsSeparator', $this->ThousandsSeparator );
        $countryINI->assign( 'Numbers', 'FractDigits', $this->FractDigits );
        $countryINI->assign( 'Numbers', 'NegativeSymbol', $this->NegativeSymbol );
        $countryINI->assign( 'Numbers', 'PositiveSymbol', $this->PositiveSymbol );

        $countryINI->assign( 'Currency', 'DecimalSymbol', $this->CurrencyDecimalSymbol );
        $countryINI->assign( 'Currency', 'Name', $this->CurrencyName );
        $countryINI->assign( 'Currency', 'ShortName', $this->CurrencyShortName );
        $countryINI->assign( 'Currency', 'ThousandsSeparator', $this->CurrencyThousandsSeparator );
        $countryINI->assign( 'Currency', 'FractDigits', $this->CurrencyFractDigits );
        $countryINI->assign( 'Currency', 'NegativeSymbol', $this->CurrencyNegativeSymbol );
        $countryINI->assign( 'Currency', 'PositiveSymbol', $this->CurrencyPositiveSymbol );
        $countryINI->assign( 'Currency', 'Symbol', $this->CurrencySymbol );
        $countryINI->assign( 'Currency', 'PositiveFormat', $this->CurrencyPositiveFormat );
        $countryINI->assign( 'Currency', 'NegativeFormat', $this->CurrencyNegativeFormat );
        if ( $countryINI->hasVariable( 'CountryNames', 'Countries' ) )
            $this->CountryNames = $countryINI->variable( 'CountryNames', 'Countries' );

    }

    /*!
     \private
    */
    function initLanguage( &$languageINI )
    {
        $languageINI->assign( "RegionalSettings", "LanguageName", $this->LanguageName );
        $languageINI->assign( "RegionalSettings", "InternationalLanguageName", $this->IntlLanguageName );
        $languageINI->assign( "RegionalSettings", "LanguageComment", $this->LanguageComment );
        $languageINI->assign( 'RegionalSettings', 'TranslationCode', $this->TranslationCode );

        if ( $this->OverrideCharset == '' )
        {
            $charset = false;
            if ( $languageINI->hasVariable( 'Charset', 'Preferred' ) )
            {
                $charset = $languageINI->variable( 'Charset', 'Preferred' );
                if ( $charset != '' )
                    $this->Charset = $charset;
            }
        }
        if ( $languageINI->hasVariable( 'Charset', 'Allowed' ) )
        {
            $this->AllowedCharsets = $languageINI->variable( 'Charset', 'Allowed' );
        }
        else
        {
            if ( $languageINI->hasVariable( 'Charset', 'Preferred' ) )
                $this->AllowedCharsets[] = $languageINI->variable( 'Charset', 'Preferred' );
        }


        if ( !is_array( $this->ShortDayNames ) )
            $this->ShortDayNames = array();
        if ( !is_array( $this->LongDayNames ) )
            $this->LongDayNames = array();

        $tmpShortDayNames = array();
        if ( $languageINI->hasGroup( 'ShortDayNames' ) )
            $tmpShortDayNames = $languageINI->variableMulti( 'ShortDayNames', $this->DayNames );
        $tmpLongDayNames = array();
        if ( $languageINI->hasGroup( 'LongDayNames' ) )
            $tmpLongDayNames = $languageINI->variableMulti( 'LongDayNames', $this->DayNames );
        foreach ( $this->DayNames as $key => $day )
        {
            if ( isset( $tmpShortDayNames[$key] ) )
                $this->ShortDayNames[$day] = $tmpShortDayNames[$key];
            if ( isset( $tmpLongDayNames[$key] ) )
                $this->LongDayNames[$day] = $tmpLongDayNames[$key];
        }


        if ( !is_array( $this->ShortMonthNames ) )
            $this->LongMonthNames = array();
        if ( !is_array( $this->ShortMonthNames ) )
            $this->LongMonthNames = array();

        $tmpShortMonthNames = array();
        if ( $languageINI->hasGroup( 'ShortMonthNames' ) )
            $tmpShortMonthNames = $languageINI->variableMulti( 'ShortMonthNames', $this->MonthNames );
        $tmpLongMonthNames = array();
        if ( $languageINI->hasGroup( 'LongMonthNames' ) )
            $tmpLongMonthNames = $languageINI->variableMulti( 'LongMonthNames', $this->MonthNames );
        foreach ( $this->MonthNames as $key => $day )
        {
            if ( isset( $tmpShortMonthNames[$key] ) )
                $this->ShortMonthNames[$day] = $tmpShortMonthNames[$key];
            if ( isset( $tmpLongMonthNames[$key] ) )
                $this->LongMonthNames[$day] = $tmpLongMonthNames[$key];
        }


        if ( !is_array( $this->ShortWeekDayNames ) )
            $this->ShortWeekDayNames = array();
        if ( !is_array( $this->LongWeekDayNames ) )
            $this->LongWeekDayNames = array();

        foreach ( $this->WeekDays as $key => $day )
        {
            if ( isset( $tmpShortDayNames[$key] ) )
                $this->ShortWeekDayNames[$day] = $tmpShortDayNames[$key];
            if ( isset( $tmpLongDayNames[$key] ) )
                $this->LongWeekDayNames[$day] = $tmpLongDayNames[$key];
        }

        if ( $languageINI->hasVariable( 'HTTP', 'ContentLanguage' ) )
            $this->HTTPLocaleCode = $languageINI->variable( 'HTTP', 'ContentLanguage' );
    }

    /*!
     \return a regexp which can be used for locale matching.
     The following groups are defiend
     - 1 - The language identifier
     - 2 - The separator and the country (3)
     - 3 - The country identifier
     - 4 - The separator and the charset (5)
     - 5 - The charset
     - 6 - The separator and the variation (7)
     - 7 - The variation
     \note The groyps 4 and 5 will only be used if \a $withCharset is \c true.
           The groups 6 and 7 will only be used if \a $withVariations is \c true.
     \param $withVariations If \c true it will include variations of locales (ends with @identifier)
    */
    static function localeRegexp( $withVariations = true, $withCharset = true )
    {
        return "([a-zA-Z]+)([_-]([a-zA-Z]+))?" . ( $withCharset ? "(\.([a-zA-Z-]+))?" : '' ) . ( $withVariations ? "(@([a-zA-Z0-9]+))?" : '' );
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
        if ( preg_match( '/^([a-zA-Z]+)([_-]([a-zA-Z]+))?(\.([a-zA-Z-]+))?(@([a-zA-Z0-9]+))?/', $localeString, $regs ) )
        {
            $info = array();
            $language = strtolower( $regs[1] );
            $country = '';
            if ( isset( $regs[3] ) )
                $country = strtoupper( $regs[3] );
            $charset = '';
            if ( isset( $regs[5] ) )
                $charset = strtolower( $regs[5] );
            $countryVariation = '';
            if ( isset( $regs[7] ) )
                $countryVariation = strtolower( $regs[7] );
            $locale = $language;
            if ( $country !== '' )
                $locale .= '-' . $country;
            $info['language'] = $language;
            $info['country'] = $country;
            $info['country-variation'] = $countryVariation;
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
            $info['country-variation'] = '';
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

    function attributes()
    {
        return array_keys( eZLocale::attributeFunctionMap() );
    }

    function hasAttribute( $attribute )
    {
        $attributeMap = eZLocale::attributeFunctionMap();
        if ( isset( $attributeMap[$attribute] ) )
            return true;
        else
            return false;
    }

    function attribute( $attribute )
    {
        $attributeMap = eZLocale::attributeFunctionMap();
        if ( isset( $attributeMap[$attribute] ) )
        {
            $method = $attributeMap[$attribute];
            if ( method_exists( $this, $method ) )
            {
                return $this->$method();
            }
            else
            {
                eZDebug::writeError( "Unknown method '$method' specified for attribute '$attribute'", 'eZLocale::attribute' );
                return null;
            }
        }

        eZDebug::writeError( "Attribute '$attribute' does not exist", 'eZLocale::attribute' );
        return null;
    }

    function attributeFunctionMap()
    {
        return array( 'charset' => 'charset',
                      'allowed_charsets' => 'allowedCharsets',
                      'country_name' => 'countryName',
                      'country_comment' => 'countryComment',
                      'country_code' => 'countryCode',
                      'country_variation' => 'countryVariation',
                      'language_name' => 'languageName',
                      'intl_language_name' => 'internationalLanguageName',
                      'language_code' => 'languageCode',
                      'language_comment' => 'languageComment',
                      'locale_code' => 'localeCode',
                      'locale_full_code' => 'localeFullCode',
                      'http_locale_code' => 'httpLocaleCode',
                      'decimal_symbol' => 'decimalSymbol',
                      'thousands_separator' => 'thousandsSeparator',
                      'decimal_count' => 'decimalCount',
                      'negative_symbol' => 'negativeSymbol',
                      'positive_symbol' => 'positiveSymbol',
                      'currency_decimal_symbol' => 'currencyDecimalSymbol',
                      'currency_thousands_separator' => 'currencyThousandsSeparator',
                      'currency_decimal_count' => 'currencyDecimalCount',
                      'currency_negative_symbol' => 'currencyNegativeSymbol',
                      'currency_positive_symbol' => 'currencyPositiveSymbol',
                      'currency_symbol' => 'currencySymbol',
                      'currency_name' => 'currencyName',
                      'currency_short_name' => 'currencyShortName',
                      'is_monday_first' => 'isMondayFirst',
                      'weekday_name_list' => 'weekDayNames',
                      'weekday_short_name_list' => 'weekDayShortNames',
                      'weekday_number_list' => 'weekDays',
                      'month_list' => 'months',
                      'month_name_list' => 'monthsNames',
                      'is_valid' => 'isValid' );
    }

    /*!
     Returns the charset for this locale.
     \note It returns an empty string if no charset was set from the locale file.
    */
    function charset()
    {
        return $this->Charset;
    }

    /*!
     \return an array with charsets that this locale can work with.
    */
    function allowedCharsets()
    {
        return $this->AllowedCharsets;
    }

    /*!
     Returns the name of the country in British English.
    */
    function countryName()
    {
        return $this->Country;
    }

    /*!
     Returns the comment for the country, if any.
    */
    function countryComment()
    {
        return $this->CountryComment;
    }

    /*!
     Returns the code for the country. eg. 'NO'
    */
    function countryCode()
    {
        return $this->CountryCode;
    }

    /*!
     Returns the variation for the country. eg. 'spraakraad'
    */
    function countryVariation()
    {
        return $this->CountryVariation;
    }

    /*!
     Returns the language code for this language, for instance nor for norwegian or eng for english.
    */
    function languageCode()
    {
        return $this->LanguageCode;
    }

    /*!
     Returns the comment for the language, if any.
    */
    function languageComment()
    {
        return $this->LanguageComment;
    }

    /*!
     Returns the locale code which is used for translation files. Normally this is the same as localeCode()
     but for some locales translation from other languages can be used

     e.g. por-MZ (Mozambique) uses por-PT for translation.
    */
    function translationCode()
    {
        return $this->TranslationCode;
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
     Same as localeCode() but appends the country variation if it is set.
    */
    function localeFullCode()
    {
        $locale = $this->LocaleCode;
        $variation = $this->countryVariation();
        if ( $variation )
            $locale .= "@" . $variation;
        return $locale;
    }

    /*!
     \return the locale code which can be set in either HTTP headers or the HTML file.
             The locale code is first check for in the RegionalSettings/HTTPLocale setting in site.ini,
             if that is empty it will use the value from localeCode().
     \sa localeCode
    */
    function httpLocaleCode()
    {
        $ini = eZINI::instance();
        $localeCode = '';
        if ( $ini->hasVariable( 'RegionalSettings', 'HTTPLocale' ) )
        {
            $localeCode = $ini->variable( 'RegionalSettings', 'HTTPLocale' );
        }
        if ( $localeCode == '' and
             $this->HTTPLocaleCode != '' )
            $localeCode = $this->HTTPLocaleCode;
        if ( $localeCode == '' )
            $localeCode = $this->localeCode();
        return $localeCode;
    }

    /*!
     \static
     Returns the current locale code for this language which is the language and the country with a dash (-) between them,
     for instance nor-NO or eng-GB.
     \sa localeCode, instance
    */
    static function currentLocaleCode()
    {
        $locale = eZLocale::instance();
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
     \return the decimal symbol for normal numbers.
    */
    function decimalSymbol()
    {
        return $this->DecimalSymbol;
    }

    /*!
     \return the thousand separator for normal numbers.
    */
    function thousandsSeparator()
    {
        return $this->ThousandsSeparator;
    }

    /*!
     \return the number of decimals for normal numbers.
    */
    function decimalCount()
    {
        return $this->FractDigits;
    }

    /*!
     \return the negative symbol for normal numbers.
    */
    function negativeSymbol()
    {
        return $this->NegativeSymbol;
    }

    /*!
     \return the positive symbol for normal numbers.
    */
    function positiveSymbol()
    {
        return $this->PositiveSymbol;
    }

    /*!
     \return the decimal symbol for currencies.
    */
    function currencyDecimalSymbol()
    {
        return $this->CurrencyDecimalSymbol;
    }

    /*!
     \return the thousand separator for currencies.
    */
    function currencyThousandsSeparator()
    {
        return $this->CurrencyThousandsSeparator;
    }

    /*!
     \return the number of decimals for currencies.
    */
    function currencyDecimalCount()
    {
        return $this->CurrencyFractDigits;
    }

    /*!
     \return the negative symbol for currencies.
    */
    function currencyNegativeSymbol()
    {
        return $this->CurrencyNegativeSymbol;
    }

    /*!
     \return the positive symbol for currencies.
    */
    function currencyPositiveSymbol()
    {
        return $this->CurrencyPositiveSymbol;
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
     \sa weekDays(), monthsNames()
    */
    function months()
    {
        return $this->Months;
    }

    /*!
     Returns the names of months as an array.
     \sa months()
    */
    function monthsNames()
    {
        return $this->LongMonthNames;
    }

    /*!
     Returns the same array as in weekDays() but with all days translated to text.
    */
    function weekDayNames( $short = false )
    {
        if ( $short )
            $dayList = $this->ShortWeekDayNames;
        else
            $dayList = $this->LongWeekDayNames;

        $resultDayList = array();
        foreach ( $this->WeekDays as $day )
        {
            $resultDayList[ $day ] = $dayList[ $day ];
        }
        return $resultDayList;
    }

    /*!
     Returns the same array as in weekDayNames() but with short version of days.
    */
    function weekDayShortNames()
    {
        return $this->weekDayNames( true );
    }

    /*!
     Returns the method name belonging to a qualifier
    */
    function getFormattingFunction( $qualifier )
    {
        if ( isset( $this->functionMap[$qualifier] ) )
        {
            return $this->functionMap[$qualifier];
        }
        else
        {
            return false;
        }
    }

    /*!
     Returns eZLocale object by HTTP locale code $httpLocaleCode
    */
    static function fetchByHttpLocaleCode( $httpLocaleCode )
    {
        $locales = self::localeList( true );
        foreach ( $locales as $locale )
        {
            if ( $locale->httpLocaleCode() == $httpLocaleCode )
                return $locale;
        }
        
        return false;
    }

    /*!
     Formats the time $time according to locale information and returns it. If $time
     is not specified the current time is used.
    */
    function formatTime( $time = false )
    {
        return $this->formatTimeType( $this->TimeFormat, $time );
    }

    /*!
     Formats the time $time according to locale information for short times and returns it. If $time
     is not specified the current time is used.
    */
    function formatShortTime( $time = false )
    {
        return $this->formatTimeType( $this->ShortTimeFormat, $time );
    }

    /*!
     Formats the time $time according to the format $fmt. You shouldn't call this
     directly unless you want to deviate from the locale settings.
     \sa formatTime(), formatShortTime()
    */
    function formatTimeType( $fmt, $time = false )
    {
        if ( $time == false )
            $time = time();

        $text = date( eZLocale::transformToPHPFormat( $fmt, $this->TimePHPArray ), $time );
        return  str_replace( array( '%a', '%A' ),
                             array( $this->meridiemName( $time, false ),
                                    $this->meridiemName( $time, true ) ),
                             $text );
    }

    /*!
     Returns the name for the meridiem ie am (ante meridiem) or pm (post meridiem).
     If $time is not supplied or false the current time is used. If $upcase is false
     the name is in lowercase otherwise uppercase.
     The time is defined to be am if the hour is less than 12 and pm otherwise. Normally
     the hours 00 and 12 does not have am/pm attached and are instead called Midnight and Noon,
     but for simplicity the am/pm is always attached (if the locale allows it).
    */
    function meridiemName( $time = false, $upcase = false )
    {
        if ( $time == false )
            $time = time();
        $hour = date( 'G', $time );
        $name = $hour < 12 ? $this->AM : $this->PM;
        if ( $upcase )
            $name = strtoupper( $name );
        return $name;
    }

    /*!
     Formats the date $date according to locale information and returns it. If $date
     is not specified the current date is used.
    */
    function formatDate( $date = false )
    {
        return $this->formatDateType( $this->DateFormat, $date );
    }

    /*!
     Formats the date $date according to locale information for short dates and returns it. If $date
     is not specified the current date is used.
    */
    function formatShortDate( $date = false )
    {
        return $this->formatDateType( $this->ShortDateFormat, $date );
    }

    /*!
     Formats the date and time $date according to locale information and returns it. If $date
     is not specified the current date is used.
    */
    function formatDateTime( $date = false )
    {
        return $this->formatDateTimeType( $this->DateTimeFormat, $date );
    }

    /*!
     Formats the date and time $date according to locale information for short dates and returns it.
     If $date is not specified the current date is used.
    */
    function formatShortDateTime( $date = false )
    {
        return $this->formatDateTimeType( $this->ShortDateTimeFormat, $date );
    }

    /*!
     Formats the date $date according to the format $fmt. You shouldn't call this
     directly unless you want to deviate from the locale settings.
     \sa formatDate(), formatShortDate()
    */
    function formatDateType( $fmt, $date = false )
    {
        if ( $date === false )
            $date = time();

        $text = date( eZLocale::transformToPHPFormat( $fmt, $this->DatePHPArray ), $date );
        return str_replace( array( '%D', '%l', '%M', '%F' ),
                            array( $this->shortDayName( date( 'w', $date ) ),
                                   $this->longDayName( date( 'w', $date ) ),
                                   $this->shortMonthName( date( 'n', $date ) ),
                                   $this->longMonthName( date( 'n', $date ) ) ),
                            $text );
    }

    /*!
     Formats the date and time \a $datetime according to the format \a $fmt.
     You shouldn't call this directly unless you want to deviate from the locale settings.
     \sa formatDateTime(), formatShortDateTime()
    */
    function formatDateTimeType( $fmt, $datetime = false )
    {
        if ( $datetime === false )
            $datetime = time();

        $text = date( eZLocale::transformToPHPFormat( $fmt, $this->DateTimePHPArray ), $datetime );
        // Replace some special 'date' formats that needs to be handled
        // internally by the i18n system and not by PHP
        return str_replace( array( '%D', '%l', '%M', '%F',
                                   '%a', '%A' ),
                            array( $this->shortDayName( date( 'w', $datetime ) ),
                                   $this->longDayName( date( 'w', $datetime ) ),
                                   $this->shortMonthName( date( 'n', $datetime ) ),
                                   $this->longMonthName( date( 'n', $datetime ) ),
                                   $this->meridiemName( $datetime, false ),
                                   $this->meridiemName( $datetime, true ) ),
                            $text );
    }

    /*!
     \private
     \static
     Transforms the date/time string \a $fmt into a string that can be
     passed to the PHP function 'date'.
     \param $fmt An eZ Publish locale format, %x means a 'formatting character' from PHPs 'date' function.
     \param $allowed An array with characters that are considered allowed 'formatting characters'
                     Any character not found in this array will be kept intact by escaping it.
     \sa http://www.php.net/manual/en/function.date.php
    */
    static function transformToPHPFormat( $fmt, $allowed )
    {
        // This goes trough each of the characters in the format
        // string $fmt, if a valid %x character is found it is replaced
        // with just x (like date expects).
        // It will also escape all characters in the range a-z and A-Z
        // expect does that are valid %x formats.
        // A special case is formats of the type %%, they will become %
        $dateFmt = '';
        $offs = 0;
        $len = strlen( $fmt );
        while ( $offs < $len )
        {
            $char = $fmt[$offs];
            if ( $char == '%' )
            {
                if ( $offs + 1 < $len )
                {
                    $type = $fmt[$offs + 1];
                    if ( $type == '%' )
                    {
                        $dateFmt .= '%';
                    }
                    else if ( ( $type >= 'a' and $type <= 'z' ) or
                              ( $type >= 'A' and $type <= 'Z' ) )
                    {
                        // Escape the $type character if it is not in
                        // the allowed 'format character' list
                        if ( !in_array( $type, $allowed ) )
                            $dateFmt .= '%\\';
                        $dateFmt .= $type;
                    }
                    else
                    {
                        $dateFmt .= $char . $type;
                    }
                    $offs += 2;
                }
                else
                {
                    $dateFmt .= '\\' . $char;
                    ++$offs;
                }
            }
            else
            {
                // Escape a-zA-Z to avoid PHPs 'date' using them as a 'format character'
                if ( ( $char >= 'a' and $char <= 'z' ) or
                     ( $char >= 'A' and $char <= 'Z' ) )
                    $dateFmt .= '\\';
                $dateFmt .= $char;
                ++$offs;
            }
        }
        return $dateFmt;
    }

    /*!
     Formats the number $number according to locale information and returns it.
    */
    function formatNumber( $number )
    {
        $neg = $number < 0;
        $num = $neg ? -$number : $number;
        $text = number_format( $num, $this->FractDigits, $this->DecimalSymbol, $this->ThousandsSeparator );
        return ( $neg ? $this->NegativeSymbol : $this->PositiveSymbol ) . $text;
    }

    /*!
     Formats the number according locale to the representation used internally in PHP
    */
    function internalNumber( $number )
    {
        if ( preg_match( '/^(['.$this->PositiveSymbol.']|['.$this->NegativeSymbol.'])?([0-9]*|[0-9]{1,3}(['.$this->ThousandsSeparator.'][0-9]{3,3})*)(['.$this->DecimalSymbol.'][0-9]+)?$/', trim( $number ) ) )
        {
            $number = str_replace( ' ', '', $number );
            if ( $this->PositiveSymbol )
                $number = str_replace( $this->PositiveSymbol, '', $number );
            $number = str_replace( $this->NegativeSymbol, '-', $number );
            $number = str_replace( $this->ThousandsSeparator, '', $number );
            $number = str_replace( $this->DecimalSymbol, '.', $number );
        }
        return $number;
    }

    /*!
     \deprecated
     Formats the currency $number according to locale information and returns it. If $as_html
     is true all spaces are converted to &nbsp; before being returned.
    */
    function formatCurrency( $number, $as_html = true )
    {
        return $this->formatCurrencyWithSymbol( $number, $this->CurrencySymbol );
    }

    /*!
      Formats the same as formatCurrency, but drops the currency sign

      \param currency input
    */
    function formatCleanCurrency( $number )
    {
        $text = $this->formatCurrencyWithSymbol( $number, '', true );
        return trim( $text );
    }

    function formatCurrencyWithSymbol( $number, $symbol )
    {
        $neg = $number < 0;
        $num = $neg ? -$number : $number;
        $num_text = number_format( $num, $this->CurrencyFractDigits,
                                   $this->CurrencyDecimalSymbol, $this->CurrencyThousandsSeparator );
        return str_replace( array( '%c', '%p', '%q' ),
                            array( $symbol,
                                   $neg ? $this->CurrencyNegativeSymbol : $this->CurrencyPositiveSymbol,
                                   $num_text ),
                            $neg ? $this->CurrencyNegativeFormat : $this->CurrencyPositiveFormat );
    }

    function translatedCountryNames()
    {
        return $this->CountryNames;
    }

    /*!
     Formats the currency according locale to the representation used internally in PHP
    */
    function internalCurrency( $number )
    {
        if ( preg_match( '/^(['.$this->CurrencyPositiveSymbol.']|['.$this->CurrencyNegativeSymbol.'])?([0-9]*|[0-9]{1,3}(['.$this->ThousandsSeparator.'][0-9]{3,3})*)(['.$this->CurrencyDecimalSymbol.'][0-9]+)?$/', trim( $number ) ) )
        {
            $number = str_replace( ' ', '', $number );
            if ( $this->CurrencyPositiveSymbol )
                $number = str_replace( $this->CurrencyPositiveSymbol, '', $number );
            $number = str_replace( $this->CurrencyNegativeSymbol, '-', $number );
            $number = str_replace( $this->CurrencyThousandsSeparator, '', $number );
            $number = str_replace( $this->CurrencyDecimalSymbol, '.', $number );
        }
        return $number;
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
    function shortDayName( $num )
    {
        if ( $num >= 0 and $num <= 6 )
        {
            $code = $this->DayNames[$num];
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
    function longDayName( $num )
    {
        if ( $num >= 0 and $num <= 6 )
        {
            $code = $this->DayNames[$num];
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
    function shortMonthName( $num )
    {
        if ( $num >= 1 and $num <= 12 )
        {
            $code = $this->MonthNames[$num];
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
    function longMonthName( $num )
    {
        if ( $num >= 1 and $num <= 12 )
        {
            $code = $this->MonthNames[$num];
            $name = $this->LongMonthNames[$code];
        }
        else
        {
            $name = null;
        }
        return $name;
    }

    /*!
     \static
     \return a list of locale objects which was found in the system.
     \param $asObject If \c true it returns each element as an eZLocale object
     \param $withVariations If \c true it will include variations of locales (ends with @identifier)
    */
    static function localeList( $asObject = false, $withVariations = true )
    {
        $locales =& $GLOBALS['eZLocaleLocaleStringList'];
        if ( !is_array( $locales ) )
        {
            $localeRegexp = eZLocale::localeRegexp( $withVariations, false );
            $locales = array();
            $dir = opendir( 'share/locale' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^($localeRegexp)\.ini$/", $file, $regs ) )
                {
                    $locales[] = $regs[1];
                }
            }
            closedir( $dir );
            $locales = array_unique( $locales );
            sort( $locales );
            if ( $asObject )
            {
                $localeObjects = array();
                foreach ( $locales as $locale )
                {
                    $localeInstance = eZLocale::instance( $locale );
                    if ( $localeInstance )
                        $localeObjects[] = $localeInstance;
                }
                $locales = $localeObjects;
            }
        }
        return $locales;
    }

    /*!
     \static
     \return a list of countries which was found in the system, the countries are in identifier form,
             for instance: NO, GB, US
     \param $withVariations If \c true it will include variations of locales (ends with @identifier)
    */
    static function countryList( $withVariations = true )
    {
        $countries =& $GLOBALS['eZLocaleCountryList'];
        if ( !is_array( $countries ) )
        {
            $localeRegexp = eZLocale::localeRegexp( $withVariations, false );
            $countries = array();
            $dir = opendir( 'share/locale' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^$localeRegexp\.ini$/", $file, $regs ) )
                {
                    $countries[] = $regs[3];
                }
            }
            closedir( $dir );
            sort( array_unique( $countries ) );
        }
        return $countries;
    }

    /*!
     \static
     \return a list of languages which was found in the system, the languages are in identifier form,
             for instance: nor, eng
     \param $withVariations If \c true it will include variations of locales (ends with @identifier)
    */
    static function languageList( $withVariations = true )
    {
        $languages =& $GLOBALS['eZLocaleLanguageist'];
        if ( !is_array( $languages ) )
        {
            $localeRegexp = eZLocale::localeRegexp( $withVariations, false );
            $languages = array();
            $dir = opendir( 'share/locale' );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( preg_match( "/^$localeRegexp\.ini$/", $file, $regs ) )
                {
                    $languages[] = $regs[1];
                }
            }
            closedir( $dir );
            sort( array_unique( $languages ) );
        }
        return $languages;
    }


    /*!
     Returns the eZINI object for the locale ini file.
     \warning Do not modify this object.
    */
    function &localeFile( $withVariation = false )
    {
        $type = $withVariation ? 'variation' : 'default';
        if ( !( $this->LocaleINI[$type] instanceof eZINI ) )
        {
            $country = $this->countryCode();
            $countryVariation = $this->countryVariation();
            $language = $this->languageCode();
            $locale = $language;
            if ( $country !== '' )
                $locale .= '-' . $country;
            if ( $withVariation )
            {
                if ( $countryVariation !== '' )
                    $locale .= '@' . $countryVariation;
            }
            $localeFile = $locale . '.ini';
            if ( eZLocale::isDebugEnabled() )
            {
                eZDebug::writeNotice( "Requesting $localeFile", 'eZLocale::localeFile' );
            }
            if ( eZINI::exists( $localeFile, 'share/locale' ) )
                $this->LocaleINI[$type] = eZINI::instance( $localeFile, 'share/locale' );
        }
        return $this->LocaleINI[$type];
    }

    /*!
     Returns the eZINI object for the country ini file.
     \warning Do not modify this object.
    */
    function &countryFile( $withVariation = false )
    {
        $type = $withVariation ? 'variation' : 'default';
        if ( !( $this->CountryINI[$type] instanceof eZINI ) )
        {
            $country = $this->countryCode();
            $countryVariation = $this->countryVariation();
            $locale = $country;
            if ( $withVariation )
            {
                if ( $countryVariation !== '' )
                    $locale .= '@' . $countryVariation;
            }
            $countryFile = 'country/' . $locale . '.ini';
            if ( eZLocale::isDebugEnabled() )
            {
                eZDebug::writeNotice( "Requesting $countryFile", 'eZLocale::countryFile' );
            }
            if ( eZINI::exists( $countryFile, 'share/locale' ) )
                $this->CountryINI[$type] = eZINI::instance( $countryFile, 'share/locale' );
        }
        return $this->CountryINI[$type];
    }

    /*!
     Returns the eZINI object for the language ini file.
     \warning Do not modify this object.
    */
    function &languageFile( $withVariation = false )
    {
        $type = $withVariation ? 'variation' : 'default';
        if ( !( $this->LanguageINI[$type] instanceof eZINI ) )
        {
            $language = $this->languageCode();
            $countryVariation = $this->countryVariation();
            $locale = $language;
            if ( $withVariation )
            {
                if ( $countryVariation !== '' )
                    $locale .= '@' . $countryVariation;
            }
            $languageFile = 'language/' . $locale . '.ini';
            if ( eZLocale::isDebugEnabled() )
            {
                eZDebug::writeNotice( "Requesting $languageFile", 'eZLocale::languageFile' );
            }
            if ( eZINI::exists( $languageFile, 'share/locale' ) )
                $this->LanguageINI[$type] = eZINI::instance( $languageFile, 'share/locale' );
        }
        return $this->LanguageINI[$type];
    }

    /*!
     \static
     Returns an unique instance of the locale class for a given locale string. If $localeString is not
     specified the default local string in site.ini is used.
     Use this instead of newing eZLocale to benefit from speed and unified access.
     \note Use create() if you need to get a new unique copy you can alter.
    */
    static function instance( $localeString = false )
    {
        if ( $localeString === false )
        {
            $localeStringDefault =& $GLOBALS["eZLocaleStringDefault"];
            if ( !isset( $localeStringDefault ) )
            {
                $ini = eZINI::instance();
                $localeString = $ini->variable( 'RegionalSettings', 'Locale' );
                /* Cache this answer to prevent countless calls to retrieve this
                 * from the INI settings */
                $localeStringDefault = $localeString;
            }
            else
            {
                /* Used cached version */
                $localeString = $localeStringDefault;
            }
        }

        $globalsKey = "eZLocaleInstance_$localeString";

        if ( !isset( $GLOBALS[$globalsKey] ) ||
             !( $GLOBALS[$globalsKey] instanceof eZLocale ) )
        {
            $GLOBALS[$globalsKey] = new eZLocale( $localeString );
        }
        return $GLOBALS[$globalsKey];
    }

    /*!
     \static
     Similar to instance() but will always create a new copy.
    */
    static function create( $localeString = false )
    {
        if ( $localeString === false )
        {
            $ini = eZINI::instance();
            $localeString = $ini->variable( 'RegionalSettings', 'Locale' );
        }
        return new eZLocale( $localeString );
    }

    /*!
     \static
     \return true if debugging of internals is enabled, this will display
     which files are loaded and when cache files are created.
      Set the option with setIsDebugEnabled().
    */
    static function isDebugEnabled()
    {
        if ( !isset( $GLOBALS['eZLocaleDebugInternalsEnabled'] ) )
             $GLOBALS['eZLocaleDebugInternalsEnabled'] = self::DEBUG_INTERNALS;
        return $GLOBALS['eZLocaleDebugInternalsEnabled'];
    }

    /*!
     \static
     Sets whether internal debugging is enabled or not.
    */
    static function setIsDebugEnabled( $debug )
    {
        $GLOBALS['eZLocaleDebugInternalsEnabled'] = $debug;
    }

    /*!
     \static
    */
    static function resetGlobals( $localeString = false )
    {
        if ( $localeString === false )
        {
            $localeStringDefault =& $GLOBALS["eZLocaleStringDefault"];
            if ( isset( $localeStringDefault ) )
            {
                $localeString = $localeStringDefault;
            }
        }

        unset( $GLOBALS["eZLocaleInstance_$localeString"] );
        unset( $GLOBALS["eZLocaleStringDefault"] );
    }

    //@{
    public $IsValid;
    //@}

    //@{
    /// Format of dates
    public $DateFormat;
    /// Format of short dates
    public $ShortDateFormat;
    /// Format of times
    public $TimeFormat;
    /// Format of short times
    public $ShortTimeFormat;
    /// True if monday is the first day of the week
    public $MondayFirst;
    /// AM and PM names
    public $AM, $PM;
    //@}

    //@{
    /// Numbers
    public $DecimalSymbol;
    public $ThousandsSeparator;
    public $FractDigits;
    public $NegativeSymbol;
    public $PositiveSymbol;
    //@}

    //@{
    /// Currency
    public $CurrencyDecimalSymbol;
    public $CurrencyThousandsSeparator;
    public $CurrencyFractDigits;
    public $CurrencyNegativeSymbol;
    public $CurrencyPositiveSymbol;
    public $CurrencySymbol;
    public $CurrencyPositiveFormat;
    public $CurrencyNegativeFormat;
    //@}

    //@{
    /// Help arrays
    public $DayNames;
    public $ShortDayNames, $LongDayNames;
    public $MonthNames;
    public $ShortMonthNames, $LongMonthNames;
    public $WeekDays, $Months;
    public $ShortWeekDayNames, $LongWeekDayNames;

    public $TimeArray;
    public $DateArray;
    public $TimePHPArray;
    public $DatePHPArray;
    //@}

    //@{
    /// Objects
    public $Country;
    public $CountryCode;
    public $CountryVariation;
    public $CountryComment;
    public $LanguageComment;
    public $LocaleINI;
    public $CountryINI;
    public $LanguageINI;
    /// The language code, for instance nor-NO, or eng-GB
    public $LanguageCode;
    /// Name of the language
    public $LanguageName;
    /// Internationalized name of the language
    public $IntlLanguageName;
    var $CountryNames;
    //@}
};

?>
