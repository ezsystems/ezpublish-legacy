<?php
//
// Created on: <17-Feb-2006 15:20:15 vs>
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

/*!
  \class eZProductCategory ezproductcategory.php
  \brief Handles product categories used by the default VAT handler.
  \ingroup eZKernel
*/

class eZProductCategory extends eZPersistentObject
{
    function eZProductCategory( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCategory",
                      "name" => "ezproductcategory" );
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCategory::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchByName( $name, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCategory::definition(),
                                                null,
                                                array( "name" => $name ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZProductCategory::definition(),
                                                    null, null, array( 'name' => 'asc' ), null,
                                                    $asObject );
    }

    /**
     * Returns number of products belonging to the given category.
     *
     * \public
     * \static
     */
    static function fetchProductCountByCategory( $categoryID )
    {
        $ini = eZINI::instance( 'shop.ini' );
        if ( !$ini->hasVariable( 'VATSettings', 'ProductCategoryAttribute' ) ||
             !$categoryAttrName = $ini->variable( 'VATSettings', 'ProductCategoryAttribute' ) )
            return 0;

        $db = eZDB::instance();
        $categoryID =(int) $categoryID;
        $categoryAttrName = $db->escapeString( $categoryAttrName );
        $query = "SELECT COUNT(*) AS count " .
                 " FROM ezcontentobject_attribute coa, ezcontentclass_attribute cca, ezcontentobject co " .
                 "WHERE " .
                 " cca.id=coa.contentclassattribute_id " .
                 " AND coa.contentobject_id=co.id " .
                 " AND cca.data_type_string='ezproductcategory' " .
                 " AND cca.identifier='$categoryAttrName' " .
                 " AND coa.version=co.current_version " .
                 " AND coa.data_int=$categoryID";
        $rows = $db->arrayQuery( $query );
        return $rows[0]['count'];
    }

    static function create()
    {
        $row = array(
            "id" => null,
            "name" => eZi18n::translate( 'kernel/shop/productcategories', 'Product category' ) );
        return new eZProductCategory( $row );
    }

    /**
     * Remove the given category and all references to it.
     *
     * \public
     * \static
     */
    static function removeByID( $id )
    {
        $id = (int) $id;

        $db = eZDB::instance();
        $db->begin();

        // Delete references to the category from VAT charging rules.
        eZVatRule::removeReferencesToProductCategory( $id );

        // Reset product category attribute for all products
        // that have been referencing the category.
        $ini = eZINI::instance( 'shop.ini' );
        if ( $ini->hasVariable( 'VATSettings', 'ProductCategoryAttribute' ) &&
             $categoryAttrName = $ini->variable( 'VATSettings', 'ProductCategoryAttribute' ) )
        {
            $categoryAttrName = $db->escapeString( $categoryAttrName );
            $query = "SELECT coa.id FROM ezcontentobject_attribute coa, ezcontentclass_attribute cca, ezcontentobject co " .
                     "WHERE " .
                     " cca.id=coa.contentclassattribute_id " .
                     " AND coa.contentobject_id=co.id " .
                     " AND cca.data_type_string='ezproductcategory' " .
                     " AND cca.identifier='$categoryAttrName' " .
                     " AND coa.version=co.current_version " .
                     " AND coa.data_int=$id";

            $rows = $db->arrayQuery( $query );

            foreach ( $rows as $row )
            {
                $query = "UPDATE ezcontentobject_attribute SET data_int=0, sort_key_int=0 WHERE id=" . (int) $row['id'];
                $db->query( $query );
            }
        }

        // Remove the category itself.
        eZPersistentObject::removeObject( eZProductCategory::definition(),
                                          array( "id" => $id ) );

        $db->commit();
    }
}

?>
