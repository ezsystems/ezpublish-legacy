<?php
//
// Definition of eZGIFImageAnalyzer class
//
// Created on: <03-Nov-2003 15:19:16 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezgifimageanalyzer.php
*/

/*!
  \class eZGIFImageAnalyzer ezgifimageanalyzer.php
  \ingroup eZImageAnalyzer
  \brief The class eZGIFImageAnalyzer does

*/

class eZGIFImageAnalyzer
{
    /*!
     Constructor
    */
    function eZGIFImageAnalyzer()
    {
    }

    /*!
     \reimp
     Checks the file for GIF data blocks and returns information on the GIF file.
    */
    function process( $mimeData, $parameters = array() )
    {
        $printInfo = false;
        if ( isset( $parameters['print_info'] ) )
            $printInfo = $parameters['print_info'];

        $filename = $mimeData['url'];
        $fd = fopen( $filename, 'rb' );
        if ( $fd )
        {
            $blockTypes = array( 0x21 => 'Exension Introducer',
                                 0x2c => 'Image Descriptor',
                                 0x3b => 'Trailer' );
            $extensionLabels = array( 0xf9 => 'Graphical Control Extension',
                                      0xff => 'Application Extension',
                                      0xfe => 'Comment Extension' );
            // Read GIF header
            $data = fread( $fd, 6 );
            if ( $data == 'GIF87a' or
                 $data == 'GIF89a' )
            {
                $info = array();

                $info['version'] = substr( $data, 3 );
                $info['frame_count'] = 0;
                $info['mode'] = EZ_IMAGE_MODE_INDEXED;
                $offset = 6;

                $info['animation_timer'] = false;
                $info['animation_timer_type'] = EZ_IMAGE_TIMER_HUNDRETHS_OF_A_SECOND;

                $info['comment_list'] = array();

                $info['transparency_type'] = EZ_IMAGE_TRANSPARENCY_OPAQUE;

                // Read Logical Screen Descriptor
                $data = fread( $fd, 7 );
                $offset += 7;

                $lsdFields = ord( $data[4] );

                $globalColorCount = 0;
                $globalColorTableSize = 0;
                if ( $lsdFields >> 7 )
                {
                    $globalColorCount = ( 1 << ( ( $lsdFields & 0x07 ) + 1) );
                    $globalColorTableSize = $globalColorCount * 3;
                }

                $info['color_count'] = $globalColorCount;

                $info['width'] = ( ord( $data[1] ) << 8 ) + ord( $data[0] );
                $info['height'] = ( ord( $data[3] ) << 8 ) + ord( $data[2] );


                if ( $globalColorTableSize )
                {
                    fseek( $fd, $globalColorTableSize, SEEK_CUR );
                    $offset += $globalColorTableSize;
                }
                if ( $printInfo )
                    print( "Global color table size=$globalColorTableSize\n" );

                $done = false;
                while ( !$done )
                {
                    $data = fread( $fd, 1 );
                    $offset += 1;
                    $blockType = ord( $data[0] );
                    if ( $printInfo )
                    {
                        print( "Block type=0x" . dechex( $blockType ) );
                        if ( isset( $blockTypes[$blockType] ) )
                            print( " (" . $blockTypes[$blockType] . ")" );
                        print( "\n" );
                    }
                    if ( $blockType == 0x21 ) // Extension Introducer
                    {
                        $data .= fread( $fd, 1 );
                        $offset += 1;
                        $extensionLabel = ord( $data[1] );
                        if ( $printInfo )
                        {
                            print( "> " . "Extension label=0x" . dechex( $extensionLabel ) );
                            if ( isset( $extensionLabels[$extensionLabel] ) )
                                print( " (" . $extensionLabels[$extensionLabel] . ")" );
                            print( "\n" );
                        }
                        if ( $extensionLabel == 0xf9 ) // Graphical Control Extension
                        {
                            $data = fread( $fd, 5 + 1 );
                            $info['animation_timer'] = ( ord( $data[3] ) << 8 ) + ord( $data[2] );
                            $gceFlags = ord( $data[1] );
                            if ( $gceFlags & 0x01 )
                                $info['transparency_type'] = EZ_IMAGE_TRANSPARENCY_TRANSPARENT;
                            $offset += 5 + 1;
                        }
                        else if ( $extensionLabel == 0xff ) // Application Extension
                        {
                            $data = fread( $fd, 12 );
                            if ( $printInfo )
                            {
                                $applicationIdentifier = substr( $data, 1, 8 );
                                $applicationAuthentication = substr( $data, 1 + 8, 3 );
                                print( ">> Application identifier: $applicationIdentifier\n" );
                                print( ">> Application authentication: $applicationAuthentication\n" );
                            }
                            $offset += 12;

                            $dataBlockDone = false;
                            while ( !$dataBlockDone )
                            {
                                $data = fread( $fd, 1 );
                                $offset += 1;
                                $blockBytes = ord( $data[0] );
                                if ( $printInfo )
                                    print( ">> Application Block size=$blockBytes\n" );
                                if ( $blockBytes )
                                {
                                    fseek( $fd, $blockBytes, SEEK_CUR );
                                    $offset += $blockBytes;
                                }
                                else
                                {
                                    if ( $printInfo )
                                        print( ">> GIF application blocks terminated by 0 image block\n" );
                                    $dataBlockDone = true;
                                }
                            }
                        }
                        else if ( $extensionLabel == 0xfe ) // Comment Extension
                        {
                            $commentBlockDone = false;
                            $comment = false;
                            while ( !$commentBlockDone )
                            {
                                $data = fread( $fd, 1 );
                                $offset += 1;
                                $blockBytes = ord( $data[0] );
                                if ( $printInfo )
                                    print( ">> Comment Block size=$blockBytes\n" );
                                if ( $blockBytes )
                                {
                                    $data = fread( $fd, $blockBytes );
                                    if ( $printInfo )
                                        print( $data );
                                    $comment .= $data;
                                    $offset += $blockBytes;
                                }
                                else
                                {
                                    if ( $printInfo )
                                        print( ">> GIF comment blocks terminated by 0 image block\n" );
                                    $commentBlockDone = true;
                                }
                            }
                            if ( $comment )
                                $info['comment_list'][] = $comment;
                        }
                        else
                        {
                            if ( $printInfo )
                                print( "> Unknown extension label, aborting\n" );
                            $done = true;
                        }
                    }
                    else if ( $blockType == 0x2c ) // Image Descriptor
                    {
                        $info['frame_count'] += 1;
                        $data .= fread( $fd, 9 );
                        $localColorTableSize = 0;
                        $localColorCount = 0;
                        $idFields = ord( $data[9] );
                        if ( $idFields >> 7 ) // Local Color Table
                        {
                            $localColorCount = ( 1 << ( ( $idFields & 0x07 ) + 1) );
                            $localColorTableSize = $localColorCount * 3;
                        }
                        if ( $localColorCount > $globalColorCount )
                            $info['color_count'] = $localColorCount;

                        if ( $localColorTableSize )
                        {
                            fseek( $fd, $localColorTableSize, SEEK_CUR );
                            $offset += $localColorTableSize;
                        }
                        if ( $printInfo )
                            print( ">> Local color table size=$localColorTableSize\n" );

                        $lzwCodeSize = fread( $fd, 1 ); // LZW Minimum Code Size
                        $offset += 1;

                        $dataBlockDone = false;
                        while ( !$dataBlockDone )
                        {
                            $data = fread( $fd, 1 );
                            $offset += 1;
                            $blockBytes = ord( $data[0] );
                            if ( $printInfo )
                                print( ">> Block size=$blockBytes\n" );
                            if ( $blockBytes )
                            {
                                fseek( $fd, $blockBytes, SEEK_CUR );
                                $offset += $blockBytes;
                            }
                            else
                            {
                                if ( $printInfo )
                                    print( ">> GIF image blocks terminated by 0 image block\n" );
                                $dataBlockDone = true;
                            }
                        }
                    }
                    else if ( $blockType == 0x3b ) // Trailer, end of stream
                    {
                        if ( $printInfo )
                            print( "GIF stream terminated by Trailer block\n" );
                        $done = true;
                    }
                    if ( feof( $fd ) )
                        $done = true;
                }
            }
            else
            {
                if ( $printInfo )
                    print( "Not GIF\n" );
                eZDebug::writeError( "The image file $filename is not a GIF file, cannot analyze it",
                                     'eZGIFImageAnalyzer::process' );
            }
            fclose( $fd );
            $info['is_animated'] = $info['frame_count'] > 1;
            return $info;
        }
        return false;
    }

}

?>
