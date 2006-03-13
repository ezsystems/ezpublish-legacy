<?php
//
// Definition of eZExchangeRatesUpdateHandler class
//
// Created on: <12-Mar-2006 13:06:15 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezexchangeratesupdatehandler.php
*/

class eZExchangeRatesUpdateHandler
{
    function eZExchangeRatesUpdateHandler()
    {
        $this->RateList = false;
        $this->BaseCurrency = false;

        $this->initialize();
    }

    function initialize( $params = array() )
    {
    }

    /*!
     \static
    */
    function create( $handlerName = false )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );

        $shopINI =& eZINI::instance( 'shop.ini' );
        if ( $handlerName === false)
        {
           if ( $shopINI->hasVariable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' ) )
               $handlerName = $shopINI->variable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' );
        }

        $handlerName = strtolower( $handlerName );

        $repositoryDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'RepositoryDirectories' );
        $extensionDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'ExtensionDirectories' );

        $baseDirectory = eZExtension::baseDirectory();
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory;
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        $foundHandler = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/{$handlerName}exchangeratesupdatehandler.php";

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
                                 implode( ', ', $repositoryDirectories ),
                                 'eZExchangeRatesUpdateHandler::create' );
            return false;
        }

        require_once( $includeFile );
        $className = $handlerName . 'ExchangeRatesUpdateHandler';
        return new $className;
    }

    function rateList()
    {
        return $this->RateList;
    }

    function setRateList( &$rateList )
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

    function requestRates( &$error )
    {
        $error = "eZExchangeRatesUpdateHandler: you should reimplement 'requestRates' method";
        return false;
    }

    var $RateList;
    var $BaseCurrency;
}

?>
