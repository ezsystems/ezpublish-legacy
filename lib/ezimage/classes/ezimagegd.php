<?php
//
// Definition of eZImageGD class
//
// Created on: <04-Mar-2002 18:26:17 amos>
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
  \class eZImageGD ezimagegd.php
  \ingroup eZImage
  \brief Image conversion delegate using ImageGD PHP extension

  This class uses the internal PHP functions for image handling
  for doing conversions. See http://www.php.net/manual/en/ref.image.php
  for more information.

*/

include_once( "lib/ezutils/classes/ezdebug.php" );

define( "EZ_IMAGE_GD_KEEP_SUFFIX", 1 );
define( "EZ_IMAGE_GD_REPLACE_SUFFIX", 2 );
define( "EZ_IMAGE_GD_APPEND_SUFFIX", 3 );
define( "EZ_IMAGE_GD_PREPEND_SUFFIX_TAG", 4 );
define( "EZ_IMAGE_GD_NO_SUFFIX", 5 );

class eZImageGD
{
    /*!
     Sets up the conversion rules, maps a mimetype to a specific image function.
     GIF and WBMP conversion is only available if it is compiled in.
    */
    function eZImageGD( $from_type = EZ_IMAGE_KEEP_SUFFIX, $to_type = EZ_IMAGE_REPLACE_SUFFIX )
    {
        $this->InputMIMETypes = array( "image/png" => "imagecreatefrompng",
                                       "image/jpeg" => "imagecreatefromjpeg" );
        $this->OutputMIMETypes = array( "image/png" => "imagepng",
                                        "image/jpeg" => "imagejpeg" );
        if ( function_exists( "imagecreatefromgif" ) and function_exists( "imagegif" ) and
             ImageTypes() & IMG_GIF )
        {
            $this->InputMIMETypes["image/gif"] = "imagecreatefromgif";
            $this->OutputMIMETypes["image/gif"] = "imagegif";
        }
        if ( function_exists( "imagecreatefromwbmp" ) and function_exists( "imagewbmp" ) and
             ImageTypes() & IMG_WBMP )
        {
            $this->InputMIMETypes["image/vnd.wap.wbmp"] = "imagecreatefromwbmp";
            $this->OutputMIMETypes["image/vnd.wap.wbmp"] = "imagewbmp";
        }
        $this->InputMIMETypes["image/xpm"] = "imagecreatefromxpm";
        $this->InputMIMETypes["image/xbm"] = "imagecreatefromxbm";
        $this->FromType = $from_type;
        $this->ToType = $to_type;
    }

    /*!
     \static
    */
    function isAvailable()
    {
        return function_exists( "imagegd" );
    }

    /*!
     Converts the filename $from according to $type. See class documentation
     or different types you can use.
    */
    function &convertFileName( $from, $type, $use_dir = true )
    {
        switch ( $type )
        {
            case EZ_IMAGE_KEEP_SUFFIX:
            {
                $str = $from["original-filename"];
                if ( $use_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            case EZ_IMAGE_APPEND_SUFFIX:
            {
                $str = $from["original-filename"] . "." . $from["suffix"];
                if ( $use_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            case EZ_IMAGE_PREPEND_SUFFIX_TAG:
            {
                $str = "";
                if ( $use_dir )
                    $str = $from["dir"] . "/";
                $str = $from["suffix"] . ":" . $str . $from["original-filename"];
            } break;

            case EZ_IMAGE_NO_SUFFIX:
            {
                $str = $from["basename"];
                if ( $use_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            default:
            case EZ_IMAGE_REPLACE_SUFFIX:
            {
                $str = $from["basename"] . "." . $from["suffix"];
                if ( $use_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;
        }
        return $str;
    }

    /*!
     Converts the image and scales it if needed.
     Returns true if the image could be converted.
    */
    function convert( &$from, &$to, &$to_dir, &$to_file, &$filters, $scale = false )
    {
        if ( !isset( $this->InputMIMETypes[$from["mime-type"]] ) )
        {
            eZDebug::writeWarning( "eZImageGD: Input Mime-type " . $from["mime-type"] . " not supported" );
            return false;
        }
        if ( !isset( $this->OutputMIMETypes[$to["mime-type"]] ) )
        {
            eZDebug::writeWarning( "eZImageGD: Output Mime-type " . $to["mime-type"] . " not supported" );
            return false;
        }

        $src = "";
        $dst = "";
        $to_dst = $to;
        $to_dst["suffix"] = $to["suffix"];
        $src .= $this->convertFileName( $from, $this->FromType );
        $dst .= $this->convertFileName( $to_dst, $this->ToType );
        $create_func = $this->InputMIMETypes[$from["mime-type"]];

        // fix duplicate slash
        $src = str_replace( "//", "/", $src );
        $dst = str_replace( "//", "/", $dst );


        if ( !function_exists( $create_func ) )
        {
            eZDebug::writeError( "GD function: $create_func does not exist, check PHP compile settings", "eZImageGD" );
            return false;
        }

        $im = $create_func( $src );

        if ( $im )
        {
            if ( $scale !== false )
            {
                $srcx = $srcy = 0;
                $srcw = ImageSX( $im );
                $srch = ImageSY( $im );
                $dstx = $dsty = 0;
                $dstw = $scale["width"];
                $dsth = $scale["height"];

                if ( !function_exists( "ImageCreateTrueColor" ) )
                {
                    eZDebug::writeError( "Cannot scale images. GD function: ImageCreateTrueColor does not exist, check PHP compile settings", "eZImageGD" );
                }
                else
                {
                    // Scale the image and keep aspect ratio
                    $heightScale = $srch / $scale["height"];
                    $widthtScale = $srcw / $scale["width"];

                    $scale = $heightScale;
                    if ( $heightScale != $widthtScale )
                        $scale = max( $heightScale, $widthtScale );

                    $dstw = (int) ( $srcw / $scale );
                    $dsth = (int) ( $srch / $scale );

                    $im2 = ImageCreateTrueColor( $dstw, $dsth );

                    ImageCopyResampled( $im2, $im,
                                        $dstx, $dsty, $srcx, $srcy,
                                        $dstw, $dsth, $srcw, $srch );
                    unset( $im );
                    $im =& $im2;
                }
            }
            $store_func = $this->OutputMIMETypes[$to["mime-type"]];
            $store_func( $im, $dst );
            $to_dir = $to["dir"];
            $to_file = $to["basename"] . "." . $to["suffix"];

            $to_dir = str_replace( "//", "/", $to_dir );

            // prepend suffix
            $to_file = $to['suffix'] . ':' . $to_file;

            return true;
        }
        else
            return false;
    }

    /*!
     Calls convert() with the $filters and $scale param.
    */
    function scale( &$from, &$to, &$to_dir, &$to_file, &$filters, $scale )
    {
        $this->convert( $from, $to, $to_dir, $to_file, $filters, $scale );
    }

    /// Array of allowed input types mapped to the corresponding function.
    var $InputMIMETypes;
    /// Array of allowed output types mapped to the corresponding function.
    var $OutputMIMETypes;
    /// The filename operation to run on the source file.
    var $FromType;
    /// The filename operation to run on the destination file.
    var $ToType;
}

?>
