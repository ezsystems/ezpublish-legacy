<?php
//
// Definition of eZImage class
//
// Created on: <30-Apr-2002 16:47:08 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZImage ezimage.php
  \ingroup eZDatatype
  \brief The class eZImage handles registered images

  \deprecated
*/

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentclassattribute.php" );
//include_once( "kernel/classes/datatypes/ezimage/ezimagevariation.php");

class eZImage extends eZPersistentObject
{
    function eZImage( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
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
                                         "alternative_text" => array( 'name' => "AlternativeText",
                                                                      'datatype' => 'string',
                                                                      'default' => '',
                                                                      'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "version" ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "eZContentObjectAttribute",
                                                                                   "field" => "id" ) ),
                      "class_name" => "eZImage",
                      "name" => "ezimage" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function hasAttribute( $attr )
    {
        $imageIni = eZINI::instance( 'image.ini' );
        if ( $imageIni->hasVariable( 'ImageSizes', 'Height' ) )
            $heightList = $imageIni->variable( 'ImageSizes', 'Height' );
        if ( $imageIni->hasVariable( 'ImageSizes', 'Width' ) )
            $widthList = $imageIni->variable( 'ImageSizes', 'Width' );

        if ( $heightList != null )
        {
            foreach ( array_keys ( $heightList ) as $key )
            {
                if ( ( $attr != "small" and
                       $attr != "medium" and
                       $attr != "large" and
                       $attr != "reference" and
                       $attr != "original" ) and
                     $attr == $key )
                {
                    $attr = "custom_size";
                    return $attr == "custom_size";
                }
            }
        }
        return $attr == 'mime_type_category' or
               $attr == 'mime_type_part' or
               eZPersistentObject::hasAttribute( $attr ) or
               $attr == 'small' or
               $attr == 'large' or
               $attr == 'medium' or
               $attr == 'reference' or
               $attr == 'original';
    }

    function attribute( $attr )
    {
        $ini = eZINI::instance();

        $imageIni = eZINI::instance( 'image.ini' );
        if ( $imageIni->hasVariable( 'ImageSizes', 'Height' ) )
            $heightList = $imageIni->variable( 'ImageSizes', 'Height' );
        if ( $imageIni->hasVariable( 'ImageSizes', 'Width' ) )
            $widthList = $imageIni->variable( 'ImageSizes', 'Width' );

        foreach ( array_keys ( $heightList ) as $key )
        {
            if ( ( $attr != "small" or
                   $attr != "medium" or
                   $attr != "large" or
                   $attr != "reference" or
                   $attr != "original" ) and
                 $attr == $key )
            {
                $attr = "custom_size";
                $customHeight = $heightList[$key];
                $customWidth = $widthList[$key];
            }
        }
        switch( $attr )
        {
            case "mime_type_category":
            {
                $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
                return $types[0];
            }break;
            case "mime_type_part":
            {
                $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
                return $types[1];
            } break;
            case "small":
            case "medium":
            case "large":
            case "reference":
            case "original":
            case "custom_size":
            {
                if ( $attr == "small" )
                {
                    $width = $ini->variable( "ImageSettings" , "SmallSizeWidth" );
                    $height = $ini->variable( "ImageSettings" , "SmallSizeHeight" );
                }
                else if ( $attr == "medium" )
                {
                    $width = $ini->variable( "ImageSettings" , "MediumSizeWidth" );
                    $height = $ini->variable( "ImageSettings" , "MediumSizeHeight" );
                }
                else if ( $attr == "large" )
                {
                    $width = $ini->variable( "ImageSettings" , "LargeSizeWidth" );
                    $height = $ini->variable( "ImageSettings" , "LargeSizeHeight" );
                }
                else if ( $attr == "reference" )
                {
                    $width = $ini->variable( "ImageSettings" , "ReferenceSizeWidth" );
                    $height = $ini->variable( "ImageSettings" , "ReferenceSizeHeight" );
                }
                else if ( $attr == "custom_size" )
                {
                    $width = $customWidth;
                    $height = $customHeight;
                }
                else
                {
                    $width = false;
                    $height = false;
                }

                if ( $heightList != null )
                {
                    foreach ( $heightList as $key => $heightValue )
                    {
                        if ( $heightValue )
                        {
                            if ( $key == "small" and $attr == "small" )
                            {
                                $height = $heightValue;
                                $width = $widthList[$key];
                            }
                            else if ( $key == "medium" and $attr == "medium" )
                            {
                                $height = $heightValue;
                                $width = $widthList[$key];
                            }
                            else if ( $key == "large" and $attr == "large" )
                            {
                                $height = $heightValue;
                                $width = $widthList[$key];
                            }
                            else if ( $key == "reference" and $attr == "reference" )
                            {
                                $height = $heightValue;
                                $width = $widthList[$key];
                            }
                            else
                            {
                                // No changes
                            }
                        }
                    }
                }

                $cacheString = $this->ContentObjectAttributeID . '-' . $attr . "-" . $width . "-" . $height;
                if ( $attr == "original" )
                    $cacheString = $this->ContentObjectAttributeID . '-' . $attr;

                if ( !isset( $GLOBALS[$cacheString] ) )
                {
                    if ( $attr == "original" )
                    {
                        $img_variation = eZImageVariation::createOriginal( $this->ContentObjectAttributeID, $this->Version, $this->Filename, eZDir::getPathFromFilename( $this->Filename ) );
                    }
                    else
                    {
                        $img_variation = eZImageVariation::requestVariation( $this, $width, $height );
                    }
                    $GLOBALS[$cacheString] = $img_variation;
                }
                else
                {
                    $img_variation = $GLOBALS[$cacheString];
                }
                return $img_variation;
            }break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    function create( $contentObjectAttributeID, $contentObjectAttributeVersion  )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "version" => $contentObjectAttributeVersion,
                      "filename" => "",
                      "original_filename" => "",
                      "mime_type" => ""
                      );
        return new eZImage( $row );
    }

    function fetch( $id, $version = null, $asObject = true )
    {
        if( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZImage::definition(),
                                                        null,
                                                        array( "contentobject_attribute_id" => $id ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZImage::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $id,
                                                           "version" => $version ),
                                                    $asObject );
        }
    }

    function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZImage::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function remove( $id, $version )
    {
        if( $version == null )
        {
            eZPersistentObject::removeObject( eZImage::definition(),
                                              array( "contentobject_attribute_id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZImage::definition(),
                                              array( "contentobject_attribute_id" => $id,
                                                     "version" => $version ) );
        }
    }

    public $Version;
    public $ContentObjectAttributeID;
    public $Filename;
    public $OriginalFilename;
    public $MimeType;
}

?>
