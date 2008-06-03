<?php
//
// Definition of eZImageFont class
//
// Created on: <03-Oct-2002 15:05:09 amos>
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

/*! \file ezimagefont.php
*/

/*!
  \class eZImageFont ezimagefont.php
  \ingroup eZImageObject
  \brief Specifies a font used for drawing text

  Font attributes are encapsulated for use with the eZImageInterface::drawText function.
  The class stores the family, pointsize and path. Alternatively an x and y adjustment may
  be specified incase the font rendering is wrong.

  Typical usage:
  \code
  if ( eZImageFont::exists( 'arial', 'design/standard/fonts' ) )
    $font = new eZImageFont( 'arial', 30, 'design/standard/fonts' );
  \enccode

  All attributes can be modified later on with setFamily, setPath, setPointSize, setXAdjustment and setYAdjustment.
*/

class eZImageFont
{
    /*!
     Initializes the object with a family, point size and path.
     X and y adjustment may also be specified.
    */
    function eZImageFont( $family, $size, $path,
                          $xAdjustment = 0, $yAdjustment = 0 )
    {
        $this->FontFamily = $family;
        $this->FontPath = $path;
        $this->PointSize = $size;

        $this->XAdjustment = $xAdjustment;
        $this->YAdjustment = $yAdjustment;

        $this->initialize();
    }

    /*!
     \return the font family, eg. arial, times
    */
    function family()
    {
        return $this->FontFamily;
    }

    /*!
     \return the path to font files, it may be a string or an array of strings.
    */
    function path()
    {
        return $this->FontPath;
    }

    /*!
     \return the font file if it has been initialized.
     \sa realFile, fontFile, initialize.
    */
    function file()
    {
        return $this->FontFile;
    }

    /*!
     Similar to file but returns the absolute path to the font file.
     This is required for GD font handling.
    */
    function realFile()
    {
        return realpath( "." ) . "/" . $this->FontFile;
    }

    /*!
     \return the point size of the font.
    */
    function pointSize()
    {
        return $this->PointSize;
    }

    /*!
     \return the number of pixels in the x direction to adjust the font output.
     \sa yAdjustment
    */
    function xAdjustment()
    {
        return $this->XAdjustment;
    }

    /*!
     \return the number of pixels in the y direction to adjust the font output.
     \sa xAdjustment
    */
    function yAdjustment()
    {
        return $this->YAdjustment;
    }

    /*!
     Sets the font family to \a $family.
     \note Changing the font family will reinitialize the font.
    */
    function setFamily( $family )
    {
        $this->FontFamily = $family;
        $this->initialize();
    }

    /*!
     Sets the font path which is used when searching for fonts, the path can either be
     a string or an array of strings.
     \note Changing the font path will reinitialize the font.
    */
    function setPath( $path )
    {
        $this->FontPath = $path;
        $this->initialize();
    }

    /*!
     Sets the point size of the font to \a $size.
    */
    function setPointSize( $size )
    {
        $this->PointSize = $size;
    }

    /*!
     Sets the number of pixels in the x direction to adjust the font output to \a $adjustment.
     \sa setYAdjustment
    */
    function setXAdjustment( $adjustment )
    {
        $this->XAdjustment = $adjustment;
    }

    /*!
     Sets the number of pixels in the y direction to adjust the font output to \a $adjustment.
     \sa setXAdjustment
    */
    function setYAdjustment( $adjustment )
    {
        $this->YAdjustment = $adjustment;
    }

    /*!
     Sets the number of pixels to adjust the font output to \a $xAdjustment and \a $yAdjustment.
     \sa setXAdjustment, setYAdjustment
    */
    function setAdjustment( $xAdjustment, $yAdjustment )
    {
        $this->XAdjustment = $xAdjustment;
        $this->YAdjustment = $yAdjustment;
    }

    /*!
     \static
     \return true if the font family \a $fontFamily exists in the path \a $fontPath.
     The path can be specified as a string or an array of strings.
    */
    static function exists( $fontFamily, $fontPath )
    {
        return eZImageFont::fontFile( $fontFamily, $fontPath ) != false;
    }

    /*!
     \private
     Initializes the font file attribute by searching for the font.
    */
    function initialize()
    {
        $this->FontFile = eZImageFont::fontFile( $this->FontFamily, $this->FontPath );
    }

    /*!
     \static
     \return the file path for the font if it is found or \c false if no font could be used.
     The font must be named equal to the \a $fontFamily parameter
     with the .ttf suffix, eg. arial.ttf.
     \param fontPath The path to the fonts or an array of paths.
    */
    static function fontFile( $fontFamily, $fontPath )
    {
        if ( preg_match( '/\.ttf$/i', $fontFamily ) )
            $family_file = $fontFamily;
        else
            $family_file = $fontFamily . '.ttf';
        if ( $fontPath != null )
        {
            if ( !is_array( $fontPath ) )
                $fontPath = array( $fontPath );
            foreach ( $fontPath as $singleFontPath )
            {
                $font = $singleFontPath . "/$family_file";
                if ( !file_exists( $font ) )
                    $font = false;
                else
                    return $font;
            }
        }
        else
            $font = $fontFamily;
        return $font;
    }

    /// \privatesection
    /// The current font family
    public $FontFamily;
    /// The path or path array to the fonts
    public $FontPath;
    /// The path to the font file one was found
    public $FontFile;
    /// The size of the font in points.
    public $PointSize;
    /// Adjustment in the x direction
    public $XAdjustment;
    /// Adjustment in the y direction
    public $YAdjustment;
}

?>
