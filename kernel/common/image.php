<?php
//
// Definition of Image class
//
// Created on: <08-May-2002 10:15:05 amos>
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

function &imageInit()
{
    include_once( 'lib/ezimage/classes/ezimagemanager.php' );
    include_once( 'lib/ezimage/classes/ezimageshell.php' );
    include_once( 'lib/ezimage/classes/ezimagegd.php' );

    $img =& $GLOBALS['eZPublishImage'];
    if ( get_class( $img ) == 'ezimagemanager' )
        return $img;

    $img =& eZImageManager::instance();

    $ini =& eZINI::instance();
    $scale = false;
    if ( $ini->hasVariable( 'ImageSettings', 'ScaleLargerThenOriginal' ) )
    {
        $scale = $ini->variable( 'ImageSettings', 'ScaleLargerThenOriginal' ) == 'true';
        eZDebug::writeWarning( 'ImageSettings ScaleLargerThenOriginal is depricated. Use ScaleLargerThanOriginal instead' );
    }

    if ( $ini->hasVariable( 'ImageSettings', 'ScaleLargerThanOriginal' ) )
    {
        $scale = $ini->variable( 'ImageSettings', 'ScaleLargerThanOriginal' ) == 'true';
    }

    if ( $scale )
    {
        $geometry = '-geometry "%wx%h"';
    }
    else
    {
        $geometry = '-geometry "%wx%h>"';
    }

    $imgINI =& eZINI::instance( 'image.ini' );

    $useConvert = $imgINI->variable( 'ConverterSettings', 'UseConvert' ) == 'true';
    $useGD = $imgINI->variable( 'ConverterSettings', 'UseGD' ) == 'true';

    if ( $useConvert and
         !eZImageShell::isAvailable( 'convert' ) )
    {
        eZDebug::writeError( "Convert is not available, disabling", 'imageInit' );
        $useConvert = false;
    }
    if ( $useGD and
         !eZImageGD::isAvailable() )
    {
        eZDebug::writeError( "ImageGD is not available, disabling", 'imageInit' );
        $useGD = false;
    }

    if ( !$useConvert and
         !$useGD )
    {
        eZDebug::writeError( "No conversion types available", 'imageInit' );
    }

    // Register convertors
    if ( $useConvert )
    {
        $img->registerType( 'convert', new eZImageShell( $imgINI->variable( 'ShellSettings', 'ConvertPath' ), $imgINI->variable( 'ShellSettings', 'ConvertExecutable' ),
                                                         array(), array(),
                                                         array( eZImageShell::createRule( $geometry,
                                                                                          'modify/scale' ),
                                                                eZImageShell::createRule( '-colorspace GRAY',
                                                                                          'colorspace/gray' ) ),
                                                         EZ_IMAGE_KEEP_SUFFIX,
                                                         EZ_IMAGE_PREPEND_AND_REPLACE_SUFFIX_TAG ) );
    }
    if ( $useGD )
    {
        $img->registerType( 'gd', new eZImageGD() );
    }

    // Output types
    $types = $imgINI->variableArray( 'OutputSettings', 'AvailableMimeTypes' );
    if ( count( $types ) == 0 )
        $types = array( 'image/jpeg',
                        'image/png' );

    $rules = array();
    $defaultRule = null;
    $ruleList = $imgINI->variableArray( 'Rules', 'Rules' );
    foreach ( $ruleList as $items )
    {
        $sourceMIME = $items[0];
        $destMIME = $items[1];
        $type = $items[2];
        if ( $type == 'convert' or
             $type == 'gd' )
        {
            $rules[] = $img->createRule( $sourceMIME, $destMIME, $type, true, true );
        }
    }
    $defaultRuleItems = $imgINI->variableArray( 'Rules', 'DefaultRule' );
    $destMIME = $defaultRuleItems[0];
    $type = $defaultRuleItems[1];
    if ( $type == 'convert' or
         $type == 'gd' )
    {
        $defaultRule = $img->createRule( '*', $destMIME, $type, true, true );
    }

    $mime_rules = array();
    $mimeTypes = $imgINI->variableArray( 'MimeTypes', 'Types' );
    foreach ( $mimeTypes as $items )
    {
        $mimeType = $items[0];
        $regexp = $items[1];
        $suffix = $items[2];
        $mime_rules[] = $img->createMIMEType( $mimeType, $regexp, $suffix );
    }

    $img->setOutputTypes( $types );
    if ( $defaultRule === null )
        $defaultRule = $img->createRule( '*', 'image/jpeg', 'convert', true, true );
    $img->setRules( $rules, $defaultRule );
//     $img->setRules( $rules, $img->createRule( '*', 'image/jpeg', 'convert', true, true ) );
    $img->setMIMETypes( $mime_rules );

    return $img;
}

?>
