<?php
//
// Created on: <02-Dec-2002 14:39:39 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZInformationCollectionAttribute ezinformationcollectionattribute.php
  \ingroup eZKernel
  \brief The class eZInformationCollectionAttribute handles collected attribute information

*/

class eZInformationCollectionAttribute extends eZPersistentObject
{
    function eZInformationCollectionAttribute( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZInformationCollectionAttribute class.
    */
    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'informationcollection_id' => array( 'name' => 'InformationCollectionID',
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                         'contentclass_attribute_id' => array( 'name' => 'ContentClassAttributeID',
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true ),
                                         "data_text" => array( 'name' => "DataText",
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true ),
                                         "data_int" => array( 'name' => "DataInt",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "data_float" => array( 'name' => "DataFloat",
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      'keys' => array( 'id' ),
                      "function_attributes" => array( "contentclass_attribute_name" => "ContentClassAttributeName" ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollectionAttribute',
                      'name' => 'ezinfocollection_attribute' );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'contentclass_attribute_name' )
        {
            return $this->contentClassAttributeName();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
    */
    function &contentClassAttributeName()
    {
        $db =& eZDB::instance();
        $nameArray =& $db->arrayQuery( "SELECT name FROM ezcontentclass_attribute WHERE id='$this->ContentClassAttributeID'" );

        return $nameArray[0]['name'];
    }

    /*!
     Creates a new eZInformationCollectionAttribute instance.
    */
    function &create( $informationCollectionID )
    {
        $row = array(
            "informationcollection_id" => $informationCollectionID );
        return new eZInformationCollectionAttribute( $row );
    }
}

?>
