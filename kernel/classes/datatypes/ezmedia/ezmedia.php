<?php
//
// Definition of eZBinaryFile class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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
  \class eZMedia ezmedia.php
  \ingroup eZKernel
  \brief The class eZMedia handles registered media files

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

class eZMedia extends eZPersistentObject
{
    function eZMedia( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "filename" => array( 'name' => "Filename",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "original_filename" => array( 'name' => "OriginalFilename",
                                                                       'datatype' => 'string',
                                                                       'default' => '',
                                                                       'required' => true ),
                                         "mime_type" => array( 'name' => "MimeType",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "width" => array( 'name' => "Width",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "height" => array( 'name' => "Height",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "has_controller" => array( 'name' => "HasController",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "controls" => array( 'name' => "Controls",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "is_autoplay" => array( 'name' => "IsAutoplay",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "pluginspage" => array( 'name' => "Pluginspage",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "quality" => array( 'name' => 'Quality',
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "is_loop" => array( 'name' => "IsLoop",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "version" ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "ezcontentobjectattribute",
                                                                                   "field" => "id" ),
                                            "version" => array( "class" => "ezcontentobjectattribute",
                                                                "field" => "version" )),
                      "class_name" => "eZMedia",
                      "name" => "ezmedia" );
    }

    function hasAttribute( $attr )
    {
        return $attr == "mime_type_category" or
            $attr == "mime_type_part" or
            eZPersistentObject::hasAttribute( $attr ) ;
    }

    function &attribute( $attr )
    {
        $ini =& eZINI::instance();

        switch( $attr )
        {
            case "mime_type_category":
            {
                $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
                return $types[0];
            } break;
            case "mime_type_part":
            {
                $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
                return $types[1];
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        return null;
    }

    function &create( $contentObjectAttributeID, $version )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "version" => $version,
                      "filename" => "",
                      "original_filename" => "",
                      "mime_type" => "",
                      "width" => "",
                      "height" => "",
                      "controller" => true,
                      "autoplay" => true,
                      "pluginspage" => "",
                      "is_loop" => false,
                      "quality" => "",
                      "controls" => ""
                      );
        return new eZMedia( $row );
    }

    function &fetch( $id, $version, $asObject = true )
    {
        if( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZMedia::definition(),
                                                        null,
                                                        array( "contentobject_attribute_id" => $id ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZMedia::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $id,
                                                           "version" => $version ),
                                                    $asObject );
        }
    }

    function &remove( $id, $version )
    {
        if( $version == null )
        {
            eZPersistentObject::removeObject( eZMedia::definition(),
                                              array( "contentobject_attribute_id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZMedia::definition(),
                                              array( "contentobject_attribute_id" => $id,
                                                     "version" => $version ) );
        }
    }

    var $ContentObjectAttributeID;
    var $Filename;
    var $OriginalFilename;
    var $MimeType;
    var $Width;
    var $Height;
    var $HasController;
    var $Controls;
    var $IsLoop;
    var $IsAutoplay;
    var $Pluginspage;
    var $Quality;
}

?>
