<?php
/**
 * File containing the eZCurrency class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZCurrency ezcurrency.php
  \ingroup eZLocale
  \brief Locale aware currency class

  eZCurrency handles currency values and allows for easy
  output with toString() and conversion with adjustValue().

  A new instance of eZCurrency will automaticly use the current locale,
  if you however want a different locale use the setLocale() function.
  The current locale can be fetched with locale().

  Change the currency value with setValue() or convert it to a new currency type
  with adjustValue() and setLocale().
  If you want more detiled information on the currency use type(), name() and shortName().

  Text output is done with toString() which returns a representation according to the current locale.

  \sa eZLocale
*/

class eZCurrency
{
    /*!
     Creates a new eZCurrency object with the currency value $value. $value can be a numerical
     value or an eZCurrency object in which case the value is extracted and copied.
    */
    function eZCurrency( $value )
    {
        if ( $value instanceof eZCurrency )
        {
            $value = $value->value();
        }
        $this->Value = $value;
        $this->Locale = eZLocale::instance();
    }

    /*!
     Sets the locale to $locale which is used in text output.
    */
    function setLocale( &$locale )
    {
        $this->Locale =& $locale;
    }

    /*!
     Adjust the value so that it can be represented in a different currency.
     The $old_scale is a float value which represents the current currency rate
     while $new_scale represents the new currency rate. All rates should be calculated
     from the dollar which then gets the rate 1.0.
    */
    function adjustValue( $old_scale, $new_scale )
    {
        $this->Value = ( $this->Value * $new_scale ) / $old_scale;
    }

    /*!
     Sets the currency value to $value.
    */
    function setValue( $value )
    {
        $this->Value =& $value;
    }

    /*!
     Returns a reference to the current locale.
    */
    function locale()
    {
        return $this->Locale;
    }

    /*!
     Returns the currency value.
    */
    function value()
    {
        return $this->Value;
    }

    /*!
     Returns the currency symbol, for instance kr for NOK or $ for USD.
    */
    function symbol()
    {
        return $this->Locale->currencySymbol();
    }

    /*!
     Returns the name of the currency, for instance Norwegian Kroner or US dollar.
    */
    function name()
    {
        return $this->Locale->currencyName();
    }

    /*!
     Returns a 3 letter name for the currency, for instance NOK or USD.
    */
    function shortName()
    {
        return $this->Locale->currencyShortName();
    }

    /*!
     Returns a text representation of the currency according to the current locale.
    */
    function toString()
    {
        return $this->Locale->formatCurrency( $this->Value );
    }

    /// The currency value.
    public $Value;
    /// The current locale object
    public $Locale;
}

?>
