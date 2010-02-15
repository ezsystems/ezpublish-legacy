<?php
//
// Definition of eZExchangeRatesUpdateHandler class
//
// Created on: <12-Mar-2006 13:06:15 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

        $dirList = array();
        $repositoryDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'RepositoryDirectories' );
        $extensionDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'ExtensionDirectories' );

        $baseDirectory = eZExtension::baseDirectory();
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            if ( !empty( $extensionDirectory ) )
                $dirList[] = $baseDirectory . '/' . $extensionDirectory . '/exchangeratehandlers';
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            if ( !empty( $repositoryDirectory ) )
                $dirList[] = $repositoryDirectory;
        }

        $foundHandler = false;
        foreach ( $dirList as $dir )
        {
            $includeFile = "$dir/$handlerName/{$handlerName}handler.php";

            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }

        if ( !$foundHandler )
        {
            eZDebug::writeError( "Exchange rates update handler '$handlerName' not found, " .
                                   "searched in these directories: " .
                                   implode( ', ', $dirList ),
                                 'eZExchangeRatesUpdateHandler::create' );
            return false;
        }

        require_once( $includeFile );
        $className = $handlerName . 'handler';
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
                        'description' => eZi18n::translate( 'kernel/shop', "eZExchangeRatesUpdateHandler: you should reimplement 'requestRates' method" ) );

        return $error;
    }

    public $RateList;
    public $BaseCurrency;
}

?>
