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

        $conds['contentobject_attribute_id'] = $contentAttributeID;
        $conds['contentobject_attribute_version'] = $contentObjectVersion;

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

        /*
        $whereString = '';
        if ( is_array( $contentAttributeID ) && is_array( $contentObjectVersion ) && ( count( $contentAttributeID ) === count( $contentObjectVersion ) ) )
        {
            $orString = '';
            for( $i = 0; $i < count( $contentAttributeID ); ++$i )
            {
                $attributeID = $contentAttributeID[$i];
                $version = $contentObjectVersion[$i];
                $whereString .= $orString . " ( contentobject_attribute_id = '$attributeID' AND contentobject_attribute_version = '$version' ) ";
                $orString = 'OR';
            }
        }
        else if ( is_numeric( $contentAttributeID ) && is_numeric( $contentObjectVersion ) )
        {
            $whereString .= "( contentobject_attribute_id = '$contentAttributeID' AND contentobject_attribute_version = '$contentObjectVersion' ) ";
        }
        else
        {
            // something wrong with input parameters.
            // return null;
            return $priceList;
        }

        if ( $currencyCode != false )
        {
            if ( is_array( $currencyCode ) )
                $whereString .= 'AND code IN (' . implode( ',', $currencyCode ) . ')';
            else
                $whereString .= "AND code = '$currencyCode'";
        }

        $db =& eZDB::instance();
        $db->begin();

        $sql = ( "SELECT contentobject_attribute_id, contentobject_attribute_version, currency_code, value, type
            FROM ezmultipricedata
            WHERE $whereString
            ORDER BY contentobject_attribute_id ASC" );

        $rows = $db->arrayQuery( $sql );

        $db->commit();

        if ( count( $rows ) > 0 )
        {
            if ( $asObjects )
            {
                foreach( $rows as $row )
                    $priceList[] = new eZMultiPriceData( $row );
            }
            else
            {
                $priceList =& $rows;
            }
        }

        return $priceList;
        */
    }

    /*!
     \static
    */
    /*
    function fetchListCount()
    {
        $rows = eZPersistentObject::fetchObjectList( eZMultiPriceData::definition(),
                                                     array(), null, null, null,
                                                     false, false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }
    */
    /*!
     \static
    */
    /*
    function fetch( $code, $asObject = true )
    {
        return eZMultiPriceData::fetchList( $code, $asObject );
    }
    */

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
                                              array( 'contentobject_attribute_id' => $objectAttributeID ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZMultiPriceData::definition(),
                                              array( 'contentobject_attribute_id' => $objectAttributeID,
                                                     'contentobject_attribute_version' => $objectAttributeVersion ) );
        }

        $db->commit();
    }


    /*!
     \static
    */
    /*
    function typeList()
    {
        return array( EZ_MULTIPRICEDATA_VALUE_TYPE_CUSTOM,
                      EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO );
    }
    */
}

?>