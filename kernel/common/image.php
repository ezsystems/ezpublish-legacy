<?php
//
// Definition of Image class
//
// Created on: <08-May-2002 10:15:05 amos>
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

function &imageInit()
{
    include_once( "lib/ezimage/classes/ezimagemanager.php" );
    include_once( "lib/ezimage/classes/ezimageshell.php" );
    include_once( "lib/ezimage/classes/ezimagegd.php" );

    $img =& $GLOBALS["eZPublishImage"];
    if ( get_class( $img ) == "ezimagemanager" )
        return $img;

    $img =& eZImageManager::instance();


    $ini =& eZINI::instance();
    if ( $ini->variable( "ImageSettings", "ScaleLargerThenOriginal" ) == 'true' )
    {

        $img->registerType( "convert", new eZImageShell( "convert", array(), array(),
                                                         array( eZImageShell::createRule( "-geometry %wx%h",
                                                                                          "modify/scale" ),
                                                                eZImageShell::createRule( "-colorspace GRAY",
                                                                                          "colorspace/gray" ) ),
                                                         EZ_IMAGE_KEEP_SUFFIX,
                                                         EZ_IMAGE_PREPEND_SUFFIX_TAG ) );
        $img->registerType( "gd", new eZImageGD( EZ_IMAGE_KEEP_SUFFIX,
                                                 EZ_IMAGE_PREPEND_SUFFIX_TAG ) );
    }
    else
    {
        $img->registerType( "convert", new eZImageShell( "convert", array(), array(),
                                                         array( eZImageShell::createRule( "-geometry %wx%h>",
                                                                                          "modify/scale" ),
                                                                eZImageShell::createRule( "-colorspace GRAY",
                                                                                          "colorspace/gray" ) ),
                                                         EZ_IMAGE_KEEP_SUFFIX,
                                                         EZ_IMAGE_PREPEND_SUFFIX_TAG ) );
        $img->registerType( "gd", new eZImageGD( EZ_IMAGE_KEEP_SUFFIX,
                                                 EZ_IMAGE_PREPEND_SUFFIX_TAG ) );
    }

    // Output types
    $types = array( "image/jpeg",
                    "image/png" );

    $rules = array( $img->createRule( "image/jpeg", "image/jpeg", "convert", true, true ),
                    $img->createRule( "image/png", "image/png", "convert", true, true ),
                    $img->createRule( "image/gif", "image/png", "convert", true, true ),
                    $img->createRule( "image/xpm", "image/png", "convert", true, true ) );
//     $rules = array( $img->createRule( "image/jpeg", "image/jpeg", "gd", true, true ),
//                     $img->createRule( "image/png", "image/png", "gd", true, true ),
//                     $img->createRule( "image/gif", "image/png", "convert", true, true ),
//                     $img->createRule( "image/xpm", "image/png", "convert", true, true ) );

    $mime_rules = array( $img->createMIMEType( "image/jpeg", "\.jpe?g$", "jpg" ),
                         $img->createMIMEType( "image/png", "\.png$", "png" ),
                         $img->createMIMEType( "image/gif", "\.gif$", "gif" ),
                         $img->createMIMEType( "image/xpm", "\.xpm$", "xpm" ),
                         $img->createMIMEType( "image/tiff", "\.tiff$", "tiff" ),
                         $img->createMIMEType( "image/ppm", "\.ppm$", "ppm" ),
                         $img->createMIMEType( "image/tga", "\.tga$", "tga" ),
                         $img->createMIMEType( "image/svg", "\.svg$", "svg" ),
                         $img->createMIMEType( "image/wml", "\.wml$", "wml" ),
                         $img->createMIMEType( "image/bmp", "\.bmp$", "bmp" ) );

    $img->setOutputTypes( $types );
    $img->setRules( $rules, $img->createRule( "*", "image/jpeg", "convert", true, true ) );
    $img->setMIMETypes( $mime_rules );

    return $img;
}

?>
