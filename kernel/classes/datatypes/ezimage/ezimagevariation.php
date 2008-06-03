<?php
//
// Definition of eZImageVariation class
//
// Created on: <01-Jul-2002 15:12:40 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezimagevariation.php
*/

/*!
  \class eZImageVariation ezimagevariation.php
  \ingroup eZDatatype
  \brief The class eZImageVariation does

  \deprecated
*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentclassattribute.php" );
require_once( "kernel/common/image.php" );

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
                      "function_attributes" => array( 'full_path' => 'fullPath' ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "eZContentObjectAttribute",
                                                                         "field" => "id" ) ),
                      "class_name" => "eZImageVariation",
                      "name" => "ezimagevariation" );
    }

    function createOriginal( $contentObjectAttributeID, $version, $filename, $additionalPath )
    {
        $additionalPath = false;
        $row = array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                      'version' => $version,
                      'filename' => $filename,
                      'additional_path' => $additionalPath );
        $variation = new eZImageVariation( $row );
        $variation->IsOriginal = true;
        $fullPath = $variation->attribute( 'full_path' );

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $file = eZClusterFileHandler::instance( $fullPath );

        if ( $file->exists() )
        {
            if ( function_exists( 'getimagesize' ) )
            {
                $fetchedFilePath = $file->fetchUnique();
                $info = getimagesize( $fetchedFilePath  );
                $file->fileDeleteLocal( $fetchedFilePath );
//                $file->fetch();
//                $info = getimagesize( $fullPath );
//                $file->deleteLocal();
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
            {
                eZDebug::writeError( "Unknown function 'getimagesize' cannot get image size", 'eZImageVariation::createOriginal' );
            }
        }
        else
        {
            eZDebug::writeError( "Unknown imagefile '$fullPath'", 'eZImageVariation::createOriginal' );
        }
        return $variation;
    }

    function fetchVariation( $contentobjectAttributeID, $version, $rwidth, $rheight )
    {
        $ret = eZPersistentObject::fetchObjectList( eZImageVariation::definition(),
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

    function removeVariation( $id, $version )
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

        //include_once( "lib/ezutils/classes/ezini.php" );
        //include_once( "lib/ezfile/classes/ezdir.php" );

        $ini = eZINI::instance();
        $sys = eZSys::instance();
        $StoragePath = $sys->storageDirectory();
        $cat = $ezimageobj->attribute( "mime_type_category" );

        $referencePath = $StoragePath . '/' . $ini->variable( "ImageSettings", "ReferenceDir" ) . '/' . $cat;
        $variationPath = $StoragePath . '/' . $ini->variable( "ImageSettings", "VariationsDir" ) . '/' . $cat;
        $origFilename = $ezimageobj->attribute( "filename" );
        $imgSuffix  =   '_' . $rwidth . 'x' . $rheight . '_' . $contentobjectAttributeID ;
        $destFilename = preg_replace('/\.(.*)$/', "$imgSuffix.$1", $origFilename) ;

        $additionalPath = eZDir::getPathFromFilename( $origFilename );
        eZDir::mkdir( $variationPath . '/' . $additionalPath , 0777, true);

        $img = imageInit();

        $convertedName = $ezimageobj->attribute( "filename" );

        $imgINI = eZINI::instance( 'image.ini' );
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

        // VS-DBFILE

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

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $file = eZClusterFileHandler::instance( $imageFullPath );

        if ( !$file->exists() || $file->size() == 0 )
        {
            eZDebug::writeError( "Could not create variation for $imageFullPath" );
            $retValue = false;
            return $retValue;
        }
        else
        {
            $imageVariation->store();
        }

        return $imageVariation;
    }

    function fullPath()
    {
        $sys = eZSys::instance();
        $ini = eZINI::instance();
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

    public $Version;
    public $ContentObjectAttributeID;
    public $Filename;
    public $RequestedWidth;
    public $RequestedHeight;
    public $Width;
    public $Height;
    public $IsOriginal;
}

?>
