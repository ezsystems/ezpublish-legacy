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
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'data_text' => array( 'name' => 'DataText',
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true ),
                                         'data_int' => array( 'name' => 'DataInt',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'data_float' => array( 'name' => 'DataFloat',
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'contentclass_attribute_name' => 'contentClassAttributeName',
                                                      'contentclass_attribute' => 'contentClassAttribute',
                                                      'contentobject_attribute' => 'contentObjectAttribute',
                                                      'contentobject' => 'contentObject',
                                                      'result_template' => 'resultTemplateName',
                                                      ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollectionAttribute',
                      'name' => 'ezinfocollection_attribute' );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'contentclass_attribute_name' )
            return $this->contentClassAttributeName();
        else if ( $attr == 'contentclass_attribute' )
            return $this->contentClassAttribute();
        else if ( $attr == 'contentobject_attribute' )
            return $this->contentObjectAttribute();
        else if ( $attr == 'result_template' )
            return $this->resultTemplateName();
        else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
     \return the template name to use for viewing the attribute
     \note The returned template name does not include the .tpl extension.
     \sa informationTemplate
    */
    function &resultTemplateName()
    {
        $dataType =& $this->dataType();
        if ( $dataType )
            return $dataType->resultTemplate( $this );
        else
            return null;
    }

    /*!
    */
    function &contentObject()
    {
        $contentObject =& eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
        return $contentObject;
    }

    /*!
    */
    function &contentObjectAttribute()
    {
        $contentObject =& $this->contentObject();
        $contentObjectAttribute =& eZContentObjectAttribute::fetch( $this->attribute( 'contentobject_attribute_id' ), $contentObject->attribute( 'current_version' ) );
        return $contentObjectAttribute;
    }

    /*!
    */
    function &contentClassAttribute()
    {
        $contentClassAttribute =& eZContentClassAttribute::fetch( $this->attribute( 'contentclass_attribute_id' ) );
        return $contentClassAttribute;
    }

    /*!
    */
    function &dataType()
    {
        $contentClassAttribute =& $this->contentClassAttribute();
        if ( $contentClassAttribute )
            return $contentClassAttribute->dataType();
        else
            return null;
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
        $row = array( 'informationcollection_id' => $informationCollectionID );
        return new eZInformationCollectionAttribute( $row );
    }

    /*!
     \static
      Fetches the information collection by object attribute ID.
    */
    function &fetchByObjectAttributeID( $id, $contentobjectAttributeID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZInformationCollectionAttribute::definition(),
                                                null,
                                                array( 'informationcollection_id' => $id,
                                                       'contentobject_attribute_id' => $contentobjectAttributeID ),
                                                $asObject );
    }

    /*!
     \static
     Removes all attributes for collected information.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezinfocollection_attribute" );
    }
}

?>
