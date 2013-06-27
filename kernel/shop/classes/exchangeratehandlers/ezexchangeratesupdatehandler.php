<?php
/**
 * File containing the eZExchangeRatesUpdateHandler class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZExchangeRatesUpdateHandler
{
    const OK = 0;
    const CANT_CREATE_HANDLER = 1;
    const FAILED = 2;
    const EMPTY_RATE_LIST = 3;
    const UNKNOWN_BASE_CURRENCY = 4;
    const INVALID_BASE_CROSS_RATE = 5;

    function eZExchangeRatesUpdateHandler()
    {
        $this->RateList = false;
        $this->BaseCurrency = false;

        $this->initialize();
    }

    function initialize( $params = array() )
    {
        if ( !isset( $params['BaseCurrency'] ) )
            $params['BaseCurrency'] = '';

        $this->setBaseCurrency( $params['BaseCurrency'] );
    }

    /*!
     \static
    */
    static function create( $handlerName = false )
    {
        $shopINI = eZINI::instance( 'shop.ini' );
        if ( $handlerName === false)
        {
           if ( $shopINI->hasVariable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' ) )
               $handlerName = $shopINI->variable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' );
        }

        $handlerName = strtolower( $handlerName );

        $className = $handlerName . 'handler';
        if ( !class_exists( $className ) )
        {
            eZDebug::writeError( "Exchange rates update handler '$handlerName' not found " .
                                 'eZExchangeRatesUpdateHandler::create' );
            return false;
        }

        return new $className;
    }

    function rateList()
    {
        return $this->RateList;
    }

    function setRateList( $rateList )
    {
        $this->RateList = $rateList;
    }

    function baseCurrency()
    {
        return $this->BaseCurrency;
    }

    function setBaseCurrency( $baseCurrency )
    {
        $this->BaseCurrency = $baseCurrency;
    }

    function requestRates()
    {
        $error = array( 'code' => self::FAILED,
                        'description' => ezpI18n::tr( 'kernel/shop', "eZExchangeRatesUpdateHandler: you should reimplement 'requestRates' method" ) );

        return $error;
    }

    public $RateList;
    public $BaseCurrency;
}

?>
