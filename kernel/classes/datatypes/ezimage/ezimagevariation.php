<?php
//
// Definition of eZImageVariation class
//
// Created on: <01-Jul-2002 15:12:40 sp>
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

/*! \file ezimagevariation.php
*/

/*!
  \class eZImageVariation ezimagevariation.php
  \brief The class eZImageVariation does

*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/common/image.php" );

class eZImageVariation extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZImageVariation( $row )
    {
        $this->eZPersistentObject( $row );
        $this->IsOriginal = false;
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
                                         "additional_path" => array( 'name' => "AdditionalPath",
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         "requested_width" => array( 'name' => "RequestedWidth",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         "requested_height" => array( 'name' => "RequestedHeight",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "width" => array( 'name' => "Width",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "height" => array( 'name' => "Height",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id, version, requestedwidth, requestedheight" ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "ezcontentobjectattribute",
                                                                         "field" => "id" ) ),
                      "class_name" => "eZImageVariation",
                      "name" => "ezimagevariation" );
    }

    function &createOriginal( $contentObjectAttributeID, $version, $filename, $additionalPath )
    {
        $additionalPath = false;
        $row = array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                      'version' => $version,
                      'filename' => $filename,
                      'additional_path' => $additionalPath );
        $variation = new eZImageVariation( $row );
        $variation->IsOriginal = true;
        $fullPath = $variation->attribute( 'full_path' );
        if ( file_exists( $fullPath ) )
        {
            if ( function_exists( 'getimagesize' ) )
            {
                $info = getimagesize( $fullPath );
                if ( $info )
                {
                    $width = $info[0];
                    $height = $info[1];
                    $variation->setAttribute( 'width', $width );
                    $variation->setAttribute( 'height', $height );
                    $variation->setAttribute( 'requested_width', $width );
                    $variation->setAttribute( 'requested_height', $height );
                }
            }
            else
                eZDebug::writeError( "Unknown function 'getimagesize' cannot get image size", 'eZImageVariation::createOriginal' );
        }
        else
            eZDebug::writeError( "Unknown imagefile '$fullPath'", 'eZImageVariation::createOriginal' );
        return $variation;
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function &attribute( $attr )
    {
        if ( $attr == "full_path" )
            return $this->fullPath();

        return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "full_path" )
            return true;

        return eZPersistentObject::hasAttribute( $attr );
    }

    function fetchVariation( $contentobjectAttributeID, $version, $rwidth, $rheight )
    {
        $ret =& eZPersistentObject::fetchObjectList( eZImageVariation::definition(),
                                                     null, array( "contentobject_attribute_id" => $contentobjectAttributeID,
                                                                  "version" => $version,
                                                                  "requested_width" => $rwidth,
                                                                  "requested_height" => $rheight
                                                                  ),
                                                     null, null,
                                                     true );
        if ( count( $ret ) )
        {
            return $ret[0];
        }
        else
        {
            return null;
        }
    }

    function &removeVariation( $id, $version )
    {
        if( $version == null )
        {
            eZPersistentObject::removeObject( eZImageVariation::definition(),
                                              array( "contentobject_attribute_id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZImageVariation::definition(),
                                              array( "contentobject_attribute_id" => $id,
                                                     "version" => $version ) );
        }
    }

    function &requestVariation( $ezimageobj, $rwidth = 50, $rheight = 50 )
    {
        $contentobjectAttributeID = $ezimageobj->attribute( "contentobject_attribute_id" );
        $version = $ezimageobj->attribute( "version" );
        if( !(( $imagevariation = eZImageVariation::fetchVariation( $contentobjectAttributeID, $version, $rwidth, $rheight ) ) === null) )
        {
            $variationFileName = $imagevariation->attribute( "filename" );
            $fileName = $ezimageobj->attribute( "filename" );
            $fileName = preg_replace('/\.(.*)$/', "", $fileName ) ;
            if( preg_match( "/$fileName/", $variationFileName ) )
                return $imagevariation;
            else
                $imagevariation->removeVariation( $contentobjectAttributeID, $version );
        }

        include_once( "lib/ezutils/classes/ezini.php" );
        include_once( "lib/ezfile/classes/ezdir.php" );

        $ini =& eZINI::instance();
        $sys =& eZSys::instance();
        $StoragePath = $sys->storageDirectory();
        $cat = $ezimageobj->attribute( "mime_type_category" );

        $referencePath = $StoragePath . '/' . $ini->variable( "ImageSettings", "ReferenceDir" ) . '/' . $cat;
        $variationPath = $StoragePath . '/' . $ini->variable( "ImageSettings", "VariationsDir" ) . '/' . $cat;
        $origFilename = $ezimageobj->attribute( "filename" );
        $imgSuffix  =   '_' . $rwidth . 'x' . $rheight . '_' . $contentobjectAttributeID ;
        $destFilename = preg_replace('/\.(.*)$/', "$imgSuffix.$1", $origFilename) ;

        $additionalPath = eZDir::getPathFromFilename( $origFilename );
        eZDir::mkdir( $variationPath . '/' . $additionalPath , 0777, true);

        $img =& imageInit();

        $convertedName = $ezimageobj->attribute( "filename" );

        $imgINI =& eZINI::instance( 'image.ini' );
        $ruleList = $imgINI->variableArray( 'Rules', 'Rules' );
        foreach ( $ruleList as $items )
        {
            $sourceMIME = $items[0];
            $destMIME = $items[1];
            $type = $items[2];
            if ( $type == 'convert' or
                 $type == 'gd' )
            {
               $sourceMIME = str_replace("image/", "", $sourceMIME );
               $destMIME = str_replace("image/", "", $destMIME );
               $convertedName = str_replace( $sourceMIME, $destMIME, $convertedName );
            }
        }

        $refImagename = $img->convert( $referencePath . '/' . $convertedName,
                                       $variationPath . '/' . $additionalPath . '/' . $destFilename,
                                       array( "width" => $rwidth, "height" => $rheight ),
                                       false
                                       );

        $refImageFilename = explode( ':', $refImagename);
        $refImagename = $variationPath . '/' . $additionalPath . '/' . $refImageFilename[ 1 ];

        $imgsize = getimagesize ( $refImagename  );
        unset( $refImagename );

        $imageVariation = new eZImageVariation( array("contentobject_attribute_id" => $ezimageobj->attribute( "contentobject_attribute_id" ),
                                                      "version" => $ezimageobj->attribute( "version" ),
                                                      "filename" =>   $refImageFilename[1],
                                                      "additional_path" => $additionalPath,
                                                      "requested_width" =>  $rwidth,
                                                      "requested_height" =>  $rheight,
                                                      "width" =>  $imgsize[0],
                                                      "height" =>  $imgsize[1]  ) );

        $imageFullPath = $variationPath . '/' . $additionalPath . '/' . $refImageFilename[1];


        if ( filesize( $imageFullPath ) == 0 || !file_exists( $imageFullPath ) )
        {
            eZDebug::writeError( "Could not create variation for $imageFullPath" );
            return false;
        }
        else
        {
            $imageVariation->store();
        }

        return $imageVariation;
    }

    function &fullPath()
    {
        $sys =& eZSys::instance();
        $ini =& eZINI::instance();
        $contentobjectAttributeID = $this->attribute( "contentobject_attribute_id" );
        $version = $this->attribute( "version" );
        $img_obj = eZImage::fetch( $contentobjectAttributeID, $version );

        $storageDir = $sys->storageDirectory();
        $category = $img_obj->attribute( "mime_type_category" );
        $additionalPath = $this->attribute( "additional_path" );
        $filename = $this->attribute( "filename" );
        if ( $this->IsOriginal )
            $variationPath = $ini->variable( "ImageSettings", "OriginalDir" );
        else
            $variationPath = $ini->variable( "ImageSettings", "VariationsDir" );

        return eZDir::path( array( $storageDir, $variationPath, $category, $additionalPath, $filename ) );
    }

    var $Version;
    var $ContentObjectAttributeID;
    var $Filename;
    var $RequestedWidth;
    var $RequestedHeight;
    var $Width;
    var $Height;
    var $IsOriginal;
}

?>
