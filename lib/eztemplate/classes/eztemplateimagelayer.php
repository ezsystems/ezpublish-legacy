<?php
//
// Definition of eZTemplateImageLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file eztemplateimagelayer.php
*/

/*!
  \class eZTemplateImageLayer eztemplateimagelayer.php
  \brief The class eZTemplateImageLayer does

*/

define( 'EZ_IMAGE_ALIGN_AXIS_NONE', 0x00 );
define( 'EZ_IMAGE_ALIGN_AXIS_START', 0x01 );
define( 'EZ_IMAGE_ALIGN_AXIS_STOP', 0x02 );
define( 'EZ_IMAGE_ALIGN_AXIS_CENTER', EZ_IMAGE_ALIGN_AXIS_START | EZ_IMAGE_ALIGN_AXIS_STOP );
define( 'EZ_IMAGE_ALIGN_AXIS_MASK', EZ_IMAGE_ALIGN_AXIS_START | EZ_IMAGE_ALIGN_AXIS_STOP );

define( 'EZ_IMAGE_PLACE_CONSTANT', 1 );
define( 'EZ_IMAGE_PLACE_RELATIVE', 2 );

include_once( 'lib/ezutils/classes/ezdir.php' );

class eZTemplateImageFont
{
    function eZTemplateImageFont( $family, $size, $path,
                                  $xAdjustment = 0, $yAdjustment = 0 )
    {
        $this->FontFamily = $family;
        $this->FontPath = $path;
        $this->PointSize = $size;

        $this->XAdjustment = $xAdjustment;
        $this->YAdjustment = $yAdjustment;

        $this->initialize();
    }

    function family()
    {
        return $this->FontFamily;
    }

    function path()
    {
        return $this->FontPath;
    }

    function file()
    {
        return $this->FontFile;
    }

    function realFile()
    {
        return realpath( "." ) . "/" . $this->FontFile;
    }

    function pointSize()
    {
        return $this->PointSize;
    }

    function xAdjustment()
    {
        return $this->XAdjustment;
    }

    function yAdjustment()
    {
        return $this->YAdjustment;
    }

    /*!
     \static
     \return true if the font family \a $fontFamily exists in the path \a $fontPath.
    */
    function exists( $fontFamily, $fontPath )
    {
        return eZTemplateImageFont::fontFile( $fontFamily, $fontPath ) != false;
    }

    function initialize()
    {
        $this->FontFile = eZTemplateImageFont::fontFile( $this->FontFamily, $this->FontPath );
    }

    function fontFile( $fontFamily, $fontPath )
    {
        if ( preg_match( '/\.ttf$/', $fontFamily ) )
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
    var $FontFamily;
    var $FontPath;
    var $FontFile;
    var $PointSize;
    var $XAdjustment;
    var $YAdjustment;
}

class eZTemplateImageFile
{
    function eZTemplateImageFile( $imageObjectRef = null, $imageObject = null, $width = false, $height = false )
    {
        $this->ImageObjectRef =& $imageObjectRef;
        $this->ImageObject =& $imageObject;
        $this->Width = $width;
        $this->Height = $height;
        $this->AlternativeText = '';
        $this->IsTrueColor = null;
        $this->Palette = array();
        $this->PaletteIndex = array();
        $this->StoredPath = false;
        $this->Font = null;
        $this->IsProcessed = false;
    }

    function isTruecolor()
    {
        return $this->IsTrueColor;
    }

    function attributeMemberMap()
    {
        return array( 'filepath' => 'StoredPath',
                      'filename' => 'StoredFile',
                      'width' => 'Width',
                      'height' => 'Height',
                      'alternative_text' => 'AlternativeText' );
    }

    function attributeFunctionMap()
    {
        return array( 'imagepath' => 'imagePath',
                      'has_size' => 'hasSize' );
    }

    function attributes()
    {
        return array_merge( array_keys( eZTemplateImageFile::attributeMemberMap() ),
                            array_keys( eZTemplateImageFile::attributeFunctionMap() ) );
    }

    function hasAttribute( $name )
    {
        $attributeMemberMap = eZTemplateImageFile::attributeMemberMap();
        if ( isset( $attributeMemberMap[$name] ) )
            return true;
        $attributeFunctionMap = eZTemplateImageFile::attributeFunctionMap();
        if ( isset( $attributeFunctionMap[$name] ) )
            return true;
        return false;
    }

    function &attribute( $name )
    {
        $attributeMemberMap = eZTemplateImageFile::attributeMemberMap();
        if ( isset( $attributeMemberMap[$name] ) )
        {
            $member = $attributeMemberMap[$name];
            if ( isset( $this->$member ) )
                return $this->$member;
            return null;
        }
        $attributeFunctionMap = eZTemplateImageFile::attributeFunctionMap();
        if ( isset( $attributeFunctionMap[$name] ) )
        {
            $function = $attributeFunctionMap[$name];
            if ( method_exists( $this, $function ) )
                return $this->$function();
            return null;
        }
        return null;
    }

    function isProcessed()
    {
        return $this->IsProcessed;
    }

    function hasSize()
    {
        return $this->Width !== false and $this->Height !== false;
    }

    function &imagePath()
    {
        return $this->StoredPath . '/' . $this->StoredFile;
    }

    function setAlternativeText( $text )
    {
        $this->AlternativeText = $text;
    }

    function &alternativeText()
    {
        return $this->AlternativeText;
    }

    function registerImage( $image )
    {
        $imageObjectRef = md5( microtime() );
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
        {
            $createdImageArray = array();
            register_shutdown_function( 'eZGlobalImageCleanupFunction' );
        }
        $createdImageArray[$imageObjectRef] = $image;
        return $imageObjectRef;
    }

    function unregisterImage( $imageRef )
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        if ( !isset( $createdImageArray[$imageRef] ) )
            return;
        unset( $createdImageArray[$imageRef] );
    }

    function cleanupRegisteredImages()
    {
        $createdImageArray =& $GLOBALS['eZImageCreatedArray'];
        if ( !is_array( $createdImageArray ) )
            return;
        foreach ( array_keys( $createdImageArray ) as $createImageKey )
        {
            $createdImage = $createdImageArray[$createImageKey];
            ImageDestroy( $createdImage );
        }
    }


    function loadPNG( $storedPath, $storedFile )
    {
        $this->ImageObject =& ImageCreateFromPNG( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZTemplateImageFile::registerImage( $this->ImageObject );
            return true;
        }
        return false;
    }

    function loadJPEG( $storedPath, $storedFile )
    {
        $this->ImageObject =& ImageCreateFromJPEG( $storedPath . '/' . $storedFile );
        if ( $this->ImageObject )
        {
            $this->ImageObjectRef = eZTemplateImageFile::registerImage( $this->ImageObject );
            return true;
        }
        return false;
    }

    function load()
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
            return;
        switch( $this->StoredType )
        {
            case 'png':
            {
                return $this->loadPNG( $this->StoredPath, $this->StoredFile );
            } break;

            case 'jpg':
            {
                return $this->loadJPEG( $this->StoredPath, $this->StoredFile );
            } break;

            default:
            {
                if ( @$this->loadPNG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                else if ( @$this->loadJPEG( $this->StoredPath, $this->StoredFile ) )
                    return true;
                eZDebug::writeError( 'Image format not supported: ' . $this->StoredType, 'eZTemplateImageFile::load' );
            };
        }
        return false;
    }

    function destroy()
    {
        if ( $this->ImageObjectRef === null )
            return;
        ImageDestroy( $this->ImageObject );
        eZTemplateImageFile::unregisterImage( $this->ImageObjectRef );
        $this->ImageObject = null;
        $this->ImageObjectRef = null;
    }

    function imageObject( $createMissing = true )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
        {
            if ( $createMissing )
            {
                if ( $this->StoredFile != '' )
                {
                    $this->process();
                }
            }
        }
        return $this->ImageObject;
    }

    function imageObjectInternal( $createMissing = true )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
        {
            if ( $createMissing )
                $this->create( $this->Width, $this->Height );
        }
        return $this->ImageObject;
    }

    function process()
    {
        if ( $this->processImage() )
            $this->IsProcessed = true;
    }

    /*!
     \virtual
    */
    function processImage()
    {
        if ( $this->StoredFile == '' )
            return true;
        $fileArray = array( $this->StoredPath, $this->StoredFile );
        $filePath = eZDir::path( $fileArray );
        $imageinfo = getimagesize( $filePath );
        if ( $imageinfo )
        {
            $width = $imageinfo[0];
            $height = $imageinfo[1];
            if ( $this->load() )
            {
                $this->Width = $width;
                $this->Height = $height;
                return true;
            }
            else
                eZDebug::writeWarning( "Image failed to load '$filePath'", 'eZTemplateImageFile::imageObject' );
        }
        else
            eZDebug::writeWarning( "No image info could be extracted from '$filePath'", 'eZTemplateImageFile::imageObject' );
        return false;
    }

    function store( $fileName, $filePath, $type )
    {
        if ( !$this->IsProcessed )
            $this->process();
        $imageObject =& $this->imageObject();
        switch( $type )
        {
            case 'png':
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                if ( !file_exists( $filePath ) )
                {
                    $ini =& eZINI::instance();
                    $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
                    eZDir::mkdir( $filePath, octdec( $perm ), true );
                }
                $fileFullPath = eZDir::path( array( $filePath, $fileName ) );
                ImagePNG( $imageObject, $fileFullPath );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;

            case 'jpg':
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                if ( !file_exists( $filePath ) )
                {
                    $ini =& eZINI::instance();
                    $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
                    eZDir::mkdir( $filePath, octdec( $perm ), true );
                }
                ImageJPEG( $imageObject, eZDir::path( array( $filePath, $fileName ) ) );
                $this->StoredPath = $filePath;
                $this->StoredFile = $fileName;
                $this->StoredType = $type;
                return true;
            } break;

            default:
            {
                eZDebug::writeError( 'Image format not supported: ' . $type, 'eZTemplateImageFile::store' );
            };
        }
        return false;
    }

    /*!
     \static
     \return true if GD2 is installed.
    */
    function hasGD2()
    {
        return true;
    }

    /*!
     \static
     \private
     Creates an image with size \a $width and \a $height using GD and returns it.
    */
    function createImage( $width, $height, &$useTruecolor )
    {
        if ( $useTruecolor === null )
            $useTruecolor = eZTemplateImageFile::hasGD2();
        if ( $useTruecolor and
             !function_exists( 'ImageCreateTrueColor' ) )
        {
            eZDebug::writeWarning( 'Function ImageCreateTrueColor does not exist, cannot create true color images',
                                   'eZTemplateImageLayer::createImage' );
            $useTruecolor = false;
        }
        if ( $useTruecolor )
            $imageObject = ImageCreateTrueColor( $width, $height );
        else
            $imageObject = ImageCreate( $width, $height );
        return $imageObject;
    }

    function create( $width, $height, $useTruecolor = null )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        $this->ImageObject = eZTemplateImageFile::createImage( $width, $height, $useTruecolor );
        $this->Width = $width;
        $this->Height = $height;
        eZDebug::writeDebug( $this->ImageObject, 'create' );
        $this->IsTrueColor = $useTruecolor;
        $this->ImageObjectRef = eZTemplateImageFile::registerImage( $this->ImageObject );
    }

    function clone( &$image )
    {
        $this->cloneImage( $image->imageObject(), $image->width(), $image->height(),
                           $image->isTruecolor() );
    }

    function cloneImage( $imageObject, $width, $height, $useTruecolor = null )
    {
        if ( $this->ImageObject !== null and
             $this->ImageObjectRef !== null )
        {
            $this->destroy();
        }
        $this->ImageObject = eZTemplateImageFile::createImage( $width, $height, $useTruecolor );
        $this->IsTrueColor = $useTruecolor;
        $this->ImageObjectRef = eZTemplateImageFile::registerImage( $this->ImageObject );
        ImageCopy( $this->ImageObject, $imageObject, 0, 0, 0, 0, $width, $height );
    }


    function width()
    {
        return $this->Width;
    }

    function height()
    {
        return $this->Height;
    }

    function setWidth( $w )
    {
        $this->Width = $w;
    }

    function setHeight( $h )
    {
        $this->Height = $h;
    }

    function setStoredFile( $file, $path, $type )
    {
        $this->StoredFile = $file;
        $this->StoredPath = $path;
        $this->StoredType = $type;
    }

    function setFont( $font )
    {
        $this->Font = $font;
    }

    function font()
    {
        return $this->Font;
    }

    function copyImage( $destinationImageObject, $imageObject,
                        $destinationX, $destinationY,
                        $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0 )
    {
        ImageCopy( $destinationImageObject, $imageObject,
                   $destinationX, $destinationY,
                   $sourceX, $sourceY, $sourceWidth, $sourceHeight );
    }

    function mergeImage( $destinationImageObject, $imageObject,
                         $destinationX, $destinationY,
                         $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0,
                         $transparency = 0 )
    {
        $percent = 100 - $transparency;
        ImageCopyMerge( $destinationImageObject, $imageObject,
                        $destinationX, $destinationY,
                        $sourceX, $sourceY, $sourceWidth, $sourceHeight,
                        $percent );
    }

    function blendImage( $destinationImageObject, $imageObject,
                         $destinationX, $destinationY,
                         $sourceWidth, $sourceHeight, $sourceX = 0, $sourceY = 0 )
    {
        ImageAlphaBlending( $destinationImageObject, true );
        ImageCopy( $destinationImageObject, $imageObject,
                   $destinationX, $destinationY,
                   $sourceX, $sourceY, $sourceWidth, $sourceHeight );
    }

    function merge( $imageObject, $x, $y, $width, $height )
    {
        if ( $this->ImageObject === null or
             $this->ImageObjectRef === null )
            return false;
//         ImageAlphaBlending( $this->ImageObject, true );
        imagecolortransparent( $imageObject, 0 );
//         ImageCopy( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height );
        ImageCopyMerge( $this->ImageObject, $imageObject, $x, $y, 0, 0, $width, $height, 50 );
    }

    function allocateColor( $name, $red, $green, $blue )
    {
        if ( isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color already defined: ' . $name, 'eZTemplateImageFile::allocateColor' );
            return null;
        }
        $red = max( 0, min( 255, $red ) );
        $green = max( 0, min( 255, $green ) );
        $blue = max( 0, min( 255, $blue ) );
        $color = ImageColorAllocate( $this->ImageObject, $red, $green, $blue );
        if ( count( $this->PaletteIndex ) == 0 )
        {
//             imagecolortransparent( $this->ImageObject, $color );
//             ImageFilledRectangle( $this->ImageObject, 0, 0, $this->Width, $this->Height, $color );
        }
        $this->Palette[$name] = $color;
        $this->PaletteIndex[] = $color;
        return $color;
    }

    function color( $name )
    {
        if ( !isset( $this->Palette[$name] ) )
        {
            eZDebug::writeError( 'Color not defined: ' . $name, 'eZTemplateImageFile::color' );
            return null;
        }
        return $this->Palette[$name];
    }

    function textColor()
    {
        return $this->TextColor;
    }

    function setTextColor( $textColor )
    {
        $this->TextColor = $textColor;
    }

    function drawText( &$font, $textColor, $text, $x, $y, $angle,
                       $imageObject = null )
    {
        if ( !$font )
        {
            eZDebug::writeWarning( 'Cannot render text, no font is set',
                                   'eZTemplateImageFile::drawText' );
            return false;
        }
        if ( !$textColor )
        {
            eZDebug::writeWarning( 'Cannot render text, no text color is set',
                                   'eZTemplateImageFile::drawText' );
            return false;
        }
        if ( is_string( $textColor ) )
            $textColor = $this->color( $textColor );
        if ( $textColor === null )
        {
            eZDebug::writeWarning( 'Cannot render text, invalid text color',
                                   'eZTemplateImageFile::drawText' );
            return false;
        }

        $x += $font->xAdjustment();
        $y += $font->yAdjustment();

        if ( $imageObject === null )
            $imageObject = $this->ImageObject;

        ImageTTFText( $imageObject, $font->pointSize(), $angle, $x, $y,
                      $textColor, $font->realFile(), $text );
    }

    /// \privatesection
    var $Width;
    var $Height;
    var $Font;
    var $ImageObject;
    var $ImageObjectRef;
    var $StoredFile;
    var $StoredPath;
    var $StoredType;
    var $PaletteIndex;
    var $Palette;
    var $AlternativeText;
    var $IsTrueColor;
    var $IsProcessed;
}

class eZTemplateImageObject extends eZTemplateImageFile
{
    function eZTemplateImageObject( $imageObjectRef = null, $imageObject = null, $width = false, $height = false )
    {
        $this->eZTemplateImageFile( $imageObjectRef, $imageObject, $width, $height );
//         $this->ImageObjectRef =& $imageObjectRef;
//         $this->ImageObject =& $imageObject;
//         $this->Width = $width;
//         $this->Height = $height;
        $this->TemplateURI = 'design:image/imageobject.tpl';
//         $this->AlternativeText = '';
        $this->ImageLayers = array();
        $this->ImageLayerCounter = 0;
    }

    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'image',
                      'uri' => $this->TemplateURI );
    }

    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    /*!
     Figures out the absolute axis placement and returns it.
     The variable \a $type determines how \a $value is used, it can either
     be a constant value (EZ_IMAGE_PLACE_CONSTANT) or a relative value
     (EZ_IMAGE_PLACE_RELATIVE) where input value is placed relative to the length
     of the axis (\a $axisStop - \a $axisStart).
     \a $alignment determines where the axis should start, EZ_IMAGE_ALIGN_AXIS_NONE
     and EZ_IMAGE_ALIGN_AXIS_START will return the position from \a $axisStart towards \a $axisStop,
     EZ_IMAGE_ALIGN_AXIS_STOP returns the position from the \a $axisStop towards \a $axisStart
     while EZ_IMAGE_ALIGN_AXIS_CENTER returns the middle of \a $axisStart and \a $axisStop.
    */
    function calculateAxisPlacement( $value, $type, $alignment, $axisStart, $axisStop, $currentLength )
    {
        $pos = 0;
        if ( $type == EZ_IMAGE_PLACE_CONSTANT )
            $pos = $value;
        else if ( $type == EZ_IMAGE_PLACE_RELATIVE )
        {
            $length = $axisStop - $axisStart;
            $pos = $value * $length;
        }
        $alignment = $alignment & EZ_IMAGE_ALIGN_AXIS_MASK;
        if ( $alignment == EZ_IMAGE_ALIGN_AXIS_NONE or
             $alignment == EZ_IMAGE_ALIGN_AXIS_START )
            return $axisStart + $pos;
        if ( $alignment == EZ_IMAGE_ALIGN_AXIS_CENTER )
        {
            $length = $axisStop - $axisStart;
            $halfLength = (int)(($length - $currentLength) / 2);
            return $axisStart + $halfLength;
        }
        else // Align to stop
        {
            $pos = $axisStop - $pos - $currentLength;
            return $pos;
        }
    }

    /*!
     Initializes the axis from the parameter value \a $name filling
     in any missing values with defaults.
    */
    function initializeAxis( $parameters, $name )
    {
        $axis = array();
        if ( isset( $parameters[$name] ) )
            $axis = $parameters[$name];
        if ( !isset( $axis['alignment'] ) )
            $axis['alignment'] = EZ_IMAGE_ALIGN_AXIS_NONE;
        if ( !isset( $axis['placement'] ) )
            $axis['placement'] = EZ_IMAGE_PLACE_CONSTANT;
        if ( !isset( $axis['value'] ) )
            $axis['value'] = 0;
        return $axis;
    }

    /*!
     Calculates the position for \c x and \c y parameters in \a $parameters and returns
     an absolute position in an array.
     The returned array will contain the \c x and \c y key.
    */
    function calculatePosition( $parameters, $width, $height )
    {
        $xAxis = eZTemplateImageObject::initializeAxis( $parameters, 'x' );
        $yAxis = eZTemplateImageObject::initializeAxis( $parameters, 'y' );
        $x = eZTemplateImageObject::calculateAxisPlacement( $xAxis['value'], $xAxis['placement'], $xAxis['alignment'],
                                                            0, $this->width(), $width );
        $y = eZTemplateImageObject::calculateAxisPlacement( $yAxis['value'], $yAxis['placement'], $yAxis['alignment'],
                                                            0, $this->height(), $height );
        return array( 'x' => $x,
                      'y' => $y );
    }

    /*!
     \return the transparency value found in the parameters or 0 if no value is found.
    */
    function getTransparency( $parameters )
    {
        if ( !isset( $parameters['transparency'] ) )
        {
            return 0.0;
        }
        $transparency = max( 0.0, min( 1.0, $parameters['transparency'] ) );
        return $transparency;
    }

    /*!
     \return the transparency percentage found in the parameters or 0 if no percentage is found.
    */
    function getTransparencyPercent( $parameters )
    {
        $transparency = eZTemplateImageObject::getTransparency( $parameters );
        return (int)($transparency * 100.0);
    }

    /*!
     Adds the image layer object \a $imageLayer to the end of the layer list with optional parameters \a $parameters
     \return the ID of the layer, the ID is unique per image object.
     \sa prependLayer
    */
    function appendLayer( &$imageLayer, $parameters = array() )
    {
        if ( get_class( $imageLayer ) != 'eztemplateimagelayer' and
             !is_subclass_of( $imageLayer, 'eztemplateimagelayer' ) )
        {
            eZDebug::writeWarning( 'Only eZTemplateImageLayer objects may be added as layer items',
                                   'eZTemplateImageObject::appendLayer' );
            return false;
        }
        ++$this->ImageLayerCounter;
        $layerID = $this->ImageLayerCounter;
        $this->ImageLayers[] = $layerID;
        $this->ImageLayerIndex[$layerID] = array( 'image' => &$imageLayer,
                                                  'parameters' => $parameters );
        return $layerID;
    }

    /*!
     Adds the image layer object \a $imageLayer to the beginning of the layer list with optional parameters \a $parameters
     \return the ID of the layer, the ID is unique per image object.
     \sa appendLayer
    */
    function prependLayer( &$imageLayer, $parameters = array() )
    {
        if ( get_class( $imageLayer ) != 'eztemplateimagelayer' and
             !is_subclass_of( $imageLayer, 'eztemplateimagelayer' ) )
        {
            eZDebug::writeWarning( 'Only eZTemplateImageLayer objects may be added as layer items',
                                   'eZTemplateImageObject::prependLayer' );
            return false;
        }
        ++$this->ImageLayerCounter;
        $layerID = $this->ImageLayerCounter;
        $this->ImageLayers = array_merge( array( $layerID ),
                                          $this->ImageLayers );
        $this->ImageLayerIndex[$layerID] = array( 'image' => &$imageLayer,
                                                  'parameters' => $parameters );
        return $layerID;
    }

    /*!
     \return true if the layer with ID \a $layerID exists in this image object.
    */
    function hasLayer( $layerID )
    {
        return ( in_array( $layerID, $this->ImageLayers ) and
                 array_key_exists( $layerID, $this->ImageLayerIndex ) );
    }

    /*!
     Removes the layer with ID \a $layerID.
     \return true if succesful
     \sa hasLayer, appendLayer
    */
    function removeLayer( $layerID )
    {
        if ( !in_array( $layerID, $this->ImageLayers ) or
             !array_key_exists( $layerID, $this->ImageLayerIndex ) )
            return false;
        $layerData = $this->ImageLayerIndex[$layerID];
        unset( $this->ImageLayerIndex[$layerID] );
        $imageLayers = array();
        foreach ( $this->ImageLayers as $imageLayerID )
        {
            if ( $imageLayerID != $layerID )
                $imageLayers[] = $imageLayerID;
        }
        $this->ImageLayers = $imageLayers;
        return true;
    }

    /*!
     \virtual
    */
    function processImage()
    {
        $this->flatten();
        return true;
    }

    function flatten()
    {
        $i = 0;
        $hasFirst = false;
        $firstImageLayer = null;
        $firstImageLayerData = null;
        while ( $i < count( $this->ImageLayers ) and
                !$hasFirst )
        {
            $layerID = $this->ImageLayers[$i];
            $layerData =& $this->ImageLayerIndex[$layerID];
            $layer =& $layerData['image'];
            if ( get_class( $layer ) == 'eztemplateimagelayer' or
                 is_subclass_of( $layer, 'eztemplateimagelayer' ) )
            {
                $firstImageLayerData =& $layerData;
                $firstImageLayer =& $layer;
                $hasFirst = true;
            }
            else
                eZDebug::writeWarning( 'Wrong image type ' . gettype( $layer ), 'eZTemplateImageLayer::flatten' );
            ++$i;
        }
        if ( $hasFirst )
        {
            $firstImageLayer->imageObject();
            if ( !$this->width() )
            {
                $this->setWidth( $firstImageLayer->width() );
            }
            if ( !$this->height() )
            {
                $this->setHeight( $firstImageLayer->height() );
            }
            $firstImageLayer->mergeLayer( $this,
                                          $firstImageLayerData,
                                          $lastImageLayerData );
            $lastImageLayerData = null;
            while ( $i < count( $this->ImageLayers ) )
            {
                $layerID = $this->ImageLayers[$i];
                $layerData =& $this->ImageLayerIndex[$layerID];
                $layer =& $layerData['image'];
                unset( $imageObject );
                if ( get_class( $layer ) == 'eztemplateimagelayer' or
                     is_subclass_of( $layer, 'eztemplateimagelayer' ) )
                {
                    $layer->mergeLayer( $this,
                                        $layerData,
                                        $lastImageLayerData );
                    $lastImageLayerData =& $layerData;
                }
                else
                {
                    eZDebug::writeWarning( 'Wrong image type ' . gettype( $layer ), 'eZTemplateImageLayer::flatten' );
                }
                ++$i;
            }
            return true;
        }
        return false;
    }

    /// \privatesection
    var $ImageLayers;
    var $TemplateURI;
}

class eZTemplateImageLayer extends eZTemplateImageFile
{
    /*!
     Constructor
    */
    function eZTemplateImageLayer( $imageObjectRef = null, $imageObject = null,
                                   $width = false, $height = false, $font = false )
    {
        $this->eZTemplateImageFile( $imageObjectRef, $imageObject, $width, $height );
        $this->setFont( $font );
        $this->TemplateURI = 'design:image/layer.tpl';
    }

    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'layer',
                      'uri' => $this->TemplateURI );
    }

    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    function mergeLayer( &$image, &$layerData, &$lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $imageObject =& $this->imageObject();
        if ( $lastLayerData === null )
        {
            $destinationImageObject =& $image->imageObjectInternal( false );
            if ( $destinationImageObject === null )
            {
                $image->clone( $this );
            }
            else
            {
                $image->mergeImage( $destinationImageObject, $imageObject,
                                    $x, $y,
                                    $this->width(), $this->height(), 0, 0,
                                    $image->getTransparencyPercent( $layerData['parameters'] ) );
            }
        }
        else
        {
            $destinationImageObject =& $image->imageObjectInternal();
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    function &createForFile( $fileName, $filePath, $fileType = false )
    {
        $layer =& new eZTemplateImageLayer();
        $layer->setStoredFile( $fileName, $filePath, $fileType );
        $layer->process();
        return $layer;
    }

    /// \privatesection
    var $TemplateURI;
}

class eZTemplateTextLayer extends eZTemplateImageLayer
{
    /*!
     Constructor
    */
    function eZTemplateTextLayer( $imageObjectRef = null, $imageObject = null,
                                  $width = false, $height = false,
                                  $font = false, $boundingBox = null, $text = null, $textAngle = 0 )
    {
        $this->eZTemplateImageLayer( $imageObjectRef, $imageObject, $width, $height, $font );
        $this->Text = $text;
        $this->TextAngle = $textAngle;
        $this->TextBoundingBox = $boundingBox;
    }

    /*!
     \virtual
    */
    function processImage()
    {
        $destinationImageObject = $this->imageObjectInternal();
        $bbox = $this->textBoundingBox();
        $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                         $destinationImageObject );
        return true;
    }

    function mergeLayer( &$image, &$layerData, &$lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $destinationImageObject =& $image->imageObjectInternal( false );
        if ( $lastLayerData === null and
             $destinationImageObject === null )
        {
            $destinationImageObject =& $image->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle(),
                             $destinationImageObject );
        }
        else
        {
            $destinationImageObject =& $image->imageObjectInternal();
            $imageObject =& $this->imageObjectInternal();
            $bbox = $this->textBoundingBox();
            $image->copyImage( $imageObject, $destinationImageObject,
                               0, 0, $this->width(), $this->height(),
                               $x, $y );
            $this->drawText( $this->font(), $this->textColor(), $this->text(), $bbox[6], -$bbox[7], $this->textAngle() );
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    function setText( $text )
    {
        $this->Text = $text;
    }

    function text()
    {
        return $this->Text;
    }

    function setTextAngle( $textAngle )
    {
        $this->TextAngle = $textAngle;
    }

    function textAngle()
    {
        return $this->TextAngle;
    }

    function textBoundingBox()
    {
        return $this->TextBoundingBox;
    }

    function &createForText( $text, &$font, $widthAdjustment, $heightAdjustment, $angle )
    {
        if ( get_class( $font ) != 'eztemplateimagefont' )
            return false;
        if ( !function_exists( 'ImageTTFBBox' ) )
        {
            eZDebug::writeError( 'ImageTTFBBox function not in PHP, check PHP compilation', 'eztemplateimagelayer.php' );
            return false;
        }
        $bbox = ImageTTFBBox( $font->pointSize(), $angle, $font->realFile(), $text );

        if ( !$bbox )
            return false;

        $width = $bbox[4] - $bbox[6];
        $height = $bbox[1] - $bbox[7];
        $width += $widthAdjustment;
        $height += $heightAdjustment;

        $layer = new eZTemplateTextLayer( null, null, $width, $height,
                                          $font, $bbox, $text, $angle );
        $layer->create( $width, $height, null );
        return $layer;
    }

    /// \privatesection
    var $TextBoundingBox;
    var $Text;
    var $Angle;
}

function eZGlobalImageCleanupFunction()
{
    eZTemplateImageFile::cleanupRegisteredImages();
}

?>
