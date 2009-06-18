<?php
//
// Definition of eZCurrencyConverter class
//
// Created on: <28-Nov-2005 12:26:52 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZCurrencyConverter ezcurrencyconverter.php
  \brief Handles conversions from one curreny into another, applying
  rounding if it's needed.


*/

class eZCurrencyConverter
{
    const ROUNDING_TYPE_NONE = 1;
    const ROUNDING_TYPE_ROUND = 2;
    const ROUNDING_TYPE_CEIL = 3;
    const ROUNDING_TYPE_FLOOR = 4;

    function eZCurrencyConverter()
    {
        $this->setMathHandler( null );
        $this->setRoundingType( null );
        $this->setRoundingPrecision( null );
        $this->setRoundingTarget( null );
    }

    /**
     * Returns a shared instance of the eZCurrencyConverter class.
     *
     * @return eZCurrencyConverter
     */
    static function instance()
    {
        if ( empty( $GLOBALS["eZCurrencyConverterGlobalInstance"] ) )
        {
            $GLOBALS["eZCurrencyConverterGlobalInstance"] = new eZCurrencyConverter();
        }

        return $GLOBALS["eZCurrencyConverterGlobalInstance"];
    }

    /*!
     \return converted value for $value form $fromCurrency to $toCurrency. Applyes rounding if needed.
    */
    function convert( $fromCurrency, $toCurrency, $value, $applyRounding = true )
    {
        if ( $fromCurrency == false || $toCurrency == false || $fromCurrency == $toCurrency || $value == 0 )
            return $value;

        $math = $this->mathHandler();

        $crossRate = $this->crossRate( $fromCurrency, $toCurrency );
        $convertedValue = $math->mul( $value, $crossRate );
        if ( $applyRounding )
        {
            switch ( $this->roundingType() )
            {
                case 'EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_ROUND':
                {
                    $convertedValue = $math->round( $convertedValue, $this->roundingPrecision(), $this->roundingTarget() );
                } break;

                case 'EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_CEIL':
                {
                    $convertedValue = $math->ceil( $convertedValue, $this->roundingPrecision(), $this->roundingTarget() );
                } break;

                case 'EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_FLOOR':
                {
                    $convertedValue = $math->floor( $convertedValue, $this->roundingPrecision(), $this->roundingTarget() );
                } break;

                case 'EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_NONE':
                default:
                    break;
            }
        }

        return $convertedValue;
    }

    /*!
     \return converted value for $value form currency specified in locale to $toCurrency. Applyes rounding if needed.
    */
    function convertFromLocaleCurrency( $toCurrency, $value, $applyRounding = true )
    {
        $locale = eZLocale::instance();
        $fromCurrency = $locale->currencyShortName();
        $retValue = $this->convert( $fromCurrency, $toCurrency, $value, $applyRounding );
        return $retValue;
    }

    function rateValue( $currencyCode )
    {
        $currencyList = $this->currencyList();
        $currency = $currencyList[$currencyCode];

        if ( is_object( $currency ) )
        {
            return $currency->rateValue();
        }

        return 0;
    }

    function crossRate( $fromCurrency, $toCurrency )
    {
        $fromRate = $this->rateValue( $fromCurrency );
        $toRate = $this->rateValue( $toCurrency );

        if ( $fromRate > 0 )
        {
            $math = $this->mathHandler();
            return $math->div( $toRate, $fromRate );
        }

        return 0;
    }

    function mathHandler()
    {
        if ( $this->MathHandler === null )
        {
            $ini = eZINI::instance( 'shop.ini' );

            $mathType = $ini->variable( 'MathSettings', 'MathHandler' );
            $mathType = strtolower( $mathType );

            $params = array( 'scale' => $ini->variable( 'MathSettings', 'MathScale' ) );

            $this->setMathHandler( eZPHPMath::create( $mathType, $params ) );
        }

        return $this->MathHandler;
    }

    function setMathHandler( $handler )
    {
        $this->MathHandler = $handler;
    }

    function currencyList()
    {
        if ( !isset( $this->CurrencyList ) )
        {
            $this->CurrencyList = eZCurrencyData::fetchList();
        }

        return $this->CurrencyList;
    }

    function roundingType()
    {
        if ( $this->RoundingType === null )
        {
            $ini = eZINI::instance( 'shop.ini' );

            $roundingType = 'EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_' . strtoupper( $ini->variable( 'MathSettings', 'RoundingType' ) );
            if ( !defined( "self::{$roundingType}" ) )
                $roundingType = self::ROUNDING_TYPE_NONE;

            $this->setRoundingType( $roundingType );
        }

        return $this->RoundingType;
    }

    function setRoundingType( $type )
    {
        $this->RoundingType = $type;
    }

    function roundingPrecision()
    {
        if ( $this->RoundingPrecision === null )
        {
            $ini = eZINI::instance( 'shop.ini' );
            $this->setRoundingPrecision( $ini->variable( 'MathSettings', 'RoundingPrecision' ) );
        }

        return $this->RoundingPrecision;
    }

    function setRoundingPrecision( $precision )
    {
        $this->RoundingPrecision = $precision;
    }

    function roundingTarget()
    {
        if ( $this->RoundingTarget === null )
        {
            $ini = eZINI::instance( 'shop.ini' );
            $this->setRoundingTarget( $ini->variable( 'MathSettings', 'RoundingTarget' ) );
        }
        return $this->RoundingTarget;
    }

    function setRoundingTarget( $target )
    {
        $this->RoundingTarget = $target;
    }


    public $CurrencyList;
    public $MathHandler;
    public $RoundingType;
    public $RoundingPrecision;
    public $RoundingTarget;
}

?>
