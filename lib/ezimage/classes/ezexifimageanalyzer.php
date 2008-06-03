<?php
//
// Definition of eZEXIFImageAnalyzer class
//
// Created on: <03-Nov-2003 15:19:16 amos>
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

/*! \file ezexifimageanalyzer.php
*/

/*!
  \class eZEXIFImageAnalyzer ezexifimageanalyzer.php
  \ingroup eZImageAnalyzer
  \brief Analyzes image formats which can contain EXIF information.

*/

class eZEXIFImageAnalyzer
{
    /*!
     Constructor
    */
    function eZEXIFImageAnalyzer()
    {
    }

    /*!
     \reimp
     Checks the file for EXIF data and returns the information.
    */
    function process( $mimeData, $parameters = array() )
    {
        $printInfo = false;
        if ( isset( $parameters['print_info'] ) )
            $printInfo = $parameters['print_info'];

        $filename = $mimeData['url'];
        if ( file_exists( $filename ) )
        {
            if ( function_exists( 'exif_read_data' ) )
            {
                $exifData = exif_read_data( $filename, "COMPUTED,IFD0,COMMENT,EXIF", true );
                if ( $exifData )
                {
                    $info = array();
                    if ( isset( $exifData['COMPUTED'] ) )
                    {
                        foreach ( $exifData['COMPUTED'] as $key => $item )
                        {
                            if ( strtolower( $key ) == 'html' )
                                continue;
                            $info[$key] = $exifData['COMPUTED'][$key];
                        }
                    }
                    if ( isset( $exifData['IFD0'] ) )
                    {
                        $info['ifd0'] = $exifData['IFD0'];
                    }
                    if ( isset( $exifData['EXIF'] ) )
                    {
                        $info['exif'] = $exifData['EXIF'];
                    }
                    return $info;
                }
            }
        }
        return false;
    }

}

?>
