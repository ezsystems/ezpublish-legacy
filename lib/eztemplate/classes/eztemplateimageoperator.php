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
include_once( "lib/eztemplate/classes/eztemplateimagelayer.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateImageOperator
{
    /*!
     Initializes the image operator with the operator name $name.
    */
    function eZTemplateImageOperator( $texttoimageName = "texttoimage",
                                      $imageName = "image",
                                      $imagefileName = "imagefile" )
    {
        $this->Operators = array( $texttoimageName, $imageName, $imagefileName );

        include_once( "lib/ezutils/classes/ezsys.php" );
        $ini =& eZINI::instance( 'texttoimage.ini' );
        $this->FontDir = realpath( "." ) . "/" . $ini->variable( "PathSettings", "FontDir" );
        $this->CacheDir = realpath( "." ) . "/" . $ini->variable( "PathSettings", "CacheDir" );
        $this->HTMLDir = eZSys::wwwDir() . $ini->variable( "PathSettings", "HtmlDir" );

        $this->DefaultClass = 'default';
        $this->Family = "arial";
        $this->Colors = array( "bgcolor" => array( 255, 255, 255 ),
                               "textcolor" => array( 0, 0, 0 ) );
        $this->PointSize = 12;
        $this->Angle = 0;
        $this->XAdjust = 0;
        $this->YAdjust = 0;
        $this->WAdjust = 0;
        $this->HAdjust = 0;
        $this->UseCache = true;

        $this->ImageGDSupported = ( function_exists( "ImageTTFBBox" ) and
                                    function_exists( "ImageCreate" ) and
                                    function_exists( "ImageColorAllocate" ) and
                                    function_exists( "ImageColorAllocate" ) and
                                    function_exists( "ImageTTFText" ) and
                                    function_exists( "ImagePNG" ) and
                                    function_exists( "ImageDestroy" ) );
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'texttoimage' => array( "class" => array( 'type' => 'string',
                                                                'required' => false,
                                                                'default' => $this->DefaultClass ),
                                              "family" => array( "type" => "string",
                                                                 "required" => false,
                                                                 "default" => null ),
                                              "pointsize" => array( "type" => "integer",
                                                                    "required" => false,
                                                                    "default" => null ),
                                              "angle" => array( "type" => "integer",
                                                                "required" => false,
                                                                "default" => null ),
                                              "bgcolor" => array( "type" => "mixed",
                                                                  "required" => false,
                                                                  "default" => null ),
                                              "textcolor" => array( "type" => "mixed",
                                                                    "required" => false,
                                                                    "default" => null ),
                                              "x" => array( "type" => "integer",
                                                            "required" => false,
                                                            "default" => null ),
                                              "y" => array( "type" => "integer",
                                                            "required" => false,
                                                            "default" => null ),
                                              "w" => array( "type" => "integer",
                                                            "required" => false,
                                                            "default" => null ),
                                              "h" => array( "type" => "integer",
                                                            "required" => false,
                                                            "default" => null ),
                                              "usecache" => array( "type" => "boolean",
                                                                   "required" => false,
                                                                   "default" => null )
                                              ),
                      'imagefile' => array( 'filename' => array( 'type' => 'string',
                                                                 'required' => true ) ) );
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
     \return the number of pixels the width of the image is adjusted.
     \sa setWidthAdjustment, heightAdjustment
    */
    function widthAdjustment()
    {
        return $this->WAdjust;
    }

    /*!
     \return the number of pixels the height of the image is adjusted.
     \sa setHeightAdjustment, widthAdjustment
    */
    function heightAdjustment()
    {
        return $this->HAdjust;
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
            if ( preg_match( "/^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/", $col, $regs ) )
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
     Adjustment for width.
     \sa widthAdjustment, heightAdjustment, setHeightAdjustment
    */
    function setWidthAdjustment( $w )
    {
        $this->WAdjust = $w;
    }

    /*!
     Adjustment for height.
     \sa widthAdjustment, heightAdjustment, setWidthAdjustment
    */
    function setHeightAdjustment( $h )
    {
        $this->HAdjust = $h;
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
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$inputValue, &$namedParameters )
    {
        if ( !$this->ImageGDSupported )
            return;

        if ( $operatorName == 'texttoimage' )
        {
            $class = $namedParameters['class'];

            $family = $this->Family;
            $size = $this->PointSize;
            $angle = $this->Angle;
            $xadj = $this->XAdjust;
            $yadj = $this->YAdjust;
            $wadj = $this->WAdjust;
            $hadj = $this->HAdjust;
            $usecache = $this->UseCache;
            $bgcol = $this->color( "bgcolor" );
            $textcol = $this->color( "textcolor" );

            $ini =& eZINI::instance( 'texttoimage.ini' );
            $family =& $ini->variable( 'DefaultSettings', 'Family' );
            $size =& $ini->variable( 'DefaultSettings', 'PointSize' );
            $angle =& $ini->variable( 'DefaultSettings', 'Angle' );
            $xadj =& $ini->variable( 'DefaultSettings', 'XAdjustment' );
            $yadj =& $ini->variable( 'DefaultSettings', 'YAdjustment' );
            $wadj =& $ini->variable( 'DefaultSettings', 'WidthAdjustment' );
            $hadj =& $ini->variable( 'DefaultSettings', 'HeightAdjustment' );
            $bgcol =& $this->decodeColor( $ini->variable( 'DefaultSettings', 'BackgroundColor' ) );
            $textcol =& $this->decodeColor( $ini->variable( 'DefaultSettings', 'TextColor' ) );

            if ( $ini->hasVariable( $class, 'Family' ) )
                $family =& $ini->variable( $class, 'Family' );
            if ( $ini->hasVariable( $class, 'PointSize' ) )
                $size =& $ini->variable( $class, 'PointSize' );
            if ( $ini->hasVariable( $class, 'Angle' ) )
                $angle =& $ini->variable( $class, 'Angle' );
            if ( $ini->hasVariable( $class, 'XAdjustment' ) )
                $xadj =& $ini->variable( $class, 'XAdjustment' );
            if ( $ini->hasVariable( $class, 'YAdjustment' ) )
                $yadj =& $ini->variable( $class, 'YAdjustment' );
            if ( $ini->hasVariable( $class, 'WidthAdjustment' ) )
                $wadj =& $ini->variable( $class, 'WidthAdjustment' );
            if ( $ini->hasVariable( $class, 'HeightAdjustment' ) )
                $hadj =& $ini->variable( $class, 'HeightAdjustment' );
            if ( $ini->hasVariable( $class, 'BackgroundColor' ) )
                $bgcol =& $this->decodeColor( $ini->variable( $class, 'BackgroundColor' ) );
            if ( $ini->hasVariable( $class, 'TextColor' ) )
                $textcol =& $this->decodeColor( $ini->variable( $class, 'TextColor' ) );

            if ( $namedParameters['family'] !== null )
                $family = $namedParameters["family"];
            if ( $namedParameters["pointsize"] !== null )
                $size = $namedParameters["pointsize"];
            if ( $namedParameters["angle"] !== null )
                $angle = $namedParameters["angle"];
            if ( $namedParameters["x"] !== null )
                $xadj = $namedParameters["x"];
            if ( $namedParameters["y"] !== null )
                $yadj = $namedParameters["y"];
            if ( $namedParameters["w"] !== null )
                $wadj = $namedParameters["w"];
            if ( $namedParameters["h"] !== null )
                $hadj = $namedParameters["h"];
            if ( $namedParameters["usecache"] !== null )
                $usecache = $namedParameters["usecache"];
            if ( $namedParameters["bgcolor"] !== null )
                $bgcol = $this->decodeColor( $namedParameters["bgcolor"] );
            if ( $namedParameters["textcolor"] !== null )
                $textcol = $this->decodeColor( $namedParameters["textcolor"] );

            $font =& new eZTemplateImageFont( $family, $size, $this->FontDir );

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
                $cnt = md5( $inputValue . $family . $size . $angle . $xadj . $yadj . $wadj . $hadj . implode( ",", $bgcol ) . implode( ",", $textcol ) );
            if ( $usecache )
                $file = "image-$cnt.png";
            else
                $file = "image-uncached-$cnt.png";
            $output = $this->CacheDir . "/$file";
            if ( is_string( $usecache ) or !$usecache or !file_exists( $output ) )
            {
                $layer =& eZTemplateImageLayer::createForText( $inputValue, $font,
                                                               $wadj, $hadj, $angle );
                if ( !$layer )
                {
                    $tpl->error( $operatorName, "Could not open font \"$family\", no image created" );
                    return;
                }
                $layer->allocateColor( 'bgcol', $bgcol[0], $bgcol[1], $bgcol[2] );
                $layer->allocateColor( 'textcol', $textcol[0], $textcol[1], $textcol[2] );
                $layer->setTextColor( 'textcol' );

                $bbox = $layer->textBoundingBox();

                $layer->drawText( $inputValue, $bbox[6] + $xadj, -$bbox[7] + $yadj, $angle );

                $layer->store( $file, $this->CacheDir, 'png' );
                $layer->destroy();
            }
            else
                $layer =& eZTemplateImageLayer::createForFile( $file, $this->CacheDir, 'png' );
            $layer->setAlternativeText( $inputValue );
            $layer->setHTMLPath( $this->HTMLDir );
            $inputValue = $layer;
        }
        else if ( $operatorName == 'image' )
        {
            $image = new eZTemplateImageObject();
            $md5Input = 'image' . count( $operatorParameters );
            foreach ( array_keys( $operatorParameters ) as $operatorParameterKey )
            {
                $operatorParameter =& $tpl->elementValue( $operatorParameters[$operatorParameterKey], $rootNamespace, $currentNamespace );
                if ( get_class( $operatorParameter ) == 'eztemplateimagelayer' )
                {
                    $md5Input .= $operatorParameter->attribute( 'imagepath' ) . "\n";
                    $image->addLayer( $operatorParameter );
                }
            }
            if ( $image->flatten() )
            {
                $cnt = md5( $md5Input );
                $file = "imageobject-$cnt.png";
                $image->store( $file, $this->CacheDir, 'png' );
                $image->setHTMLPath( $this->HTMLDir );
                $inputValue = $image;
            }
        }
        else if ( $operatorName == 'imagefile' )
        {
            $file =& $namedParameters['filename'];
            $layer =& eZTemplateImageLayer::createForFile( $file, $this->CacheDir, 'png' );
            $layer->setHTMLPath( $this->HTMLDir );
            $inputValue = $layer;
        }
    }

    /// \privatesection
    /// The operator array
    var $Operators;
    /// The default class to use for text to image conversion
    var $DefaultClass;
    /// the directory were fonts are found, default is ""
    var $FontDir;
    /// the directory were cache files are created, default is ""
    var $CacheDir;
    /// the directory were html code finds the images, default is ""
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
    /// Whether image GD is supported
    var $ImageGDSupported;
}

?>
