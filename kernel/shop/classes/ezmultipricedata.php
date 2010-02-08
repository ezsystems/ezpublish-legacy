<?php
//
// Definition of eZMultiPriceData class
//
// Created on: <08-Nov-2005 13:06:15 dl>
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

class eZMultiPriceData extends eZPersistentObject
{
    const VALUE_TYPE_CUSTOM = 1;
    const VALUE_TYPE_AUTO = 2;
    const FETCH_DATA_LIST_LIMIT = 5000;

    const ERROR_OK = 0;
    const ERROR_AUTOPRICES_UPDATE_FAILED = 1;

    function eZMultiPriceData( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_attr_id' => array( 'name' => 'ContentObjectAttrID',
                                                                           'datatype' => 'integer',
                                                                           'default' => 0,
                                                                           'required' => true,
                                                                           'foreign_class' => 'eZContentObjectAttribute',
                                                                           'foreign_attribute' => 'id',
                                                                           'multiplicity' => '1..*' ),
                                         'contentobject_attr_version' => array( 'name' => 'ContentObjectAttrVersion',
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
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => "eZMultiPriceData",
                      'name' => "ezmultipricedata" );
    }

    static function create( $contentObjectID, $contentObjectVersion, $currencyCode, $value, $type )
    {
        return new eZMultiPriceData( array( 'contentobject_attr_id' => $contentObjectID,
                                            'contentobject_attr_version' => $contentObjectVersion,
                                            'currency_code' => $currencyCode,
                                            'value' => $value,
                                            'type' => $type ) );
    }

    /*!
     \static
     \params
       codeList     - specifies currencies to fetch. If 'false' then all prices for '$contentAttributeID/$contentObjectVersion' will be retrieved.
    */
    static function fetch( $contentAttributeID, $contentObjectVersion, $currencyCode = false, $type = false, $asObjects = true )
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
                    $priceList[$rows[$key]->attribute( 'currency_code' )] = $rows[$key];
                else
                    $priceList[$rows[$key]['currency_code']] = $rows[$key];
            }
        }

        return $priceList;
    }

    /*!
        removes single record from 'ezmultipricedata' table
    */
    static function removeByID( $id )
    {
        $db = eZDB::instance();
        $db->begin();
        eZPersistentObject::removeObject( eZMultiPriceData::definition(),
                                          array( 'id' => $id ) );
        $db->commit();
    }


    /*!
        remove
    */
    static function removeByOAID( $objectAttributeID, $objectAttributeVersion = null )
    {
        $db = eZDB::instance();
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
    static function createPriceListForCurrency( $currencyCode )
    {
        $db = eZDB::instance();

        $dataList = false;
        $insertSql = 'INSERT INTO ezmultipricedata(contentobject_attr_id, contentobject_attr_version, currency_code, type)';

        $fetchCount = self::FETCH_DATA_LIST_LIMIT;
        $db->begin();
        while( $fetchCount === self::FETCH_DATA_LIST_LIMIT  )
        {
            $dataList = eZMultiPriceData::fetchDataListWithoutPriceInCurrency( $currencyCode, $fetchCount );
            $fetchCount = count( $dataList );

            $currencyCode = $db->escapeString( $currencyCode );
            foreach ( $dataList as $data )
            {
                $sql = $insertSql . "
                               VALUES( {$data['contentobject_attr_id']}, {$data['contentobject_attr_version']}, '$currencyCode', " . self::VALUE_TYPE_AUTO . " )";
                $db->query( $sql );
            }
        }
        $db->commit();
    }

    /*!
     \static
    */
    static function fetchDataListWithoutPriceInCurrency( $currencyCode, $limit = false )
    {
        $db = eZDB::instance();
        $currencyCode = $db->escapeString( $currencyCode );

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

        $limitCondition = array();
        if ( $limit !== false )
            $limitCondition['limit'] = $limit;

        $dataList = $db->arrayQuery( $fetchSql, $limitCondition );
        return $dataList;
    }

    static function removePriceListForCurrency( $currencyCodeList, $currentVersionOnly = true )
    {
        $db = eZDB::instance();

        if ( !is_array( $currencyCodeList ) || count( $currencyCodeList ) === 0 )
            return;

        $currencyListStr = '';
        foreach ( $currencyCodeList as $currencyCode )
        {
            $currencyListStr .= "'" . $db->escapeString( $currencyCode ) . "'" . ',';
        }

        $currencyListStr = rtrim( $currencyListStr, ',' );

        $sql = '';
        $db->begin();
        if ( $currentVersionOnly === true )
        {
            $fetchSql = "SELECT ezmultipricedata.id
                         FROM   ezmultipricedata, ezcontentobject, ezcontentobject_attribute
                         WHERE  ezmultipricedata.currency_code IN ( $currencyListStr )
                                AND ezcontentobject_attribute.version = ezcontentobject.current_version
                                AND ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                                AND ezmultipricedata.contentobject_attr_version = ezcontentobject_attribute.version
                                AND ezmultipricedata.contentobject_attr_id = ezcontentobject_attribute.id";

            $fetchCount = self::FETCH_DATA_LIST_LIMIT;
            while( $fetchCount === self::FETCH_DATA_LIST_LIMIT  )
            {
                $dataList = $db->arrayQuery( $fetchSql, array( 'limit' => $fetchCount ) );
                $fetchCount = count( $dataList );

                if ( $fetchCount > 0 )
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
        $db->commit();
    }

    static function changeCurrency( $oldCurrencyCode, $newCurrencyCode, $currentVersionOnly = true )
    {
        $db = eZDB::instance();

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

            $fetchCount = self::FETCH_DATA_LIST_LIMIT;
            while( $fetchCount === self::FETCH_DATA_LIST_LIMIT  )
            {
                $dataList = $db->arrayQuery( $fetchSql, array( 'limit' => $fetchCount ) );
                $fetchCount = count( $dataList );

                if ( $fetchCount > 0 )
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

    static function updateAutoprices()
    {
        // use direct sql-queries to speed up the process.

        $converter = eZCurrencyConverter::instance();
        $currencyList = $converter->currencyList();
        $currencyListCount = count( $currencyList );

        if ( $currencyListCount > 0 )
        {
            $fetchCount = self::FETCH_DATA_LIST_LIMIT;
            $fetchOffset = 0;
            $sql = "SELECT DISTINCT ezmultipricedata.*
                    FROM ezmultipricedata, ezcontentobject, ezcontentobject_attribute
                    WHERE ezmultipricedata.contentobject_attr_id = ezcontentobject_attribute.id
                          AND ezcontentobject_attribute.contentobject_id = ezcontentobject.id
                          AND ezmultipricedata.contentobject_attr_version = ezcontentobject.current_version
                          ORDER BY ezmultipricedata.contentobject_attr_id, ezmultipricedata.type";

            $objectAttributeID = false;
            $fromCurrency = false;
            $fromValue = 0;

            $db = eZDB::instance();
            $db->begin();
            while( $fetchCount === self::FETCH_DATA_LIST_LIMIT  )
            {
                $multipriceDataList = $db->arrayQuery( $sql, array( 'offset' => $fetchOffset, 'limit' => $fetchCount ) );
                $fetchCount = count( $multipriceDataList );
                $fetchOffset += $fetchCount;

                foreach ( $multipriceDataList as $multipriceData )
                {
                    if ( $multipriceData['contentobject_attr_id'] != $objectAttributeID )
                    {
                        // process next attribute.
                        $objectAttributeID = $multipriceData['contentobject_attr_id'];

                        // use value of the first custom price as base price.
                        $fromCurrency = $multipriceData['currency_code'];
                        $fromValue = $multipriceData['value'];
                    }

                    if ( $multipriceData['type'] == self::VALUE_TYPE_AUTO )
                    {
                        $value = $converter->convert( $fromCurrency, $multipriceData['currency_code'], $fromValue );

                        $updateSql = "UPDATE ezmultipricedata SET value = '$value' WHERE id = {$multipriceData['id']}";
                        $db->query( $updateSql );
                    }
                }
            }
            $db->commit();
        }

        $error = array( 'code' => self::ERROR_OK,
                        'description' => ezi18n( 'kernel/shop', "'Auto' prices were updated successfully." ) );

        return $error;
    }
}

?>
