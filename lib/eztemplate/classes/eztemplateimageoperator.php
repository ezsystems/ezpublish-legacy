<?php
//
// Definition of eZTemplateImageOperator class
//
// Created on: <05-Mar-2002 12:52:10 amos>
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

/*!
  \class eZTemplateImageOperator eztemplateimageoperator.php
  \ingroup eZTemplateOperators
  \brief Text to image conversion using operator "texttoimage"

  This operator allows a piece of text be converted to an image file representing
  the text. The output image is written in PNG format.
  Use setFontDir() and setCacheDir() to change where the font is located and where
  the cache file should be put.
  If fontDir() is an empty string the font will be looked for in the system.

  \note This code is far from finished, in the future it will return an image type
        which can be further modified with other image operators.
*/

include_once( "lib/eztemplate/classes/eztemplate.php" );

class eZTemplateImageOperator
{
    /*!
     Initializes the image operator with the operator name $name.
    */
    function eZTemplateImageOperator( $name = "texttoimage" )
    {
        $this->Operators = array( $name );
        $this->FontDir = "";
        $this->CacheDir = "";
        $this->HTMLDir = "";
        $this->Family = "arial";
        $this->Colors = array( "bgcolor" => array( 255, 255, 255 ),
                               "textcolor" => array( 0, 0, 0 ) );
        $this->PointSize = 12;
        $this->Angle = 0;
        $this->XAdjust = 0;
        $this->YAdjust = 0;
        $this->UseCache = true;
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( "family" => array( "type" => "string",
                                         "required" => false,
                                         "default" => $this->Family ),
                      "pointsize" => array( "type" => "integer",
                                            "required" => false,
                                            "default" => $this->PointSize ),
                      "angle" => array( "type" => "integer",
                                        "required" => false,
                                        "default" => $this->Angle ),
                      "bgcolor" => array( "type" => "mixed",
                                          "required" => false,
                                          "default" => null ),
                      "textcolor" => array( "type" => "mixed",
                                            "required" => false,
                                            "default" => null ),
                      "x" => array( "type" => "integer",
                                    "required" => false,
                                    "default" => $this->XAdjust ),
                      "y" => array( "type" => "integer",
                                    "required" => false,
                                    "default" => $this->YAdjust ),
                      "usecache" => array( "type" => "boolean",
                                           "required" => false,
                                           "default" => $this->UseCache )
                      );
    }

    /*!
     \return the directory where fonts are located. If it is empty
     the font is looked for in the system font dirs.
     \sa setFontDir
    */
    function fontDir()
    {
        return $this->FontDir;
    }

    /*!
     \return the directory where images are created.
     \sa setCacheDir
    */
    function cacheDir()
    {
        return $this->CacheDir;
    }

    /*!
     \return the directory where image is accessible from HTML code.
     \sa setHTMLdir
    */
    function htmlDir()
    {
        return $this->HTMLDir;
    }

    /*!
     \return the family name of the default font.
     \sa setFamily
    */
    function family()
    {
        return $this->Family;
    }

    /*!
     \return the pointsize of the default font.
     \sa setPointsize
    */
    function pointSize()
    {
        return $this->PointSize;
    }

    /*!
     \return the angle at which the font is rendered.
    */
    function angle()
    {
        return $this->Angle;
    }

    /*!
     \return the number of pixels the font is adjusted in the X direction.
     \sa setXAdjustment, yAdjustment
    */
    function xAdjustment()
    {
        return $this->XAdjust;
    }

    /*!
     \return the number of pixels the font is adjusted in the Y direction.
     \sa setYAdjustment, xAdjustment
    */
    function yAdjustment()
    {
        return $this->YAdjust;
    }

    /*!
     \return true if image cache should be reused if the image text etc.. hasn't changed.
    */
    function useCache()
    {
        return $this->UseCache;
    }

    /*!
     \return the array of colors in use
    */
    function colors()
    {
        return $this->Colors;
    }

    /*!
     Takes a mixed mode color representation and decodes it to a an array of three elements
     which represents the R, G and B color elements.
    */
    function decodeColor( /*! The mixed color mode */ $col )
    {
        $decode = null;
        if ( is_array( $col ) )
            $decode = $col;
        else if ( is_string( $col ) )
        {
            preg_match( "/^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/", $col, $regs );
            $decode = array( hexdec( $regs[1] ), hexdec( $regs[2] ), hexdec( $regs[3] ) );
        }
        return $decode;
    }

    /*!
     Returns the decodecd color for colorname $colname
    */
    function color( $colname )
    {
        if ( isset( $this->Colors[$colname] ) )
        {
            $col = $this->Colors[$colname];
            return $this->decodeColor( $col );
        }
        return null;
    }

    /*!
     Sets the font directory, see fontDir() for more information.
     \sa fontDir
    */
    function setFontDir( $dir )
    {
        $this->FontDir = $dir;
    }

    /*!
     Sets the directory where images are created.
     \sa cacheDir
    */
    function setCacheDir( $dir )
    {
        $this->CacheDir = $dir;
    }

    /*!
     Sets the directory which the HTML code uses to acces the image.
     \sa htmlDir
    */
    function setHTMLDir( $dir )
    {
        $this->HTMLDir = $dir;
    }

    /*!
     Sets the font family for the default font.
    */
    function setFamily( $fam )
    {
        $this->Family = $fam;
    }

    /*!
     Sets the pointsize for the default font.
    */
    function setPointSize( $size )
    {
        $this->PointSize = $size;
    }

    /*!
     Sets the angle for the default font.
    */
    function setAngle( $ang )
    {
        $this->Angle = $ang;
    }

    /*!
     Adjustment in the x axis.
     \sa xAdjustment, yAdjustment, setYAdjustment
    */
    function setXAdjustment( $x )
    {
        $this->XAdjust = $x;
    }

    /*!
     Adjustment in the y axis.
     \sa xAdjustment, yAdjustment, setXAdjustment
    */
    function setYAdjustment( $y )
    {
        $this->YAdjust = $y;
    }

    /*!
     Sets whether to reuse cache files or not.
     \sa useCache
    */
    function setUseCache( $use )
    {
        $this->UseCache = $use;
    }

    /*!
     Sets all the colors.
     \sa setColor, color
    */
    function setColors( $cols )
    {
        $this->Colors = $cols;
    }

    /*!
     Sets the colorname $colname to color value $colval.
     The colval is a mixed color mode so different values can be input.
     \sa setColors, color
    */
    function setColor( $colname, $colval )
    {
        $this->Colors[$colname] = $colval;
    }

    /*!
     Performs image conversion using Image GD and returns the html
     text for the image.
     \todo Change the output to not use HTML but rather the path to the image.
    */
    function modify( &$element, &$tpl, &$op_name, &$op_params, &$namespace, &$current_nspace, &$value, &$named_params )
    {
        $family = $named_params["family"];
        if ( preg_match( "/\.ttf$/", $family ) )
            $family_file = $family;
        else
            $family_file = "$family.ttf";
        if ( $this->FontDir != "" )
        {
            $font = $this->FontDir . "/$family_file";
            if ( !file_exists( $font ) )
                $font = $family;
        }
        else
            $font = $family;
        $size = $named_params["pointsize"];
        $angle = $named_params["angle"];
        $xadj = $named_params["x"];
        $yadj = $named_params["y"];
        $usecache = $named_params["usecache"];

        $bgcol = $this->decodeColor( $named_params["bgcolor"] );
        $textcol = $this->decodeColor( $named_params["textcolor"] );
        if ( $bgcol === null )
            $bgcol = $this->color( "bgcolor" );
        if ( !is_array( $bgcol ) or
             count( $bgcol ) < 3 )
            $bgcol = array( 255, 255, 255 );
        if ( $textcol === null )
            $textcol = $this->color( "textcolor" );
        if ( !is_array( $textcol ) or
             count( $textcol ) < 3 )
            $textcol = array( 0, 0, 0 );

        if ( is_string( $usecache ) )
            $cnt = $usecache;
        else
            $cnt = md5( $value . $family . $size . $angle . $xadj . $yadj . implode( ",", $bgcol ) . implode( ",", $textcol ) );
        if ( $usecache )
            $file = "image-$cnt.png";
        else
            $file = "image-uncached-$cnt.png";
        $output = $this->CacheDir . "/$file";
        if ( is_string( $usecache ) or !$usecache or !file_exists( $output ) )
        {
            $bbox = @ImageTTFBBox( $size, $angle, $font, $value );
            if ( !$bbox )
            {
                $tpl->error( $op_name, "Could not open font \"$family\"" );
                return;
            }

            $width = $bbox[4] - $bbox[6];
            $height = $bbox[1] - $bbox[7];

            $im = ImageCreate( $width, $height );

            $bgcolor = ImageColorAllocate( $im, $bgcol[0], $bgcol[1], $bgcol[2] );
            $textcolor = ImageColorAllocate ($im, $textcol[0], $textcol[1], $textcol[2] );

            ImageTTFText ( $im, $size, $angle, $bbox[6] + $xadj, -$bbox[7] + $yadj,
                           $textcolor, $font, $value );
            ImagePNG( $im, $output );
            ImageDestroy( $im );
        }
        $value = "<img src=\"" . $this->HTMLDir . "/$file\" alt=\"$value\"/>";
    }

    /// The operator array
    var $Operators;
    /// the directory were fonts are found, default is ""
    var $FontDir;
    /// the directory were cache files are created, default is ""
    var $CacheDir;
    /// the directory were html code fines the images, default is ""
    var $HTMLDir;
    /// the default font family, default is "arial"
    var $Family;
    /// the default font point size, default is 12
    var $PointSize;
    /// the default font angle, default is 0
    var $Angle;
    /// the default font x adjustment, default is 0
    var $XAdjust;
    /// the default font y adjustment, default is 0
    var $YAdjust;
    /// whether to reuse cache files or not
    var $UseCache;
    /// the color array, default is bgcolor=white and textcolor=black
    var $Colors;
}

?>
