<?php
//
// Definition of eZProductCollectionItemOption class
//
// Created on: <10-æÅ×-2003 16:04:18 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezproductcollectionitemoption.php
*/

/*!
  \class eZProductCollectionItemOption ezproductcollectionitemoption.php
  \brief The class eZProductCollectionItemOption does

*/

class eZProductCollectionItemOption extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZProductCollectionItemOption( $row )
    {
        $this->eZPersistentObject( $row );

    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'item_id' => array( 'name' => 'ItemID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'option_item_id' => array( 'name' => 'OptionItemID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         'object_attribute_id' => array( 'name' => 'ObjectAttributeID',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCollectionItemOption",
                      "name" => "ezproductcollection_item_opt" );
    }

    function &create( $productCollectionItemID, $optionItemID, $optionName, $optionValue, $optionPrice, $attributeID )
    {
        $row = array( 'item_id' => $productCollectionItemID,
                      'option_item_id' => $optionItemID,
                      'name' => $optionName,
                      'value' => $optionValue,
                      'price' => $optionPrice,
                      'object_attribute_id' => $attributeID );
        return new eZProductCollectionItemOption( $row );
    }

    /*!
     Clones the collection item option object and returns it. The ID of the clone is erased.
    */
    function &clone()
    {
        $item = $this;
        $item->setAttribute( 'id', null );
        return $item;
    }

    /*!
     Copies the collection object item option,
     the new copy will point to the collection item \a $collectionItemID.
     \return the new collection item option object.
     \note The new collection item option will already be present in the database.
    */
    function &copy( $collectionItemID )
    {
        $item =& $this->clone();
        $item->setAttribute( 'item_id', $collectionItemID );
        $item->store();
        return $item;
    }

    function &fetchList( $productCollectionItemID, $asObject = true )
    {
        $productItemOptions =& eZPersistentObject::fetchObjectList( eZProductCollectionItemOption::definition(),
                                                                    null, array( "item_id" => $productCollectionItemID,
                                                                                 ),
                                                                    array( "id" => "ASC"  ),
                                                                    null,
                                                                    $asObject );
        return $productItemOptions;
    }

    /*!
     \static
     Removes all product collections options which are related to the collection items specified in the array \a $itemIDList.
    */
    function cleanupList( $itemIDList )
    {
        $db =& eZDB::instance();
        $idText = implode( ', ', $itemIDList );
        $db->query( "DELETE FROM ezproductcollection_item_opt WHERE item_id IN ( $idText )" );
    }

}

?>
