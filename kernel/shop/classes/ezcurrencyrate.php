<?php
//
// Definition of eZCurrencyRate class
//
// Created on: <08-Nov-2005 13:06:15 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcurrencyrate.php
*/

define( 'EZ_CURRENCYRATE_ERROR_OK', 0 );
define( 'EZ_CURRENCYRATE_ERROR_RATE_EXISTS', 1 );

define( 'EZ_CURRENCYRATE_DEFAULT_AUTO_RATE_VALUE', '0.0000' );
define( 'EZ_CURRENCYRATE_DEFAULT_CUSTOM_RATE_VALUE', '0.0000' );
define( 'EZ_CURRENCYRATE_DEFAULT_FACTOR_VALUE', '1.0000' );

include_once( "kernel/classes/ezpersistentobject.php" );

class eZCurrencyRate extends eZPersistentObject
{
    function eZCurrencyRate( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'code' => array( 'name' => 'Code',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'auto_value' => array( 'name' => 'AutoValue',
                                                                'datatype' => 'string',
                                                                'default' => EZ_CURRENCYRATE_DEFAULT_AUTO_RATE_VALUE,
                                                                'required' => false ),
                                         'custom_value' => array( 'name' => 'CustomValue',
                                                                'datatype' => 'string',
                                                                'default' => EZ_CURRENCYRATE_DEFAULT_CUSTOM_RATE_VALUE,
                                                                'required' => false ),
                                         'factor' => array( 'name' => 'Factor',
                                                                'datatype' => 'string',
                                                                'default' => EZ_CURRENCYRATE_DEFAULT_FACTOR_VALUE,
                                                                'required' => false ) ),
                      'keys' => array( 'code' ),
                      'function_attributes' => array( 'rate_value' => 'rateValue' ),
                      'class_name' => "eZCurrencyRate",
                      'sort' => array( 'code' => 'asc' ),
                      'name' => "ezcurrencyrate" );
    }

    /*!
     \static
     \params codeList can be a single code like 'USD' or an array like array( 'USD', 'NOK' )
     or 'false' (means all currencies).
    */
    function fetchList( $conditions = null, $asObjects = true, $offset = false, $limit = false )
    {
        $rateList = null;
        /*
        if ( $currencyCode == false )
            $conds = null;
        else if ( is_array( $currencyCode ) )
            $conds = array( 'code' => array( $currencyCode ) );
        else
            $conds = array( 'code' => $currencyCode );
        */
        $sort = null;
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array( 'offset' => $offset, 'length' => $limit );

        $rows = eZPersistentObject::fetchObjectList( eZCurrencyRate::definition(),
                                                     null,
                                                     $conditions,
                                                     $sort,
                                                     $limitation,
                                                     $asObjects );

        if ( count( $rows ) > 0 )
            $rateList = $rows;

        return $rateList;
    }

    /*!
     \static
    */
    function fetchListCount( $conditions = null )
    {
        $rows = eZPersistentObject::fetchObjectList( eZCurrencyRate::definition(),
                                                     array(), $conditions, null, null,
                                                     false, false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    /*!
     \static
    */
    function fetch( $currencyCode, $asObject = true )
    {
        $rate = eZCurrencyRate::fetchList( array( 'code' => $currencyCode ), $asObject );
        if ( is_array( $rate ) && count( $rate ) > 0 )
            return $rate[0];
    }

    /*!
     \static
        $rateList = array( $currencyCode => array( 'custom_value' => value,
                                                   'auto_value' => value,
                                                   'factor' => factor) );

        $rateList = array( 'UAH' => array( 'custom_value' => 5.05,
                                           'factor' => 1.5 ),
                           'NOK' => array( 'custom_value' => 7.12 ),
                           'EUR' => array( 'factor' => 1.2 ) )
    */
    function setRates( $rateList, $createNew = true )
    {
        if ( is_array( $rateList ) && count( $rateList ) > 0 )
        {
            // fetch existing rates
            $codeList = array();
            foreach ( $rateList as $currencyCode => $rate )
                $codeList[] = $currencyCode;

            $rates = eZCurrencyRate::fetchList( array( 'code' => array( $codeList ) ), true );

            $db =& eZDB::instance();
            $db->begin();

            // update existing rates
            if ( is_array( $rates ) )
            {
                foreach ( $rates as $rate )
                {
                    $currencyCode = $rate->attribute( 'code' );
                    if ( isset( $rateList[$currencyCode]['custom_value'] ) )
                        $rate->setAttribute( 'custom_value', $rateList[$currencyCode]['custom_value'] );
                    if ( isset( $rateList[$currencyCode]['auto_value'] ) )
                        $rate->setAttribute( 'auto_value', $rateList[$currencyCode]['auto_value'] );
                    if ( isset( $rateList[$currencyCode]['factor'] ) )
                        $rate->setAttribute( 'factor', $rateList[$currencyCode]['factor'] );

                    $rate->sync();

                    unset( $rateList[$rate->attribute( 'code' )] );
                }
            }

            // add new rates
            if ( $createNew )
            {
                foreach ( $rateList as $currencyCode => $rateData )
                {
                    $rate = eZCurrencyRate::create( $currencyCode );
                    if ( is_object( $rate ) )
                    {
                        if ( isset( $rateData['custom_value'] ) )
                            $rate->setAttribute( 'custom_value', $rateData['custom_value'] );
                        if ( isset( $rateData['auto_value'] ) )
                            $rate->setAttribute( 'auto_value', $rateData['auto_value'] );
                        if ( isset( $rateData['factor'] ) )
                            $rate->setAttribute( 'factor', $rateData['factor'] );

                        $rate->store();
                    }
                }
            }

            $db->commit();
        }
    }

    function rateValue()
    {
        eZDebug::writeDebug( 'eZCurrencyRate::rateValue', 'lazy: FIX ME!!!' );
        $rateValue = $this->attribute( 'auto_value' );
        if ( $this->attribute( 'custom_value' ) > 0 )
            $rateValue = $this->attribute( 'custom_value' );
        $rateValue = $rateValue * $this->attribute( 'factor' );
        return $rateValue;
    }

    /*!
     \static
    */
    function create( $currencyCode,
                     $autoRate = EZ_CURRENCYRATE_DEFAULT_AUTO_RATE_VALUE,
                     $customRate = EZ_CURRENCYRATE_DEFAULT_CUSTOM_RATE_VALUE,
                     $factor = EZ_CURRENCYRATE_DEFAULT_FACTOR_VALUE )
    {
        $currencyCode = strtoupper( $currencyCode );
        $errCode = eZCurrencyRate::canCreate( $currencyCode );
        if ( $errCode === EZ_CURRENCYRATE_ERROR_OK )
        {
            $rate = new eZCurrencyRate( array( 'code' => $currencyCode,
                                               'auto_value' => $autoRate,
                                               'custom_value' => $customRate,
                                               'factor' => $factor ) );
            return $rate;
        }

        return $errCode;
    }

    /*!
     \static
   */
    function canCreate( $currencyCode )
    {
        /*if ( eZCurrencyRate::fetch( $currencyCode ) )
            return EZ_CURRENCYRATE_ERROR_RATE_EXISTS;
        */
        return EZ_CURRENCYRATE_ERROR_OK;
    }

    /*!
     \static
    */
    function errorMessage( $errorCode )
    {
        switch ( $errorCode )
        {
            case EZ_CURRENCYRATE_ERROR_RATE_EXISTS:
                return ezi18n( 'kernel/shop/classes/ezcurrencyrate', 'Specified rate already exists.' );

            default:
                return ezi18n( 'kernel/shop/classes/ezcurrencydata', 'Unknown error.' );
        }
    }
}

?>