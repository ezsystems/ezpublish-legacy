<?php
//
// Definition of eZECBHandler class
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

class eZECBHandler extends eZExchangeRatesUpdateHandler
{
    function eZECBHandler()
    {
        $this->ServerName = false;
        $this->ServerPort = false;
        $this->RatesURI = false;

        eZExchangeRatesUpdateHandler::eZExchangeRatesUpdateHandler();
    }

    function initialize( $params = array() )
    {
        eZExchangeRatesUpdateHandler::initialize( $params );

        $shopINI = eZINI::instance( 'shop.ini' );
        if ( !isset( $params['ServerName'] ) )
        {
            $params['ServerName'] = '';
            if ( $shopINI->hasVariable( 'ECBExchangeRatesSettings', 'ServerName' ) )
                $params['ServerName'] = $shopINI->variable( 'ECBExchangeRatesSettings', 'ServerName' );
        }

        if ( !isset( $params['ServerPort'] ) )
        {
            $params['ServerPort'] = '';
            if ( $shopINI->hasVariable( 'ECBExchangeRatesSettings', 'ServerPort' ) )
                $params['ServerPort'] = $shopINI->variable( 'ECBExchangeRatesSettings', 'ServerPort' );
        }

        if ( !isset( $params['RatesURI'] ) )
        {
            $params['RatesURI'] = '';
            if ( $shopINI->hasVariable( 'ECBExchangeRatesSettings', 'RatesURI' ) )
                $params['RatesURI'] = $shopINI->variable( 'ECBExchangeRatesSettings', 'RatesURI' );
        }

        if ( !isset( $params['BaseCurrency'] ) )
        {
            // the ECB returns currencies against 'EUR'
            $params['BaseCurrency'] = 'EUR';
        }

        $this->setServerName( $params['ServerName'] );
        $this->setServerPort( $params['ServerPort'] );
        $this->setRatesURI( $params['RatesURI'] );
        $this->setBaseCurrency( $params['BaseCurrency'] );
    }

    function requestRates()
    {
        $error = array( 'code' => self::OK,
                        'description' => eZi18n::translate( 'kernel/shop', "'Autorates' were retrieved successfully" ) );

        $serverName = $this->serverName();
        $serverPort = $this->serverPort();
        $ratesURI = $this->ratesURI();

        $ratesList = array();

        $buf = eZHTTPTool::sendHTTPRequest( "{$serverName}/{$ratesURI}", $serverPort,  false, 'eZ Publish', false );
        if ( $buf )
        {
            $header = false;
            $body = false;

            if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $body ) )
            {
                if ( $header['content-type'] === 'text/xml' )
                {
                    // parse xml
                    $dom = new DOMDocument( '1.0', 'utf-8' );
                    $dom->preserveWhiteSpace = false;
                    $success = $dom->loadXML( $body );

                    $xpath = new DOMXPath( $dom );
                    $xpath->registerNamespace( 'eurofxref', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref' );

                    $rootNode = $dom->documentElement;
                    $cubeNode = $xpath->query( 'eurofxref:Cube', $rootNode )->item( 0 );
                    $timeNode = $cubeNode->firstChild;

                    foreach ( $timeNode->childNodes as $currencyNode )
                    {
                        $currencyCode = $currencyNode->getAttribute( 'currency' );
                        $rateValue = $currencyNode->getAttribute( 'rate' );
                        $ratesList[$currencyCode] = $rateValue;
                    }
                }
                else
                {
                    $error['code'] = self::FAILED;
                    $error['description'] = eZi18n::translate( 'kernel/shop', "Unknown body format in HTTP response. Expected 'text/xml'" );
                }
            }
            else
            {
                $error['code'] = self::FAILED;
                $error['description'] = eZi18n::translate( 'kernel/shop', "Invalid HTTP response" );
            }
        }
        else
        {
            $error['code'] = self::FAILED;
            $error['description'] = eZi18n::translate( 'kernel/shop', "Unable to send http request: %1:%2/%3", null, array( $serverName, $serverPort, $ratesURI ) );
        }

        $this->setRateList( $ratesList );
        return $error;
    }

    function setServerName( $serverName )
    {
        $this->ServerName = $serverName;
    }

    function serverName()
    {
        return $this->ServerName;
    }

    function setServerPort( $serverPort )
    {
        $this->ServerPort = $serverPort;
    }

    function setRatesURI( $ratesURI )
    {
        $this->RatesURI = $ratesURI;
    }

    function serverPort()
    {
        return $this->ServerPort;
    }

    function ratesURI()
    {
        return $this->RatesURI;
    }

    public $ServerName;
    public $ServerPort;
    public $RatesURI;
}

?>
