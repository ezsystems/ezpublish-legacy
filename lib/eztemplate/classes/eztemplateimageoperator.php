<?php
//
// Definition of eZTemplateImageOperator class
//
// Created on: <05-Mar-2002 12:52:10 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*!
  \class eZTemplateImageOperator eztemplateimageoperator.php
  \ingroup eZTemplateOperators
  \brief Text to image conversion using operator "texttoimage"

  This operator allows a piece of text be converted to an image file representing
  the text. The output image is written in PNG format.
  Use setFontDir() and setCacheDir() to change where the font is located and where
  the cache file should be put.
  If fontDir() is an empty string the font will be looked for in the system.
*/

//include_once( "lib/eztemplate/classes/eztemplate.php" );
//include_once( "lib/ezimage/classes/ezimageobject.php" );
//include_once( "lib/ezimage/classes/ezimagelayer.php" );
//include_once( "lib/ezimage/classes/ezimagetextlayer.php" );
//include_once( 'lib/ezimage/classes/ezimagefont.php' );
//include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateImageOperator
{
    /*!
     Initializes the image operator with the operator name $name.
    */
    function eZTemplateImageOperator( $texttoimageName = "texttoimage",
                                      $imageName = "image",
                                      $imagefileName = "imagefile" )
    {
        $this->Operators = array( $texttoimageName,
                                  $imageName,
                                  $imagefileName );

        //include_once( "lib/ezutils/classes/ezsys.php" );
        $ini = eZINI::instance( 'texttoimage.ini' );
        $fontDirs = $ini->variable( "PathSettings", "FontDir" );
        $this->FontDir = array();
        foreach ( $fontDirs as $fontDir )
        {
            $this->FontDir[] = $fontDir;
        }
        $this->CacheDir = $ini->variable( "PathSettings", "CacheDir" );
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
        if ( $ini->variable( "ImageSettings", "UseCache" ) == "disabled" )
            $this->UseCache = false;

        $functions = array( "ImageTTFBBox",
                            "ImageCreate",
                            "ImageColorAllocate",
                            "ImageColorAllocate",
                            "ImageTTFText",
                            "ImagePNG",
                            "ImageJPEG",
                            "ImageDestroy" );
        $this->MissingGDFunctions = array();
        foreach ( $functions as $function )
        {
            if ( !function_exists( $function ) )
                $this->MissingGDFunctions[] = $function;
        }
        $this->ImageGDSupported = count( $this->MissingGDFunctions ) == 0;
    }

    function operatorTemplateHints()
    {
        return array( 'texttoimage' => array( 'input' => true,
                                              'output' => true,
                                              'output-type' => array( 'objectproxy', 'keep' ),
                                              'parameters' => true ),
                      'image' => array( 'input' => false,
                                        'output' => true,
                                        'output-type' => array( 'objectproxy', 'keep' ),
                                        'parameters' => true ),
                      'imagefile' => array( 'input' => false,
                                            'output' => true,
                                            'output-type' => 'objectproxy',
                                            'parameters' => true ) );
    }

    /*!
     Performs image conversion using Image GD and returns the html
     text for the image.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$inputValue, $namedParameters,
                     $placement )
    {
        if ( !$this->ImageGDSupported )
        {
            eZDebug::writeError( "$operatorName cannot be used since the following ImageGD functions are missing: " . implode( ', ', $this->MissingGDFunctions ) );
            return;
        }

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

            $ini = eZINI::instance( 'texttoimage.ini' );
            $family = $ini->variable( 'DefaultSettings', 'Family' );
            $size = $ini->variable( 'DefaultSettings', 'PointSize' );
            $angle = $ini->variable( 'DefaultSettings', 'Angle' );
            $xadj = $ini->variable( 'DefaultSettings', 'XAdjustment' );
            $yadj = $ini->variable( 'DefaultSettings', 'YAdjustment' );
            $wadj = $ini->variable( 'DefaultSettings', 'WidthAdjustment' );
            $hadj = $ini->variable( 'DefaultSettings', 'HeightAdjustment' );
            $bgcol = $this->decodeColor( $ini->variable( 'DefaultSettings', 'BackgroundColor' ) );
            $textcol = $this->decodeColor( $ini->variable( 'DefaultSettings', 'TextColor' ) );

            $absoluteWidth = false;
            $absoluteHeight = false;

            if ( $ini->hasVariable( $class, 'Family' ) )
                $family = $ini->variable( $class, 'Family' );
            if ( $ini->hasVariable( $class, 'PointSize' ) )
                $size = $ini->variable( $class, 'PointSize' );
            if ( $ini->hasVariable( $class, 'Angle' ) )
                $angle = $ini->variable( $class, 'Angle' );
            if ( $ini->hasVariable( $class, 'XAdjustment' ) )
                $xadj = $ini->variable( $class, 'XAdjustment' );
            if ( $ini->hasVariable( $class, 'YAdjustment' ) )
                $yadj = $ini->variable( $class, 'YAdjustment' );
            if ( $ini->hasVariable( $class, 'WidthAdjustment' ) )
                $wadj = $ini->variable( $class, 'WidthAdjustment' );
            if ( $ini->hasVariable( $class, 'HeightAdjustment' ) )
                $hadj = $ini->variable( $class, 'HeightAdjustment' );
            if ( $ini->hasVariable( $class, 'BackgroundColor' ) )
                $bgcol = $this->decodeColor( $ini->variable( $class, 'BackgroundColor' ) );
            if ( $ini->hasVariable( $class, 'TextColor' ) )
                $textcol = $this->decodeColor( $ini->variable( $class, 'TextColor' ) );
            if ( $ini->hasVariable( $class, 'AbsoluteWidth' ) )
                $absoluteWidth = $ini->variable( $class, 'AbsoluteWidth' );
            if ( $ini->hasVariable( $class, 'AbsoluteHeight' ) )
                $absoluteHeight = $ini->variable( $class, 'AbsoluteHeight' );

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
            $storeImage = $namedParameters["storeimage"];

            $fontDir = false;
            foreach ( $this->FontDir as $fontPath )
            {
                if ( eZImageFont::exists( $family, $fontPath ) )
                {
                    $fontDir = $fontPath;
                    break;
                }
            }
            if ( !$fontDir )
                return;
            $font = new eZImageFont( $family, $size, $fontDir, $xadj, $yadj );

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

            $alternativeText = htmlspecialchars( $inputValue );
            if ( is_string( $usecache ) )
                $md5Text = $usecache;
            else
                $md5Text = md5( $inputValue . $family . $size . $angle . $xadj . $yadj . $wadj . $hadj . $absoluteWidth . $absoluteHeight . implode( ",", $bgcol ) . implode( ",", $textcol ) );
            if ( is_string( $usecache ) or !$usecache or
                 !$this->hasImage( $this->CacheDir, 'imagetext', $md5Text, $alternativeText, $this->StoreAs ) )
            {
                $layer = eZImageTextLayer::createForText( $inputValue, $font,
                                                           $wadj, $hadj, $angle,
                                                           $absoluteWidth, $absoluteHeight );
                if ( !$layer )
                {
                    $tpl->error( $operatorName, "Could not open font \"$family\", no image created", $placement );
                    return;
                }
                $layer->allocateColor( 'bgcol', $bgcol[0], $bgcol[1], $bgcol[2] );
                $layer->allocateColor( 'textcol', $textcol[0], $textcol[1], $textcol[2] );
                $layer->setTextColor( 'textcol' );

                if ( $storeImage )
                    $this->storeImage( $layer, $this->CacheDir, 'imagetext', $md5Text, $alternativeText, $this->StoreAs );
                $layer->destroy();
            }
            else
            {
                $layer = $this->loadImage( $this->CacheDir, 'imagetext', $md5Text, $alternativeText, $this->StoreAs );
            }
            $layer->setAlternativeText( $alternativeText );
            $inputValue = $layer;
        }

        else if ( $operatorName == 'image' )
        {
            $useCache = $this->UseCache;
            $image = new eZImageObject();
            $md5Input = "image\n";
            $alternativeText = '';
            $this->readImageParameters( $tpl, $image, $operatorParameters, $rootNamespace, $currentNamespace, $md5Input, $alternativeText,
                                        $placement );
            $image->setAlternativeText( $alternativeText );
            $md5Text = md5( $md5Input );
            if ( !$useCache or
                 !$this->hasImage( $this->CacheDir, 'imageobject', $md5Text, $alternativeText, $this->StoreAs ) )
            {
                $this->storeImage( $image, $this->CacheDir, 'imageobject', $md5Text, $alternativeText, $this->StoreAs );
                $image->destroy();
                $inputValue = $image;
            }
            else
            {
                $this->setLoadImage( $image, $this->CacheDir, 'imageobject', $md5Text, $alternativeText, $this->StoreAs );
                $image->load();
                $inputValue = $image;
            }
        }
        else if ( $operatorName == 'imagefile' )
        {
            $file =& $namedParameters['filename'];
            $options =& $namedParameters['options'];
            $dir = '';
            if ( preg_match( "#^(.+)/([^/]+)$#", $file, $matches ) )
            {
                $dir = $matches[1];
                $file = $matches[2];
            }

            $layer = eZImageLayer::createForFile( $file, $dir );
            $alternativeText = $file;
            if ( preg_match( "#(.+)\.([^.]+)$#", $file, $matches ) )
                $alternativeText = $matches[1];
            $layer->setAlternativeText( $alternativeText );
            $inputValue = $layer;
        }
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
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
                                                                   "default" => null ),
                                              "storeimage" => array( "type" => "boolean",
                                                                     "required" => false,
                                                                     "default" => true )
                                              ),
                      'imagefile' => array( 'filename' => array( 'type' => 'string',
                                                                 'required' => true ),
                                            'options' => array( 'type' => 'array',
                                                                'default' => array(),
                                                                'required' => false ) ) );
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

    function hasImage( $dirs, $base, $md5Text, $alternativeText, $imageType )
    {
        $name = preg_replace( array( "#[^a-zA-Z0-9_-]+#",
                                     "#__+#",
                                     "#_$#" ),
                              array( '_',
                                     '_',
                                     '' ),
                              $alternativeText );
        $file = "$name.$imageType";
        $splitMD5Path = eZDir::getPathFromFilename( $md5Text );
        $filePath = eZDir::path( array( $dirs, $base, $splitMD5Path, $md5Text, $file ) );
        return file_exists( $filePath );
    }

    function storeImage( $image, $dirs, $base, $md5Text, $alternativeText, $imageType )
    {
        $name = preg_replace( array( "#[^a-zA-Z0-9_-]+#",
                                     "#__+#",
                                     "#_$#" ),
                              array( '_',
                                     '_',
                                     '' ),
                              $alternativeText );
        $file = "$name.$imageType";
        $splitMD5Path = eZDir::getPathFromFilename( $md5Text );
        $dirPath = eZDir::path( array( $dirs, $base, $splitMD5Path, $md5Text ) );
        if ( !file_exists( $dirPath ) )
        {
            $ini = eZINI::instance();
            $mod = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
            eZDir::mkdir( $dirPath, octdec( $mod ), true );
        }
        $image->store( $file, $dirPath, $imageType );
    }

    function setLoadImage( $image, $dirs, $base, $md5Text, $alternativeText, $imageType )
    {
        $name = preg_replace( array( "#[^a-zA-Z0-9_-]+#",
                                     "#__+#",
                                     "#_$#" ),
                              array( '_',
                                     '_',
                                     '' ),
                              $alternativeText );
        $file = "$name.$imageType";
        $splitMD5Path = eZDir::getPathFromFilename( $md5Text );
        $dirPath = eZDir::path( array( $dirs, $base, $splitMD5Path, $md5Text ) );
        $filePath = eZDir::path( array( $dirPath, $file ) );
        if ( !file_exists( $filePath ) )
            return null;
        $image->setStoredFile( $file, $dirPath, $imageType );
    }

    function loadImage( $dirs, $base, $md5Text, $alternativeText, $imageType )
    {
        $name = preg_replace( array( "#[^a-zA-Z0-9_-]+#",
                                     "#__+#",
                                     "#_$#" ),
                              array( '_',
                                     '_',
                                     '' ),
                              $alternativeText );
        $file = "$name.$imageType";
        $splitMD5Path = eZDir::getPathFromFilename( $md5Text );
        $dirPath = eZDir::path( array( $dirs, $base, $splitMD5Path, $md5Text ) );
        $filePath = eZDir::path( array( $dirPath, $file ) );
        if ( !file_exists( $filePath ) )
            $layer = null;
        else
            $layer = eZImageLayer::createForFile( $file, $dirPath, $this->StoreAs );
        return $layer;
    }

    function readImageParameters( $tpl, $image, $operatorParameters, $rootNamespace, $currentNamespace, &$md5Input, &$alternativeText,
                                  $placement )
    {
        $imageAlternativeText = false;
        foreach ( array_keys( $operatorParameters ) as $operatorParameterKey )
        {
            $operatorParameter = $tpl->elementValue( $operatorParameters[$operatorParameterKey], $rootNamespace, $currentNamespace, $placement );
            unset( $imageLayer );
            $imageLayer = null;
            $imageParameters = array();
            if ( is_string( $operatorParameter ) )
            {
                $imageAlternativeText = $operatorParameter;
            }
            else if ( is_array( $operatorParameter ) )
            {
                $imageLayer = $operatorParameter[0];
                $imageParameterSource = $operatorParameter[1];
                if ( isset( $imageParameterSource['transparency'] ) )
                    $imageParameters['transparency'] = $imageParameterSource['transparency'];
                if ( isset( $imageParameterSource['halign'] ) or
                     isset( $imageParameterSource['valign'] ) or
                     isset( $imageParameterSource['x'] ) or
                     isset( $imageParameterSource['y'] ) )
                {
                    $xAlignment = eZImageObject::ALIGN_AXIS_NONE;
                    $yAlignment = eZImageObject::ALIGN_AXIS_NONE;
                    $xPlacement = eZImageObject::PLACE_CONSTANT;
                    $yPlacement = eZImageObject::PLACE_CONSTANT;
                    $xPos = 0;
                    $yPos = 0;
                    if ( isset( $imageParameterSource['halign'] ) )
                    {
                        $alignmentText = strtolower( $imageParameterSource['halign'] );
                        switch ( $alignmentText )
                        {
                            case 'left':
                            {
                                $xAlignment = eZImageObject::ALIGN_AXIS_START;
                            } break;
                            case 'right':
                            {
                                $xAlignment = eZImageObject::ALIGN_AXIS_STOP;
                            } break;
                            case 'center':
                            {
                                $xAlignment = eZImageObject::ALIGN_AXIS_CENTER;
                            } break;
                        }
                    }
                    if ( isset( $imageParameterSource['valign'] ) )
                    {
                        $alignmentText = strtolower( $imageParameterSource['valign'] );
                        switch ( $alignmentText )
                        {
                            case 'top':
                            {
                                $yAlignment = eZImageObject::ALIGN_AXIS_START;
                            } break;
                            case 'bottom':
                            {
                                $yAlignment = eZImageObject::ALIGN_AXIS_STOP;
                            } break;
                            case 'center':
                            {
                                $yAlignment = eZImageObject::ALIGN_AXIS_CENTER;
                            } break;
                        }
                    }
                    if ( isset( $imageParameterSource['x'] ) )
                    {
                        $xPos = $imageParameterSource['x'];
                        $xPlacement = eZImageObject::PLACE_CONSTANT;
                    }
                    if ( isset( $imageParameterSource['xrel'] ) )
                    {
                        $xPos = $imageParameterSource['xrel'];
                        $xPlacement = eZImageObject::PLACE_RELATIVE;
                    }
                    if ( isset( $imageParameterSource['y'] ) )
                    {
                        $yPos = $imageParameterSource['y'];
                        $yPlacement = eZImageObject::PLACE_CONSTANT;
                    }
                    if ( isset( $imageParameterSource['yrel'] ) )
                    {
                        $yPos = $imageParameterSource['yrel'];
                        $yPlacement = eZImageObject::PLACE_RELATIVE;
                    }
                    $x = array( 'alignment' => $xAlignment,
                                'placement' => $xPlacement,
                                'value' => $xPos );
                    $y = array( 'alignment' => $yAlignment,
                                'placement' => $yPlacement,
                                'value' => $yPos );
                    $imageParameters['x'] = $x;
                    $imageParameters['y'] = $y;
                }
            }
            else
                $imageLayer = $operatorParameter;
            if ( $imageLayer !== null and
                 $image->appendLayer( $imageLayer, $imageParameters ) )
            {
                $layerText = trim( $imageLayer->alternativeText() );
                if ( $layerText != '' )
                {
                    if ( $alternativeText != '' )
                        $alternativeText .= '-';
                    $alternativeText .= $layerText;
                }
                $md5Input .= $imageLayer->attribute( 'imagepath' );
                $xAlignment = eZImageObject::ALIGN_AXIS_NONE;
                $yAlignment = eZImageObject::ALIGN_AXIS_NONE;
                $xPlacement = eZImageObject::PLACE_CONSTANT;
                $yPlacement = eZImageObject::PLACE_CONSTANT;
                $xPos = 0;
                $yPos = 0;
                if ( isset( $imageParameters['x']['alignment'] ) )
                    $xAlignment = $imageParameters['x']['alignment'];
                if ( isset( $imageParameters['y']['alignment'] ) )
                    $yAlignment = $imageParameters['y']['alignment'];
                if ( isset( $imageParameters['x']['placement'] ) )
                    $xPlacement = $imageParameters['x']['placement'];
                if ( isset( $imageParameters['y']['placement'] ) )
                    $yPlacement = $imageParameters['y']['placement'];
                if ( isset( $imageParameters['x']['value'] ) )
                    $xPos = $imageParameters['x']['value'];
                if ( isset( $imageParameters['y']['value'] ) )
                    $yPos = $imageParameters['y']['value'];
                $md5Input .= "$xPos-$xAlignment-$xPlacement-$yPos-$yAlignment-$yPlacement\n";
            }
        }
        if ( $imageAlternativeText !== false )
            $alternativeText = $imageAlternativeText;
    }

    /// \privatesection
    /// The operator array
    public $Operators;
    /// The default class to use for text to image conversion
    public $DefaultClass;
    /// the directory were fonts are found, default is ""
    public $FontDir;
    /// the directory were cache files are created, default is ""
    public $CacheDir;
    /// the directory were html code finds the images, default is ""
    public $HTMLDir;
    /// the default font family, default is "arial"
    public $Family;
    /// the default font point size, default is 12
    public $PointSize;
    /// the default font angle, default is 0
    public $Angle;
    /// the default font x adjustment, default is 0
    public $XAdjust;
    /// the default font y adjustment, default is 0
    public $YAdjust;
    /// whether to reuse cache files or not
    public $UseCache;
    /// the color array, default is bgcolor=white and textcolor=black
    public $Colors;
    /// Whether image GD is supported
    public $ImageGDSupported;
    /// Storage Format, default is "png"
    public $StoreAs = 'png';
}

?>
