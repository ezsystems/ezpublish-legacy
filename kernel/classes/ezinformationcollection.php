<?php
//
// Created on: <02-Dec-2002 13:15:49 bf>
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
  \class eZInformationCollection ezinformationcollection.php
  \ingroup eZKernel
  \brief The class eZInformationCollection handles information collected by content objects

  Content objects can contain attributes which are able to collect information.
  The information collected is handled by the eZInformationCollection class.

*/

include_once( 'kernel/classes/ezinformationcollectionattribute.php' );

class eZInformationCollection extends eZPersistentObject
{
    function eZInformationCollection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZInformationCollection class.
    */
    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      'keys' => array( 'id' ),
                      "function_attributes" => array( "attributes" => "attributes",
                                                      "object" => "object" ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollection',
                      'name' => 'ezinfocollection' );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'attributes' )
        {
            return $this->informationCollectionAttributes();
        }
        else if ( $attr == 'object' )
        {
            return $this->object();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &informationCollectionAttributes( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZInformationCollectionAttribute::definition(),
                                                    null,
                                                    array( "informationcollection_id" => $this->ID ),
                                                    null, null,
                                                    $asObject );
    }

    function object()
    {
        return eZContentObject::fetch( $this->ContentObjectID );
    }

    /*!
     Creates a new eZInformationCollection instance.
    */
    function &create( $contentObjectID )
    {
        $row = array(
            "contentobject_id" => $contentObjectID,
            "created" => mktime() );
        return new eZInformationCollection( $row );
    }
}

?>
