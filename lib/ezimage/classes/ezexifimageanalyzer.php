<?php
/**
 * File containing the eZEXIFImageAnalyzer class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZEXIFImageAnalyzer ezexifimageanalyzer.php
  \ingroup eZImageAnalyzer
  \brief Analyzes image formats which can contain EXIF information.

*/

class eZEXIFImageAnalyzer
{
    /*!
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
                $exifData = @exif_read_data( $filename, "COMPUTED,IFD0,COMMENT,EXIF", true );
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
