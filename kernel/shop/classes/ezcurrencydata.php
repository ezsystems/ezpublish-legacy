<?php
//
// Definition of eZCurrencyData class
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

/*! \file ezcurrencydata.php
*/

define( 'EZ_CURRENCYDATA_ERROR_OK', 0 );
define( 'EZ_CURRENCYDATA_ERROR_INVALID_CURRENCY_CODE', 1 );
define( 'EZ_CURRENCYDATA_ERROR_CURRENCY_EXISTS', 2 );

define( 'EZ_CURRENCYDATA_STATUS_ACTIVE', '1' );
define( 'EZ_CURRENCYDATA_STATUS_INACTIVE', '2' );

include_once( "kernel/classes/ezpersistentobject.php" );

class eZCurrencyData extends eZPersistentObject
{
    function eZCurrencyData( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( /*'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                                        */
                                         'code' => array( 'name' => 'Code',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'symbol' => array( 'name' => 'Symbol',
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => false ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ) ),
                      //'keys' => array( 'id' ),
                      //'increment_key' => 'id',
                      //'sort' => array( 'id' => 'asc' ),
                      'keys' => array( 'code' ),
                      'function_attributes' => array(),
                      'class_name' => "eZCurrencyData",
                      'sort' => array( 'code' => 'asc' ),
                      'name' => "ezcurrencydata" );
    }

    /*!
     \static
     \params codeList can be a single code like 'USD' or an array like array( 'USD', 'NOK' )
     or 'false' (means all currencies).
    */
    function fetchList( $conditions = null, $asObjects = true, $offset = false, $limit = false )
    {
        $currencyList = null;

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

        $rows = eZPersistentObject::fetchObjectList( eZCurrencyData::definition(),
                                                     null,
                                                     $conditions,
                                                     $sort,
                                                     $limitation,
                                                     $asObjects );

        if ( count( $rows ) > 0 )
        {
            $keys = array_keys( $rows );
            foreach ( $keys as $key )
            {
                if ( $asObjects )
                    $currencyList[$rows[$key]->attribute( 'code' )] =& $rows[$key];
                else
                    $currencyList[$rows[$key]['code']] =& $rows[$key];
            }
        }

        return $currencyList;
    }

    /*!
     \static
    */
    function fetchListCount( $conditions = null )
    {
        $rows = eZPersistentObject::fetchObjectList( eZCurrencyData::definition(),
                                                     array(),
                                                     $conditions,
                                                     null,
                                                     null,
                                                     false,
                                                     false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    /*!
     \static
    */
    function fetch( $currencyCode, $asObject = true )
    {
        $currency = eZCurrencyData::fetchList( array( 'code' => $currencyCode ), $asObject );
        if ( is_array( $currency ) && count( $currency ) > 0 )
            return $currency[$currencyCode];
    }

    /*!
     \static
    */
    function create( $code, $symbol, $status = EZ_CURRENCYDATA_STATUS_ACTIVE )
    {
        $code = strtoupper( $code );
        $errCode = eZCurrencyData::canCreate( $code );
        if ( $errCode === EZ_CURRENCYDATA_ERROR_OK )
        {
            $currency = new eZCurrencyData( array( 'code' => $code,
                                                   'symbol' => $symbol,
                                                   'status' => $status ) );
            return $currency;
        }

        return $errCode;
    }

    /*!
     \static
   */
    function canCreate( $code )
    {
        if ( !preg_match( "/^[A-Z]{3}$/", $code ) )
            return EZ_CURRENCYDATA_ERROR_INVALID_CURRENCY_CODE;

        if ( eZCurrencyData::fetch( $code ) )
            return EZ_CURRENCYDATA_ERROR_CURRENCY_EXISTS;

        return EZ_CURRENCYDATA_ERROR_OK;
    }

    /*!static
    */
    function removeCurrencyList( $currencyCodeList )
    {
        if ( is_array( $currencyCodeList ) && count( $currencyCodeList ) > 0 )
        {
            $db =& eZDB::instance();
            $db->begin();
                eZPersistentObject::removeObject( eZCurrencyData::definition(),
                                                  array( 'code' => array( $currencyCodeList ) ) );
            $db->commit();
        }
    }

    function setStatusList( $currencyStatusList )
    {
        $currencyList = eZCurrencyData::fetchList();
        $db =& eZDB::instance();
        $db->begin();
        foreach ( $currencyList as $currency )
        {
            if ( isset( $currencyStatusList[$currency->attribute( 'code' )] ) )
            {
                $currency->setStatus( $currencyStatusList[$currency->attribute( 'code' )]['status'] );
                $currency->sync();
            }
        }
        $db->commit();
    }

    function setStatus( $status )
    {
        $statusNumeric = eZCurrencyData::statusStringToNumeric( $status );
        if ( $statusNumeric !== false )
            $this->setAttribute( 'status', $statusNumeric );
        else
            eZDebug::writeError( "Unknow currency's status $status", 'eZCurrencyData::setStatus' );
    }

    function statusStringToNumeric( $statusString )
    {
        $status = false;
        if ( is_numeric( $statusString ) )
        {
            $status = $statusString;
        }
        if ( is_string( $statusString ) )
        {
            $statusString = strtoupper( $statusString );
            if ( defined( "EZ_CURRENCYDATA_STATUS_$statusString" ) )
                $status = constant( "EZ_CURRENCYDATA_STATUS_$statusString" );
        }

        return $status;
    }

    /*!
     \static
    */
    function errorMessage( $errorCode )
    {
        switch ( $errorCode )
        {
            case EZ_CURRENCYDATA_ERROR_INVALID_CURRENCY_CODE:
                return ezi18n( 'kernel/shop/classes/ezcurrencydata', 'Invalid characters in currency code.' );

            case EZ_CURRENCYDATA_ERROR_CURRENCY_EXISTS:
                return ezi18n( 'kernel/shop/classes/ezcurrencydata', 'Currency already exists.' );

            default:
                return ezi18n( 'kernel/shop/classes/ezcurrencydata', 'Unknown error.' );
        }
    }
}

?>