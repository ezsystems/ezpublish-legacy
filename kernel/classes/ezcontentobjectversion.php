<?php
//
// Definition of eZContentObjectVersion class
//
// Created on: <18-Apr-2002 10:05:34 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
  \class eZContentObjectVersion ezcontentobjectversion.php
  \brief The class eZContentObjectVersion handles different versions of an content object
  \ingourp eZKernel

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

include_once( "kernel/classes/ezcontentobjectattribute.php" );
include_once( "kernel/classes/ezcontentobjecttranslation.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

class eZContentObjectVersion extends eZPersistentObject
{
    function eZContentObjectVersion( $row=array() )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( 'id' => 'ID',
                                         'contentobject_id' => 'ContentObjectID',
                                         'creator_id' => 'CreatorID',
                                         'version' => 'Version',
                                         'status' => 'Status',
                                         'created' => 'Created',
                                         'modified' => 'Modified',
                                         'workflow_event_pos' => 'WorkflowEventPos' ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'data' => 'fetchData', 'creator' => 'creator' ),
                      'class_name' => "eZContentObjectVersion",
                      'sort' => array( 'version' => 'asc' ),
                      'name' => 'ezcontentobject_version' );
    }

    function &fetch( $id, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectVersion::definition(), $id, $as_object );
    }

    /*!
     Returns an array with all the translations for the current version.
    */
    function translations()
    {
        $db =& eZDB::instance();

        $query = "SELECT language_code FROM ezcontentobject_attribute
                  WHERE contentobject_id='$this->ContentObjectID' AND version='$this->Version'
                  GROUP BY language_code";

        $languageCodes =& $db->arrayQuery( $query );

        $translations = array();
        foreach ( $languageCodes as $languageCode )
        {
            $translations[] = new eZContentObjectTranslation( $this->ContentObjectID, $this->Version, $languageCode["language_code"] );
        }

        return $translations;
    }


    function &fetchVersion( $version, $contentObjectID, $as_object = true )
    {
        $ret =& eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( "version" => $version,
                                                                 "contentobject_id" => $contentObjectID
                                                                 ),
                                                    null, null,
                                                     $as_object );

        return $ret[0];
    }

    /*!
     \return true if the requested attribute exists in object.
    */
    function hasAttribute( $attr )
    {
        return $attr == 'creator' or eZPersistentObject::hasAttribute( $attr );
    }

    /*!
     \return the attribute with the requested name.
    */
    function &attribute( $attr )
    {
        if ( $attr == 'creator' )
        {
            return $this->creator();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
     Returns the attributes for the current content object version. The wanted language
     must be specified.
    */
    function attributes( $language = "en_GB", $as_object = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null, array( "version" => $this->Version,
                                                                 "contentobject_id" => $this->ContentObjectID,
                                                                 "language_code" => $language
                                                                 ),
                                                    null, null,
                                                    $as_object );
    }

    /*!
     \return the creator of the current version.
    */
    function &creator()
    {
        return eZContentObject::fetch( $this->CreatorID );
    }
}

?>
