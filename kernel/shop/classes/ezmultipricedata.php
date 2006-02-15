<?php
//
// Definition of eZMultiPriceData class
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

/*! \file ezmultipricedata.php
*/

include_once( 'kernel/classes/ezpersistentobject.php' );

define( 'EZ_MULTIPRICEDATA_VALUE_TYPE_CUSTOM', 1 );
define( 'EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO', 2 );
define( 'EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT', 5000 );

class eZMultiPriceData extends eZPersistentObject
{
    function eZMultiPriceData( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         'contentobject_attribute_version' => array( 'name' => 'ContentObjectVersion',
                                                                                     'datatype' => 'integer',
                                                                                     'default' => 0,
                                                                                     'required' => true ),
                                         'currency_code' => array( 'name' => 'CurrencyCode',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'type' => array( 'name' => 'Type',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ) ),

                      'function_attributes' => array(),
                      //'keys' => array( 'id', 'contentobject_attribute_id', 'contentobject_attribute_version', 'currency_code' ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => "eZMultiPriceData",
                      'name' => "ezmultipricedata" );
    }

    /*!
     \static
     \params
       codeList     - specifies currencies to fetch. If 'false' then all prices for '$contentAttributeID/$contentObjectVersion' will be retrieved.
    */
    function fetch( $contentAttributeID, $contentObjectVersion, $currencyCode = false, $type = false, $asObjects = true )
    {
        $priceList = null;
        $conds = array();

        $conds['contentobject_attr_id'] = $contentAttributeID;
        $conds['contentobject_attr_version'] = $contentObjectVersion;

        if ( is_array( $currencyCode ) )
            $conds['currency_code'] = array( $currencyCode );
        else if ( $currencyCode != false )
            $conds['currency_code'] = $currencyCode;

        if ( is_array( $type ) )
            $conds['type'] = array( $type );
        else if ( $type != false )
            $conds['type'] = $type;



        $sort = null;
        $limitation = null;

        $rows = eZPersistentObject::fetchObjectList( eZMultiPriceData::definition(),
                                                     null,
                                                     $conds,
                                                     $sort,
                                                     $limitation,
                                                     $asObjects );


        if ( count( $rows ) > 0 )
        {
            $keys = array_keys( $rows );
            foreach ( $keys as $key )
            {
                if ( $asObjects )
                    $priceList[$rows[$key]->attribute( 'currency_code' )] =& $rows[$key];
                else
                    $priceList[$rows[$key]['currency_code']] =& $rows[$key];
            }
        }

        return $priceList;
    }

    /*!
        removes single record from 'ezmultipricedata' table
    */
    function removeByID( $id = false )
    {
        if ( $id === false)
            $id = $this->attribute( 'id' );

        $db =& eZDB::instance();
        $db->begin();
        eZPersistentObject::removeObject( eZMultiPriceData::definition(),
                                          array( 'id' => $id ) );
        $db->commit();
    }


    /*!
        remove
    */
    function remove( $objectAttributeID, $objectAttributeVersion = null )
    {
        $db =& eZDB::instance();
        $db->begin();
        if ( $objectAttributeVersion == null )
        {
            eZPersistentObject::removeObject( eZMultiPriceData::definition(),
                                              array( 'contentobject_attr_id' => $objectAttributeID ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZMultiPriceData::definition(),
                                              array( 'contentobject_attr_id' => $objectAttributeID,
                                                     'contentobject_attr_version' => $objectAttributeVersion ) );
        }

        $db->commit();
    }


    /*!
     \static
    */
    function createPriceListForCurrency( $currencyCode, $currentVersionOnly = true )
    {
        $db =& eZDB::instance();

        $dataList = false;
        $insertSql = 'INSERT INTO ezmultipricedata(contentobject_attr_id, contentobject_attr_version, currency_code, type)';

        $limit = EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT;
        while( $limit === EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT  )
        {
            $dataList = eZMultiPriceData::fetchDataListWithoutPriceInCurrency( $currencyCode, $currentVersionOnly, $limit );
            $limit = count( $dataList );

            $currencyCode = $db->escapeString( $currencyCode );
            foreach ( $dataList as $data )
            {
                $sql = $insertSql . "
                               VALUES( {$data['contentobject_attr_id']}, {$data['contentobject_attr_version']}, '$currencyCode', " . EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO . " )";
                $db->query( $sql );
            }
        }
    }

    /*!
     \static
    */
    function fetchDataListWithoutPriceInCurrency( $currencyCode, $currentVersionOnly = true, $limit )
    {
        $db =& eZDB::instance();
        $currencyCode = $db->escapeString( $currencyCode );

        $fetchSql = '';
        if ( $currentVersionOnly )
        {
            $fetchSql = "SELECT DISTINCT m1.contentobject_attr_id,
                                    m1.contentobject_attr_version
                    FROM ezmultipricedata m1 LEFT JOIN ezmultipricedata m2
                    ON ( m2.contentobject_attr_id = m1.contentobject_attr_id
                         AND m2.contentobject_attr_version = m1.contentobject_attr_version
                         AND m2.currency_code = '$currencyCode' ),
                        ezcontentobject,
                        ezcontentobject_attribute

                    WHERE m1.currency_code <> '$currencyCode'
                          AND m2.contentobject_attr_id is null
                          AND ezcontentobject_attribute.version = ezcontentobject.current_version
                          AND ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                          AND m1.contentobject_attr_version = ezcontentobject_attribute.version
                          AND m1.contentobject_attr_id = ezcontentobject_attribute.id";
        }
        else
        {
            $fetchSql = "SELECT DISTINCT contentobject_attr_id, contentobject_attr_version
                    FROM            ezmultipricedata
                    WHERE           ezmultipricedata.currency_code <> '$currencyCode'";
        }


        $dataList = $db->arrayQuery( $fetchSql, array( 'limit' => $limit ) );

        return $dataList;
    }

    function removePriceListForCurrency( $currencyCodeList, $currentVersionOnly = true )
    {
        $db =& eZDB::instance();

        if ( !is_array( $currencyCodeList ) || count( $currencyCodeList ) === 0 )
            return;

        $currencyListStr = '';
        foreach ( $currencyCodeList as $currencyCode )
        {
            $currencyListStr .= "'" . $db->escapeString( $currencyCode ) . "'" . ',';
        }

        $currencyListStr = rtrim( $currencyListStr, ',' );

        $sql = '';
        if ( $currentVersionOnly === true )
        {
            $fetchSql = "SELECT ezmultipricedata.id
                         FROM   ezmultipricedata, ezcontentobject, ezcontentobject_attribute
                         WHERE  ezmultipricedata.currency_code IN ( $currencyListStr )
                                AND ezcontentobject_attribute.version = ezcontentobject.current_version
                                AND ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                                AND ezmultipricedata.contentobject_attr_version = ezcontentobject_attribute.version
                                AND ezmultipricedata.contentobject_attr_id = ezcontentobject_attribute.id";

            $limit = EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT;
            while( $limit === EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT  )
            {
                $dataList = $db->arrayQuery( $fetchSql, array( 'limit' => $limit ) );
                $limit = count( $dataList );

                if ( $limit > 0 )
                {
                    $idListStr = '';
                    foreach ( $dataList as $data )
                    {
                        $idListStr .= $db->escapeString( $data['id'] ). ',';
                    }

                    $idListStr = rtrim( $idListStr, ',' );

                    $sql = "DELETE FROM ezmultipricedata where id IN ( $idListStr )";
                    $db->query( $sql );
                }
            }
        }
        else
        {
            $sql = "DELETE FROM ezmultipricedata where currency_code IN ( $currencyListStr )";
            $db->query( $sql );
        }
    }

    function changeCurrency( $oldCurrencyCode, $newCurrencyCode, $currentVersionOnly = true )
    {
        $db =& eZDB::instance();

        $oldCurrencyCode = $db->escapeString( $oldCurrencyCode );
        $newCurrencyCode = $db->escapeString( $newCurrencyCode );

        if ( $currentVersionOnly === true )
        {
            $fetchSql = "SELECT ezmultipricedata.id
                         FROM   ezmultipricedata, ezcontentobject, ezcontentobject_attribute
                         WHERE  ezmultipricedata.currency_code = '$oldCurrencyCode'
                                AND ezcontentobject_attribute.version = ezcontentobject.current_version
                                AND ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                                AND ezmultipricedata.contentobject_attr_version = ezcontentobject_attribute.version
                                AND ezmultipricedata.contentobject_attr_id = ezcontentobject_attribute.id";

            $limit = EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT;
            while( $limit === EZ_MULTIPRICEDATA_FETCH_DATA_LIST_LIMIT  )
            {
                $dataList = $db->arrayQuery( $fetchSql, array( 'limit' => $limit ) );
                $limit = count( $dataList );

                if ( $limit > 0 )
                {
                    $idListStr = '';
                    foreach ( $dataList as $data )
                    {
                        $idListStr .= $db->escapeString( $data['id'] ). ',';
                    }

                    $idListStr = rtrim( $idListStr, ',' );

                    $sql = "UPDATE ezmultipricedata SET currency_code = '$newCurrencyCode' WHERE id IN ( $idListStr )";
                    $db->query( $sql );
                }
            }
        }
        else
        {
            $sql = "UPDATE ezmultipricedata SET currency_code = '$newCurrencyCode' WHERE currency_code = '$oldCurrencyCode'";
            $db->query( $sql );
        }
    }
}

?>