<?php
//
// Definition of eZImageVariation class
//
// Created on: <01-Jul-2002 15:12:40 sp>
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
    }

    function &definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => "ContentObjectAttributeID",
                                         "version" => "Version",
                                         "filename" => "Filename",
                                         "additional_path" => "AdditionalPath",
                                         "requested_width" => "RequestedWidth",
                                         "requested_height" => "RequestedHeight",
                                         "width" => "Width",
                                         "height" => "Height"
                                         ),
                      "keys" => array( "contentobject_attribute_id, version, requestedwidth, requestedheight" ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "ezcontentobjectattribute",
                                                                         "field" => "id" ) ),
                      "class_name" => "eZImageVariation",
                      "name" => "ezimagevariation" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function &attribute( $attr )
    {
                return eZPersistentObject::attribute( $attr );
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

    function &requestVariation( $ezimageobj, $rwidth = 50, $rheight = 50 )
    {

        $contentobjectAttributeID = $ezimageobj->attribute( "contentobject_attribute_id" );
        $version = $ezimageobj->attribute( "version" );
        if( !(( $imagevariation =  eZImageVariation::fetchVariation( $contentobjectAttributeID, $version, $rwidth, $rheight ) ) === null) )
        {
            return $imagevariation;
        }

        include_once( "lib/ezutils/classes/ezini.php" );
        include_once( "lib/ezutils/classes/ezdir.php" );

        $ini =& eZINI::instance();
        $StoragePath = $ini->variable( "FileSettings" , "StorageDir" );
        $cat = $ezimageobj->attribute( "mime_type_category" );

        $referencePath = $StoragePath . '/' . $ini->variable( "ImageSettings", "ReferenceDir" ) . '/' . $cat;
        $variationPath = $StoragePath . '/' . $ini->variable( "ImageSettings", "VariationsDir" ) . '/' . $cat;
        $origFilename = $ezimageobj->attribute( "filename" );
        $imgSuffix  =   '_' . $rwidth . 'x' . $rheight . '_' . $contentobjectAttributeID ;
        $destFilename = preg_replace('/\.(.*)$/', "$imgSuffix.$1", $origFilename) ;

        $additionalPath = eZDir::getPathFromFilename( $origFilename );
        eZDir::mkdir( $variationPath . '/' . $additionalPath , 0777, true);

        $img =& imageInit();

        $refImagename = $img->convert( $referencePath . '/'. $ezimageobj->attribute("filename"),
                                       $variationPath . '/' . $additionalPath . '/' . $destFilename,
                                       array( "width" => $rwidth, "height" => $rheight ),
                                       false
                                     );

        $refImageFilename = explode( ':', $refImagename);
        $refImagename = $variationPath . '/' . $additionalPath . '/' . $refImageFilename[ 1 ];

        $imgsize = getimagesize ( $refImagename  );
        unset ($refImagename);

        $imageVariation = new eZImageVariation( array("contentobject_attribute_id" => $ezimageobj->attribute( "contentobject_attribute_id" ),
                                                      "version" => $ezimageobj->attribute( "version" ),
                                                      "filename" =>   $refImageFilename[1],
                                                      "additional_path" => $additionalPath,
                                                      "requested_width" =>  $rwidth,
                                                      "requested_height" =>  $rheight,
                                                      "width" =>  $imgsize[0],
                                                      "height" =>  $imgsize[1]  ) );
        $imageVariation->store();
        return $imageVariation;
    }

    var $Version;
    var $ContentObjectAttributeID;
    var $Filename;
    var $RequestedWidth;
    var $RequestedHeight;
    var $Width;
    var $Height;
}

?>
